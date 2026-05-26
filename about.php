<?php
$pageTitle = "About Us | G06 Creative Agency";
require_once("settings.php");

// 1. SELF-HEALING: Auto-create the 'about' table if it doesn't exist yet
$tableCheckQuery = "CREATE TABLE IF NOT EXISTS about (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  student_id VARCHAR(15) NOT NULL,
  snack VARCHAR(100) NOT NULL,
  part1_contrib TEXT NOT NULL,
  part2_contrib TEXT NOT NULL
)";
mysqli_query($conn, $tableCheckQuery);

// 2. SELF-HEALING: Insert default team records if the table is completely empty
$checkEmpty = mysqli_query($conn, "SELECT COUNT(*) AS total FROM about");
$rowEmpty = mysqli_fetch_assoc($checkEmpty);
if ($rowEmpty['total'] == 0) {
    $insertQuery = "INSERT INTO about (name, student_id, snack, part1_contrib, part2_contrib) VALUES
    ('Jack', '106501279', 'Cold Brew Coffee', 'Developed static HTML structures and configured CSS variables formatting.', 'Constructed application endpoint processing scripts and SQL schemas.'),
    ('Liam', '106512828', 'Raspberry White Chocolates', 'Designed responsive grid patterns and user interaction pathways.', 'Created administrative control panels and user management gates.')";
    mysqli_query($conn, $insertQuery);
}

// 3. Now run the clean fetch query safely
$query = "SELECT * FROM about ORDER BY id ASC";
$result = mysqli_query($conn, $query);

include_once("header.inc");
?>

<main class="page-container">
  <section class="acknowledgement">
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
      <figcaption style="font-style: italic; margin-top: 10px;">G06 Partners: Liam and Jack.</figcaption>
    </figure>
    <blockquote style="background: #f8f9fa; border-left: 5px solid #1a73e8; padding: 15px; margin: 20px 0;">
      "Chi va piano, va sano e va lontano" (He who goes softly goes safely and far)
    </blockquote>
  </section>

  <section>
    <h2>Agency Facts & Contributions</h2>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <caption>Team Credentials and Project Assignments</caption>
      <thead>
        <tr style="background-color: #1a73e8; color: white;">
          <th style="padding: 12px; text-align: left;">Name</th>
          <th style="padding: 12px; text-align: left;">Student ID</th>
          <th style="padding: 12px; text-align: left;">Design Snack</th>
          <th style="padding: 12px; text-align: left;">Part 1 Tasks</th>
          <th style="padding: 12px; text-align: left;">Part 2 Tasks</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr style="border-bottom: 1px solid #ddd;">
              <td style="padding: 12px; font-weight: bold;"><?php echo htmlspecialchars($row['name']); ?></td>
              <td style="padding: 12px;" class="id-style"><?php echo htmlspecialchars($row['student_id']); ?></td>
              <td style="padding: 12px; font-style: italic;"><?php echo htmlspecialchars($row['snack']); ?></td>
              <td style="padding: 12px; font-size: 0.9rem;"><?php echo htmlspecialchars($row['part1_contrib']); ?></td>
              <td style="padding: 12px; font-size: 0.9rem;"><?php echo htmlspecialchars($row['part2_contrib']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" style="padding: 12px; text-align: center;">No member contribution data available.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>

<?php include_once("footer.inc"); ?>