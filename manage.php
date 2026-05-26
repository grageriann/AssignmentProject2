<?php
session_start();
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    header("Location: login.php");
    exit;
}
require_once "settings.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["update_status"])) {
    $eoi_number = $_POST["eoi_number"];
    $status = $_POST["status"];

    $query = "UPDATE eoi SET Status = ? WHERE EOInumber = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $eoi_number);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }

  if (isset($_POST["delete_eoi"])) {
    $eoi_number = $_POST["eoi_number"];

    $query = "DELETE FROM eoi WHERE EOInumber = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $eoi_number);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

$search_job  = isset($_GET["search_job"]) ? trim($_GET["search_job"]) : "";
$search_name = isset($_GET["search_name"]) ? trim($_GET["search_name"]) : "";

$query = "SELECT * FROM eoi WHERE 1=1";
$params = [];
$types = "";

if ($search_job !== "") {
    $query .= " AND JobReferenceNumber = ?";
    $params[] = $search_job;
    $types .= "s";
}

if ($search_name !== "") {
    $query .= " AND (FirstName LIKE ? OR LastName LIKE ?)";
    $wildcard_name = "%" . $search_name . "%";
    $params[] = $wildcard_name;
    $params[] = $wildcard_name;
    $types .= "ss";
}

$query .= " ORDER BY DateSubmitted DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Manage Applications</title>
    <link rel="stylesheet" href="styles.css">
  </head>

  <body>
    <header>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="jobs.php">Jobs</a></li>
          <li><a href="apply.php">Apply</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="manage.php">Manage</a></li>
        </ul>
      </nav>
    </header>

    <main>
      <h1>Manage Job Applications</h1>

      <section style="background: #f4f4f4; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <form method="get" action="manage.php" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
          <div>
            <label for="search_job" style="display: block; font-weight: bold; margin-bottom: 5px;">Job Ref:</label>
            <input type="text" id="search_job" name="search_job" value="<?php echo htmlspecialchars($search_job); ?>" style="padding: 6px;">
          </div>
          <div>
            <label for="search_name" style="display: block; font-weight: bold; margin-bottom: 5px;">Applicant Name:</label>
            <input type="text" id="search_name" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>" placeholder="First or last name..." style="padding: 6px;">
          </div>
          <div>
            <button type="submit" style="padding: 6px 12px; cursor: pointer;">Filter</button>
            <a href="manage.php" style="padding: 6px 12px; background: #ddd; color: black; text-decoration: none; border-radius: 3px; margin-left: 5px; font-size: 0.9rem;">Reset</a>
          </div>
        </form>
      </section>

      <table>
        <thead>
          <tr>
            <th>EOI Number</th>
            <th>Job Ref</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Skills</th>
            <th>Status</th>
            <th>Update</th>
            <th>Delete</th>
          </tr>
        </thead>

        <tbody>
          <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
              <td colspan="9" style="text-align: center; padding: 20px; color: #666;">No expressions of interest found matching your search.</td>
            </tr>
          <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td>#<?php echo htmlspecialchars($row["EOInumber"]); ?></td>
                <td><?php echo htmlspecialchars($row["JobReferenceNumber"]); ?></td>
                <td>
                  <?php echo htmlspecialchars($row["FirstName"] . " " . $row["LastName"]); ?>
                </td>
                <td><?php echo htmlspecialchars($row["EmailAddress"]); ?></td>
                <td><?php echo htmlspecialchars($row["PhoneNumber"]); ?></td>
                <td><?php echo htmlspecialchars($row["Skills"]); ?></td>

                <td>
                  <form method="post" action="manage.php">
                    <input type="hidden" name="eoi_number" value="<?php echo htmlspecialchars($row["EOInumber"]); ?>">

                    <select name="status">
                      <option value="New" <?php if ($row["Status"] === "New") echo "selected"; ?>>New</option>
                      <option value="Current" <?php if ($row["Status"] === "Current") echo "selected"; ?>>Current</option>
                      <option value="Final" <?php if ($row["Status"] === "Final") echo "selected"; ?>>Final</option>
                    </select>
                </td>

                <td>
                    <button type="submit" name="update_status">Update</button>
                  </form>
                </td>

                <td>
                  <form method="post" action="manage.php" onsubmit="return confirm('Are you sure you want to delete this application permanently?');">
                    <input type="hidden" name="eoi_number" value="<?php echo htmlspecialchars($row["EOInumber"]); ?>">
                    <button type="submit" name="delete_eoi">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </main>

    <footer>
      <p>&copy; 2026 G06 Creative Digital Media Agency</p>
    </footer>
  </body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>