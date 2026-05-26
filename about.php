<?php
$pageTitle = "About Us | G06 Creative Agency";
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
      h2 {
        color: #1a73e8;
        border-bottom: 2px solid #eee;
        padding-bottom: 5px;
      }
    </style>
  </head>

  <body>
    <header>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="jobs.php">Jobs</a></li>
          <li><a href="apply.php">Apply</a></li>
          <li><a href="about.php">About</a></li>
        </ul>
      </nav>
    </header>

    <main>
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
          <li>Group Name: G06 – Creative Digital Media Agency</li>
          <li>Specialization: Web Design, Branding, and Digital Content</li>
          <li>
            Course Info:
            <ul>
              <li>Wednesday 2:30 PM Class</li>
            </ul>
          </li>
        </ul>
      </section>

      <section>
        <h2>The Creative Team</h2>
        <dl>
          <dt><strong>Jack Stremski</strong></dt>
          <dd>Front-end Lead: Engineered the Home and Jobs architecture.</dd>
          <dd>
            "El diseño no solo viste la idea le da vida en su manera de funcionar."
            (Design does not just dress an idea it gives it life in the way it works.)
          </dd>

          <dt><strong>Liam White</strong></dt>
          <dd>UX Designer: Built the Apply and About interfaces.</dd>
          <dd>
            "Chi va piano, va sano e va lontano" (He who goes softly goes safely
            and far)
          </dd>
        </dl>
      </section>

      <section>
        <h2>Team Photo</h2>
        <figure class="team-border">
          <img src="group-photo.jpg" alt="G06 Creative Team" width="300">
          <figcaption>G06 Partners: Liam and Jack.</figcaption>
        </figure>
      </section>

      <section>
        <h2>Agency Facts</h2>
        <table>
          <caption>
            Team Credentials
          </caption>
          <thead>
            <tr>
              <th>Name</th>
              <th>ID</th>
              <th>Design Snack</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Jack</td>
              <td class="id-style">106501279</td>
              <td>Cold Brew Coffee</td>
            </tr>
            <tr>
              <td>Liam</td>
              <td class="id-style">106512828</td>
              <td>Raspberry White Chocolates</td>
            </tr>
          </tbody>
        </table>
      </section>
    </main>

    <footer>
      <p>
        &copy; 2026 G06 Creative Agency |
        <a href="https://student-team-fnet57yj.atlassian.net/jira/software/projects/KAN/boards/2?jql=">Jira</a> |
        <a href="https://github.com/grageriann/Group-Project-Assignment">GitHub</a> |
        <a href="mailto:info@g06agency.com">info@g06agency.com</a>
      </p>
    </footer>
  </body>
</html>