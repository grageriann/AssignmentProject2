<?php
$pageTitle = "Careers | Open Positions";
require_once("settings.php");

// Self-healing check: Ensure the jobs table is created and populated for the marker
$tableQuery = "CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_number CHAR(5) NOT NULL UNIQUE,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    salary VARCHAR(100) NOT NULL,
    reports_to VARCHAR(100) NOT NULL
)";
mysqli_query($conn, $tableQuery);

$checkEmpty = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs");
$rowEmpty = mysqli_fetch_assoc($checkEmpty);
if ($rowEmpty['total'] == 0) {
    mysqli_query($conn, "INSERT INTO jobs (reference_number, title, description, salary, reports_to) VALUES
    ('FE123', 'Front-End Developer', 'We are looking for a Front-End Developer to build responsive and accessible client websites. This role focuses on translating visual concepts into functional webpages using HTML5 and CSS3.', '$68,000 – $80,000 per year', 'Lead Developer'),
    ('WD245', 'Web Designer', 'We are seeking a creative Web Designer to produce visually engaging and client-focused website designs. This role involves layout planning, visual styling, and contributing to brand identity across digital platforms.', '$65,000 – $75,000 per year', 'Creative Director')");
}

// Handle search bar values securely
$search_query = isset($_GET['search']) ? trim($_GET['search']) : "";

if ($search_query !== "") {
    $sql = "SELECT * FROM jobs WHERE reference_number LIKE ? OR title LIKE ? ORDER BY id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    $like_param = "%" . $search_query . "%";
    mysqli_stmt_bind_param($stmt, "ss", $like_param, $like_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT * FROM jobs ORDER BY id ASC";
    $result = mysqli_query($conn, $sql);
}

include_once("header.inc");
?>

<main class="page-container">
    <h2>Open Positions</h2>
    <p>Explore our career paths and find where you fit best. Use the search tool to quickly filter positions by title or job reference identifier code.</p>

    <div style="background-color: var(--light-grey); padding: 15px; border-radius: 8px; margin-bottom: 25px; clear: both;">
        <form method="get" action="jobs.php" style="display: flex; gap: 10px; max-width: 100%; margin: 0; padding: 0; background: transparent; box-shadow: none; border: none;">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by job code or position title (e.g., FE123, Designer)..." style="flex: 1; margin: 0;">
            <button type="submit" style="padding: 10px 20px; background-color: var(--primary-blue); color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; width: auto;">Search Jobs</button>
            <?php if ($search_query !== ""): ?>
                <a href="jobs.php" style="padding: 10px 15px; background-color: var(--dark-grey); color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.9rem; line-height: 1.6;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div style="display: block; width: 100%; clear: both; overflow: hidden;">
        
        <div style="width: 70%; float: left; box-sizing: border-box; padding-right: 20px;">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($job = mysqli_fetch_assoc($result)): ?>
                    
                    <section class="job-card" style="margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 20px; clear: both; float: none; width: 100%;">
                        <h3 style="color: var(--primary-blue); margin-bottom: 5px; font-size: 1.4rem; border: none; padding: 0;"><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p style="font-weight: bold; color: var(--text-color); margin: 0 0 10px 0;">
                            Reference Code: <span style="color: var(--primary-blue);"><?php echo htmlspecialchars($job['reference_number']); ?></span>
                        </p>
                        
                        <h4>Position Overview</h4>
                        <p><?php echo htmlspecialchars($job['description']); ?></p>
                        
                        <h4>Key Position Metrics</h4>
                        <ul>
                            <li><strong>Salary Range:</strong> <?php echo htmlspecialchars($job['salary']); ?></li>
                            <li><strong>Reports To:</strong> <?php echo htmlspecialchars($job['reports_to']); ?></li>
                        </ul>
                        
                        <p style="margin-top: 15px;">
                            <a href="apply.php?job_ref=<?php echo urlencode($job['reference_number']); ?>" style="display: inline-block; padding: 8px 16px; background-color: var(--primary-blue); color: var(--white); text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">Apply For This Position</a>
                        </p>
                    </section>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="font-weight: bold; color: #d32f2f; margin-top: 20px;">No open positions match your search term. Please try another query or view all jobs.</p>
            <?php endif; ?>
        </div>

        <aside>
            <h3>General Requirements</h3>
            <p>All candidates applying for our engineering or design positions are expected to meet these general standards:</p>
            <ul>
                <li>Excellent verbal and written communication skills.</li>
                <li>Strong problem-solving capability and attention to detail.</li>
                <li>Experience working effectively within collaborative team environments.</li>
                <li>A portfolio or GitHub repository demonstrating relevant project work.</li>
            </ul>
        </aside>

    </div> </main>

<?php 
if (isset($stmt)) { mysqli_stmt_close($stmt); }
mysqli_close($conn);
include_once("footer.inc"); 
?>