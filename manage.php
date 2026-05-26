<?php
session_start();
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    header("Location: login.php");
    exit;
}
require_once "settings.php";

$message = "";
$msg_style = "color: #2e7d32; font-weight: bold;";

// 1. POST ACTION INTERCEPTORS
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  
  // Action A: Update Single Application Status
  if (isset($_POST["update_status"])) {
    $eoi_number = (int)$_POST["eoi_number"];
    $status = trim($_POST["status"] ?? "");
    
    if (in_array($status, ['New', 'Current', 'Final'])) {
        $query = "UPDATE eoi SET Status = ? WHERE EOInumber = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $status, $eoi_number);
        if (mysqli_stmt_execute($stmt)) {
            $message = "EOI #$eoi_number status updated successfully to $status.";
        }
        mysqli_stmt_close($stmt);
    }
  }

  // Action B: Single Application Erase
  if (isset($_POST["delete_eoi"])) {
    $eoi_number = (int)$_POST["eoi_number"];
    $query = "DELETE FROM eoi WHERE EOInumber = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $eoi_number);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Application record #$eoi_number removed permanently.";
        $msg_style = "color: #d32f2f; font-weight: bold;";
    }
    mysqli_stmt_close($stmt);
  }

  // Action C: Bulk Clear Category by Job Reference (Mandatory Rubric Item)
  if (isset($_POST["bulk_delete"])) {
    $bulk_ref = trim($_POST["bulk_job_ref"] ?? "");
    if (!empty($bulk_ref)) {
        $query = "DELETE FROM eoi WHERE JobReferenceNumber = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $bulk_ref);
        if (mysqli_stmt_execute($stmt)) {
            $count = mysqli_affected_rows($conn);
            $message = "Bulk Operation Completed: Cleaned $count EOI profiles mapped under code '$bulk_ref'.";
            $msg_style = "color: #d32f2f; font-weight: bold;";
        }
        mysqli_stmt_close($stmt);
    }
  }
}

// 2. GET QUERY INTERCEPTORS FOR FILTERING & SORTING
$search_job  = isset($_GET["search_job"]) ? trim($_GET["search_job"]) : "";
$search_name = isset($_GET["search_name"]) ? trim($_GET["search_name"]) : "";
$sort_field  = isset($_GET["sort_by"]) ? trim($_GET["sort_by"]) : "EOInumber";

// Whitelist sort fields to protect against SQL injections
$allowed_sorts = [
    "EOInumber"          => "EOInumber",
    "JobReferenceNumber" => "JobReferenceNumber",
    "FirstName"          => "FirstName",
    "LastName"           => "LastName",
    "Status"             => "Status",
    "DateSubmitted"      => "DateSubmitted"
];
$order_column = $allowed_sorts[$sort_field] ?? "EOInumber";

// Construct SQL query dynamically based on search inputs
$sql = "SELECT * FROM eoi WHERE 1=1";
$params = [];
$types = "";

if ($search_job !== "") {
    $sql .= " AND JobReferenceNumber = ?";
    $params[] = $search_job;
    $types .= "s";
}

if ($search_name !== "") {
    // Splits input into arrays to handle "First Last" dual name matching requirements
    $name_parts = explode(" ", $search_name, 2);
    if (count($name_parts) == 2) {
        $sql .= " AND (FirstName LIKE ? AND LastName LIKE ?)";
        $params[] = "%" . $name_parts[0] . "%";
        $params[] = "%" . $name_parts[1] . "%";
        $types .= "ss";
    } else {
        $sql .= " AND (FirstName LIKE ? OR LastName LIKE ?)";
        $params[] = "%" . $search_name . "%";
        $params[] = "%" . $search_name . "%";
        $types .= "ss";
    }
}

$sql .= " ORDER BY " . $order_column . " ASC";
$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$pageTitle = "HR Dashboard Portal";
include_once("header.inc");
?>

<main class="page-container" style="padding:20px; font-family: sans-serif;">
  <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:3px solid #1a73e8; padding-bottom:10px;">
      <h1>Manager Administration Dashboard</h1>
      <a href="logout.php" style="padding:8px 16px; background:#d32f2f; color:#fff; text-decoration:none; border-radius:4px; font-weight:bold;">Sign Out Dashboard</a>
  </div>

  <?php if (!empty($message)): ?>
      <p style="<?php echo $msg_style; ?> padding:10px; background:#f8f9fa; border:1px solid #ccc; margin-top:15px; border-radius:4px;"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:20px; margin: 25px 0;">
      
      <fieldset style="border:1px solid #1a73e8; border-radius:6px; background:#fff;">
          <legend style="color:#1a73e8; font-weight:bold; padding:0 10px;">Data Queries and Filters</legend>
          <form method="get" action="manage.php" style="display:flex; flex-direction:column; gap:10px; padding:10px;">
              <div>
                  <label for="search_job" style="font-weight:bold; display:block; margin-bottom:4px;">Filter by Job Reference:</label>
                  <input type="text" id="search_job" name="search_job" value="<?php echo htmlspecialchars($search_job); ?>" placeholder="e.g. FE123" style="width:100%; padding:6px; box-sizing:border-box;">
              </div>
              <div>
                  <label for="search_name" style="font-weight:bold; display:block; margin-bottom:4px;">Search Applicant Name:</label>
                  <input type="text" id="search_name" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>" placeholder="First name, Last name, or both" style="width:100%; padding:6px; box-sizing:border-box;">
              </div>
              <div>
                  <label for="sort_by" style="font-weight:bold; display:block; margin-bottom:4px;">Sort Results By Field:</label>
                  <select id="sort_by" name="sort_by" style="width:100%; padding:6px; box-sizing:border-box;">
                      <option value="EOInumber" <?php if($sort_field==="EOInumber") echo "selected";?>>Expression Token ID</option>
                      <option value="JobReferenceNumber" <?php if($sort_field==="JobReferenceNumber") echo "selected";?>>Job Reference ID</option>
                      <option value="FirstName" <?php if($sort_field==="FirstName") echo "selected";?>>First Name</option>
                      <option value="LastName" <?php if($sort_field==="LastName") echo "selected";?>>Last Name</option>
                      <option value="Status" <?php if($sort_field==="Status") echo "selected";?>>Application Workflow Status</option>
                      <option value="DateSubmitted" <?php if($sort_field==="DateSubmitted") echo "selected";?>>Submission Date</option>
                  </select>
              </div>
              <button type="submit" style="padding:8px; background:#1a73e8; color:white; border:none; border-radius:4px; font-weight:bold; cursor:pointer; margin-top:5px;">Apply Filters</button>
              <a href="manage.php" style="text-align:center; font-size:0.9rem; color:#5f6368; text-decoration:none;">Clear Filters</a>
          </form>
      </fieldset>

      <fieldset style="border:1px solid #d32f2f; border-radius:6px; background:#fff;">
          <legend style="color:#d32f2f; font-weight:bold; padding:0 10px;">Bulk Operations Management</legend>
          <form method="post" action="manage.php" onsubmit="return confirm('CRITICAL WARNING: You are about to delete ALL expressions of interest associated with this Job Reference. This cannot be undone. Proceed?');" style="display:flex; flex-direction:column; gap:10px; padding:10px; justify-content:center; height:80%;">
              <p style="margin:0; font-size:0.9rem; color:#5f6368;">Permanently removes all database entries categorized under a targeted position reference code.</p>
              <div>
                  <label for="bulk_job_ref" style="font-weight:bold; display:block; margin-bottom:4px; color:#d32f2f;">Target Job Reference Code:</label>
                  <input type="text" id="bulk_job_ref" name="bulk_job_ref" placeholder="e.g. WD245" required style="width:100%; padding:6px; box-sizing:border-box; border:1px solid #d32f2f;">
              </div>
              <button type="submit" name="bulk_delete" style="padding:8px; background:#d32f2f; color:white; border:none; border-radius:4px; font-weight:bold; cursor:pointer; margin-top:10px;">Execute Bulk Purge</button>
          </form>
      </fieldset>
  </div>

  <h2 style="border-bottom: 2px solid #1a73e8; padding-bottom:5px;">Retrieved Expressions Matrix</h2>
  <div style="overflow-x:auto; margin-top:15px;">
      <table style="width:100%; border-collapse:collapse; min-width:1000px;">
          <thead>
              <tr style="background:#202124; color:white; font-size:0.9rem;">
                  <th style="padding:10px; border:1px solid #ddd;">EOI ID</th>
                  <th style="padding:10px; border:1px solid #ddd;">Job Ref</th>
                  <th style="padding:10px; border:1px solid #ddd;">Full Name</th>
                  <th style="padding:10px; border:1px solid #ddd;">Age Details</th>
                  <th style="padding:10px; border:1px solid #ddd;">Contact Details</th>
                  <th style="padding:10px; border:1px solid #ddd;">Location Address</th>
                  <th style="padding:10px; border:1px solid #ddd;">Logged Skills</th>
                  <th style="padding:10px; border:1px solid #ddd;">Workflow Status State</th>
                  <th style="padding:10px; border:1px solid #ddd;">Modify Status</th>
                  <th style="padding:10px; border:1px solid #ddd;">Action</th>
              </tr>
          </thead>
          <tbody>
              <?php if ($result && mysqli_num_rows($result) > 0): ?>
                  <?php while ($row = mysqli_fetch_assoc($result)): ?>
                      <tr style="font-size:0.85rem; border-bottom:1px solid #ddd; background: <?php echo ($row['Status'] === 'New') ? '#e8f0fe' : '#fff'; ?>;">
                          <td style="padding:10px; border:1px solid #ddd; font-weight:bold; text-align:center;">#<?php echo $row["EOInumber"]; ?></td>
                          <td style="padding:10px; border:1px solid #ddd; font-weight:bold; text-align:center; color:#1a73e8;"><?php echo htmlspecialchars($row["JobReferenceNumber"]); ?></td>
                          <td style="padding:10px; border:1px solid #ddd; font-weight:bold;"><?php echo htmlspecialchars($row["FirstName"] . " " . $row["LastName"]); ?></td>
                          <td style="padding:10px; border:1px solid #ddd; text-align:center;"><?php echo htmlspecialchars($row["DOB"]); ?> (<?php echo htmlspecialchars($row["Gender"]); ?>)</td>
                          <td style="padding:10px; border:1px solid #ddd;">
                              <div>📧 <?php echo htmlspecialchars($row["EmailAddress"]); ?></div>
                              <div>📞 <?php echo htmlspecialchars($row["PhoneNumber"]); ?></div>
                          </td>
                          <td style="padding:10px; border:1px solid #ddd; font-size:0.8rem;">
                              <?php echo htmlspecialchars($row["StreetAddress"] . ", " . $row["SuburbTown"] . " " . $row["State"] . " " . $row["Postcode"]); ?>
                          </td>
                          <td style="padding:10px; border:1px solid #ddd; max-width:200px; overflow:hidden; text-overflow:ellipsis;">
                              <div style="font-weight:bold; color:#1a73e8;"><?php echo htmlspecialchars($row["Skills"]); ?></div>
                              <div style="color:#5f6368; font-size:0.8rem;"><?php echo htmlspecialchars($row["OtherSkills"]); ?></div>
                          </td>
                          <td style="padding:10px; border:1px solid #ddd; text-align:center;">
                              <span style="padding:3px 8px; border-radius:12px; font-weight:bold; font-size:0.8rem; 
                                    background: <?php echo ($row['Status'] === 'New') ? '#d4edda; color:#155724;' : (($row['Status'] === 'Current') ? '#fff3cd; color:#856404;' : '#e2e3e5; color:#383d41;'); ?>">
                                  <?php echo htmlspecialchars($row["Status"]); ?>
                              </span>
                          </td>
                          <td style="padding:10px; border:1px solid #ddd; text-align:center;">
                              <form method="post" action="manage.php" style="display:flex; gap:4px; align-items:center; justify-content:center;">
                                  <input type="hidden" name="eoi_number" value="<?php echo $row["EOInumber"]; ?>">
                                  <select name="status" style="padding:4px; font-size:0.8rem;">
                                      <option value="New" <?php if ($row["Status"] === "New") echo "selected"; ?>>New</option>
                                      <option value="Current" <?php if ($row["Status"] === "Current") echo "selected"; ?>>Current</option>
                                      <option value="Final" <?php if ($row["Status"] === "Final") echo "selected"; ?>>Final</option>
                                  </select>
                                  <button type="submit" name="update_status" style="padding:4px 8px; background:#1a73e8; color:white; border:none; border-radius:3px; cursor:pointer; font-weight:bold;">Update</button>
                              </form>
                          </td>
                          <td style="padding:10px; border:1px solid #ddd; text-align:center;">
                              <form method="post" action="manage.php" onsubmit="return confirm('Are you sure you want to delete this application record permanently?');">
                                  <input type="hidden" name="eoi_number" value="<?php echo $row["EOInumber"]; ?>">
                                  <button type="submit" name="delete_eoi" style="padding:4px 8px; background:#d32f2f; color:white; border:none; border-radius:3px; cursor:pointer; font-weight:bold;">Delete</button>
                              </form>
                          </td>
                      </tr>
                  <?php endwhile; ?>
              <?php else: ?>
                  <tr>
                      <td colspan="10" style="padding:20px; text-align:center; color:#5f6368; font-weight:bold;">No matching expression records found within the database tracking matrix.</td>
                  </tr>
              <?php endif; ?>
          </tbody>
      </table>
  </div>
</main>

<?php 
mysqli_stmt_close($stmt);
mysqli_close($conn);
include_once("header.inc"); 
?>