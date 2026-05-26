<?php
$pageTitle = "About Us | G06 Creative Agency";
require_once("settings.php");

// Self-healing check: Ensure table exists and has your team rows
$tableCheckQuery = "CREATE TABLE IF NOT EXISTS about (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  student_id VARCHAR(15) NOT NULL,
  snack VARCHAR(100) NOT NULL,
  part1_contrib TEXT NOT NULL,
  part2_contrib TEXT NOT NULL
)";
mysqli_query($conn, $tableCheckQuery);

$checkEmpty = mysqli_query($conn, "SELECT COUNT(*) AS total FROM about");
$rowEmpty = mysqli_fetch_assoc($checkEmpty);
if ($rowEmpty['total'] == 0) {
    $insertQuery = "INSERT INTO about (name, student_id, snack, part1_contrib, part2_contrib) VALUES
    ('Jack', '106501279', 'Cold Brew Coffee', 'Developed static HTML structures and configured CSS variables formatting.', 'Constructed application endpoint processing scripts and SQL schemas.'),
    ('Liam', '106512828', 'Raspberry White Chocolates', 'Designed responsive grid patterns and user interaction pathways.', 'Created administrative control panels and user management gates.')";
    mysqli_query($conn, $insertQuery);
}

$query = "SELECT * FROM about ORDER BY id ASC";
$result = mysqli_query($conn, $query);

include_once("header.inc");
?>

<main class="page-container">
  <section>
    <h2>Acknowledgement of Country</h2>
    <p>
      G06 Creative Digital Media Agency acknowledges the Traditional Custodians of the lands where we live and work. 
      We are committed to fostering an inclusive creative industry and strongly encourage applications from Aboriginal and Torres Strait Islander peoples.
    </p>
  </section>

  <section>
    <h2>Team Photo & Motto</h2>
    <figure class="team-border" style="text-align: center; margin: 20px 0;">
      <img src="images/group-photo.jpg" alt="G06 Creative Team" style="max-width:300px; border-radius:8px;">
      <figcaption>G06 Partners: Liam and Jack.</figcaption>
    </figure>
    <p style="font-style: italic; text-align: center; margin: 15px 0; color: var(--text-color);">
      "Chi va piano, va sano e va lontano" (He who goes softly goes safely and far)
    </p>
  </section>

  <section>
    <h2>Agency Facts & Contributions</h2>
    <table>
      <caption>Team Credentials and Project Assignments</caption>
      <thead>
        <tr>
          <th>Name</th>
          <th>Student ID</th>
          <th>Design Snack</th>
          <th>Part 1 Tasks</th>
          <th>Part 2 Tasks</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
              <td class="id-style"><?php echo htmlspecialchars($row['student_id']); ?></td>
              <td style="font-style: italic;"><?php echo htmlspecialchars($row['snack']); ?></td>
              <td><?php echo htmlspecialchars($row['part1_contrib']); ?></td>
              <td><?php echo htmlspecialchars($row['part2_contrib']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" style="text-align: center;">No member contribution data available.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>

<?php include_once("footer.inc"); ?>