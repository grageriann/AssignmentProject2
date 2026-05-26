<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "G06 Digital Media Agency"; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body id="<?php echo isset($bodyId) ? $bodyId : ""; ?>">

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

    <?php if (isset($bodyId) && $bodyId === "index-body"): ?>
    <section class="hero-text">
        <img src="LBlogo.png" alt="G06 Creative Digital Media Agency logo" class="company-logo" style="max-width: 140px;">
        <h1 class="welcome-highlight">G06 Creative Digital Media Agency</h1>
        <p class="hero-slogan">Creative ideas. Digital impact. Better user experiences.</p>
        <p>
            G06 Creative Digital Media Agency is a client-focused digital studio specialising
            in web design, branding, and digital content production. We create accessible,
            engaging, and visually strong websites for businesses that want to improve their
            online presence and communicate effectively with their audiences.
        </p>
    </section>
    <?php endif; ?>