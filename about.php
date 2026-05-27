<?php
$pageTitle = "About | G06 Agency";
$bodyId = "about-body";

include_once("header.inc");
require_once("settings.php");

// FIX: Select the exact column names defined in your database.sql file
$query = "SELECT name, student_id, snack, part1_contrib, part2_contrib FROM about ORDER BY id ASC";
$result = mysqli_query($conn, $query);
?>

<main class="page-container">
    <section>
        <h2 style="font-size: 1.5rem">Acknowledgement of Country</h2>
        <p>
            G06 Creative Digital Media Agency acknowledges the Traditional
            Custodians of the lands where we live and work. We are committed to
            fostering an inclusive creative industry and strongly encourage
            applications from Aboriginal and Torres Strait Islander peoples.
        </p>
    </section>

    <section>
        <h2>Agency Information</h2>
        <ul>
            <li><strong>Class Group:</strong> G06 Digital Media Studio</li>
            <li><strong>Course Code:</strong> COS10026 Web Development</li>
        </ul>
    </section>

    <section>
        <h2>Our Timetable</h2>
        <p>We work collaboratively during our allocated on-campus laboratory sessions:</p>
        <dl>
            <dt><strong>Team Motto:</strong></dt>
            <dd>
                "Chi va piano, va sano e va lontano" (He who goes softly goes safely
                and far)
            </dd>
        </dl>
    </section>

    <section>
        <h2>Team Photo</h2>
        <figure class="team-border">
            <img src="images/group-photo.jpg" alt="G06 Creative Team" width="300">
            <figcaption style="font-style: italic; margin-top: 5px; color: #555;">G06 Partners: Liam and Jack.</figcaption>
        </figure>
    </section>

    <section>
        <h2>Agency Facts</h2>
        <table class="about-table">
            <caption>
                Team Credentials & Contributions
            </caption>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Design Snack</th>
                    <th>Part 1 Contribution</th>
                    <th>Part 2 Contribution</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Loop through each database record and render it safely into rows
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td class='id-style'>" . htmlspecialchars($row['student_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['snack']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['part1_contrib']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['part2_contrib']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>No team records found in the database.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>

<?php 
// Close resource footprints cleanly
if ($result && is_object($result)) {
    mysqli_free_result($result);
}
mysqli_close($conn);

include_once("footer.inc"); 
?>