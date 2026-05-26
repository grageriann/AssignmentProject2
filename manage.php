<?php
session_start();
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    header("Location: login.php");
    exit;
}
require_once "settings.php";

$message = "";

// 1. Intercept Status Changes and Erasures
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["update_status"])) {
    $eoi_number = (int)$_POST["eoi_number"];
    $status = trim($_POST["status"] ?? "");
    if (in_array($status, ['New', 'Current', 'Final'])) {
        $query = "UPDATE eoi SET Status = ? WHERE EOInumber = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $status, $eoi_number);
        if (mysqli_stmt_execute($stmt)) {
            $message = "EOI #$eoi_number status updated to $status.";
        }
        mysqli_stmt_close($stmt);
    }
  }

  if (isset($_POST["delete_eoi"])) {
    $eoi_number = (int)$_POST["eoi_number"];
    $query = "DELETE FROM eoi WHERE EOInumber = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $eoi_number);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Application record #$eoi_number removed permanently.";
    }
    mysqli_stmt_close($stmt);
  }

  if (isset($_POST["bulk_delete"])) {
    $bulk_ref = trim($_POST["bulk_job_ref"] ?? "");
    if (!empty($bulk_ref)) {
        $query = "DELETE FROM eoi WHERE JobReferenceNumber = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $bulk_ref);
        if (mysqli_stmt_execute($stmt)) {
            $count = mysqli_affected_rows($conn);
            $message = "Bulk Purge Complete: Removed $count applications matching code '$bulk_ref'.";
        }
        mysqli_stmt_close($stmt);
    }
  }
}

// 2. Sorting & Search Logic Matrix
$search_job  = isset($_GET["search_job"]) ? trim($_GET["search_job"]) : "";
$search_name = isset($_GET["search_name"]) ? trim($_GET["search_name"]) : "";
$sort_field  = isset($_GET["sort_by"]) ? trim($_GET["sort_by"]) : "EOInumber";

$allowed_sorts = [
    "EOInumber"          => "EOInumber",
    "JobReferenceNumber" => "JobReferenceNumber",
    "FirstName"          => "FirstName",
    "LastName"           => "LastName",
    "Status"             => "Status",
    "DateSubmitted"      => "DateSubmitted"
];
$order_column = $allowed_sorts[$sort_field] ?? "EOInumber";

$sql = "SELECT * FROM eoi WHERE 1=1";
$params = [];
$types = "";

if ($search_job !== "") {
    $sql .= " AND JobReferenceNumber = ?";
    $params[] = $search_job;
    $types .= "s";
}

if ($search_name !== "") {
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

<main class="page-container">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
      <h1>Manager Administration Dashboard</h1>
      <a href="logout.php" style="padding:6px 12px; background:#d32f2f; color:#fff; text-decoration:none; border-radius:4px; font-weight:bold; font-size:0.9rem;">Sign Out</a>
  </div>

  <?php if (!empty($message)): ?>
      <p style="padding:10px; background:var(--hover-blue); border:1px solid var(--primary-blue); color:var(--primary-blue); font-weight:bold; border-radius:4px;"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
      <form method="get" action="manage.php" style="flex: 1; min-width: 280px; padding: 15px; border: 1px solid #ccc; border-radius: 6px;">
          <h3 style="margin-top:0; color:var(--primary-blue);">Search Filters & Sorting</h3>
          
          <label for="search_job">Job Reference Number:</label>
          <input type="text" id="search_job" name="search_job" value="<?php echo htmlspecialchars($search_job); ?>" placeholder="e.g. FE123">
          
          <label for="search_name">Applicant Name:</label>
          <input type="text" id="search_name" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>" placeholder="First, Last, or both">
          
          <label for="sort_by">Sort Database Rows By:</label>
          <select id="sort_by" name="sort_by">
              <option value="EOInumber" <?php if($sort_field==="EOInumber") echo "selected";?>>EOI ID</option>
              <option value="JobReferenceNumber" <?php if($sort_field==="JobReferenceNumber") echo "selected";?>>Job Reference</option>
              <option value="FirstName" <?php if($sort_field==="FirstName") echo "selected";?>>First Name</option>
              <option value="LastName" <?php if($sort_field==="LastName") echo "selected";?>>Last Name</option>
              <option value="Status" <?php if($sort_field==="Status") echo "selected";?>>Status State</option>
              <option value="DateSubmitted" <?php if($sort_field==="DateSubmitted") echo "selected";?>>Date Submitted</option>
          </select>
          
          <button type="submit" style="background: var(--primary-blue); color: var(--white); width: 100%; margin-top: 10px; padding: 8px;">Apply Filters</button>
      </form>

      <form method="post" action="manage.php" onsubmit="return confirm('WARNING: Clear ALL profiles matching this code?');" style="flex: 1; min-width: 280px; padding: 15px; border: 1px solid #d32f2f; border-radius: 6px;">
          <h3 style="margin-top:0; color:#d32f2f;">Bulk Category Deletion</h3>
          <p style="font-size:0.85rem; color:var(--text-color);">Removes all database listings under a selected position code category simultaneously.</p>
          
          <label for="bulk_job_ref" style="color:#d32f2f;">Target Job Code Reference:</label>
          <input type="text" id="bulk_job_ref" name="bulk_job_ref" placeholder="e.g. WD245" required style="border-color:#d32f2f;">
          
          <button type="submit" name="bulk_delete" style="background: #d32f2f; color: var(--white); width: 100%; margin-top: 35px; padding: 8px;">Execute Bulk Purge</button>
      </form>
  </div>

  <table>
      <caption>Retrieved Expression Records Matrix</caption>
      <thead>
          <tr>
              <th>ID</th>
              <th>Job Ref</th>
              <th>Applicant</th>
              <th>Contact Details</th>
              <th>Logged Skills</th>
              <th>Status</th>
              <th>Modify Status / Delete</th>
          </tr>
      </thead>
      <tbody>
          <?php if ($result && mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                      <td><strong>#<?php echo $row["EOInumber"]; ?></strong></td>
                      <td style="color:var(--primary-blue); font-weight:bold;"><?php echo htmlspecialchars($row["JobReferenceNumber"]); ?></td>
                      <td><?php echo htmlspecialchars($row["FirstName"] . " " . $row["LastName"]); ?><br><small>(<?php echo htmlspecialchars($row["DOB"]); ?>)</small></td>
                      <td>
                          <div><?php echo htmlspecialchars($row["EmailAddress"]); ?></div>
                          <div><?php echo htmlspecialchars($row["PhoneNumber"]); ?></div>
                      </td>
                      <td>
                          <div style="font-weight:bold; color:var(--primary-blue);"><?php echo htmlspecialchars($row["Skills"]); ?></div>
                          <div style="font-size:0.8rem; color:#666;"><?php echo htmlspecialchars($row["OtherSkills"]); ?></div>
                      </td>
                      <td><span style="font-weight:bold;"><?php echo htmlspecialchars($row["Status"]); ?></span></td>
                      <td>
                          <div style="display:flex; gap:5px; align-items:center; justify-content:center;">
                              <form method="post" action="manage.php" style="display:inline-flex; gap:2px; margin:0; padding:0; border:none; box-shadow:none;">
                                  <input type="hidden" name="eoi_number" value="<?php echo $row["EOInumber"]; ?>">
                                  <select name="status" style="padding:2px; font-size:0.8rem; width:auto; margin:0;">
                                      <option value="New" <?php if ($row["Status"] === "New") echo "selected"; ?>>New</option>
                                      <option value="Current" <?php if ($row["Status"] === "Current") echo "selected"; ?>>Current</option>
                                      <option value="Final" <?php if ($row["Status"] === "Final") echo "selected"; ?>>Final</option>
                                  </select>
                                  <button type="submit" name="update_status" style="padding:4px 6px; font-size:0.8rem; background:var(--primary-blue); color:white;">Set</button>
                              </form>
                              <form method="post" action="manage.php" onsubmit="return confirm('Delete permanently?');" style="display:inline-block; margin:0; padding:0; border:none; box-shadow:none;">
                                  <input type="hidden" name="eoi_number" value="<?php echo $row["EOInumber"]; ?>">
                                  <button type="submit" name="delete_eoi" style="padding:4px 6px; font-size:0.8rem; background:#d32f2f; color:white;">X</button>
                              </form>
                          </div>
                      </td>
                  </tr>
              <?php endwhile; ?>
          <?php else: ?>
              <tr>
                  <td colspan="7" style="text-align:center; font-weight:bold; color:#666;">No matching records found.</td>
              </tr>
          <?php endif; ?>
      </tbody>
  </table>
</main>

<?php 
mysqli_stmt_close($stmt);
mysqli_close($conn);
include_once("footer.inc"); 
?>