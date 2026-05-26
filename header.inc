<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Recruitment website for G06 Creative Digital Media Agency">
    <meta name="keywords" content="jobs, recruitment, web developer, designer, digital media">
    <meta name="author" content="Jack Stremski & Liam White">
    <title><?php echo isset($pageTitle) ? $pageTitle : "G06 Creative Digital Media Agency"; ?></title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body id="<?php echo isset($bodyId) ? $bodyId : ""; ?>">

    <header>
        <div class="hero-text">
            <img src="images/LBlogo.png" alt="G06 Creative Logo" class="company-logo">
            <h1>G06 Creative</h1>
            <p class="hero-slogan">Innovate. Design. Deliver.</p>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="jobs.php">Jobs</a></li>
            <li><a href="apply.php">Apply</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="manage.php">Manager Portal</a></li>
        </ul>
    </nav>