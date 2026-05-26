<?php 
$pageTitle = "Home | G06 Digital Media Agency";
include_once("header.inc"); 
?>

<div id="index-body">
    <section class="hero-text">
      <img
        src="images/LBlogo.png"
        alt="G06 Creative Digital Media Agency logo"
        class="company-logo"
        style="max-width: 140px;"
      >
      <h1 class="welcome-highlight">G06 Creative Digital Media Agency</h1>
      <p class="hero-slogan">Creative ideas. Digital impact. Better user experiences.</p>
      <p>
        G06 Creative Digital Media Agency is a client-focused digital studio specialising
        in web design, branding, and digital content production. We create accessible,
        engaging, and visually strong websites for businesses that want to improve their
        online presence and communicate effectively with their audiences.
      </p>
    </section>

    <main class="page-container">
      <section class="acknowledgement">
        <h2>Acknowledgement of Country</h2>
        <p>
          G06 Creative Digital Media Agency acknowledges the Traditional Custodians of the
          lands on which we work and create. We pay our respects to Elders past and present,
          and we value inclusive employment practices that encourage applications from
          Aboriginal and Torres Strait Islander peoples.
        </p>
      </section>

      <section class="search-section">
        <h2>Search Careers Information</h2>
        <form action="jobs.php" method="get" class="search-form">
          <label for="job-search">Find a role:</label>
          <select id="job-search" name="job-search">
            <option value="">Select a job</option>
            <option value="front-end-developer">Front-End Developer</option>
            <option value="web-designer">Web Designer</option>
          </select>
          <button type="submit">Go</button>
        </form>
      </section>

      <section class="overview">
        <h2>Why Join Our Agency?</h2>
        <p>
          We are expanding our creative and technical team to deliver high-quality client
          websites, brand experiences, and digital content. Our agency values collaboration,
          accessibility, innovation, and user-focused design.
        </p>

        <table>
          <caption>Agency Services and Career Opportunities</caption>
          <thead>
            <tr>
              <th rowspan="2">Area</th>
              <th colspan="2">Focus</th>
            </tr>
            <tr>
              <th>Client Impact</th>
              <th>Career Opportunities</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Web Design</td>
              <td>Builds modern, accessible, and responsive websites</td>
              <td>Front-End Developer, Web Designer</td>
            </tr>
            <tr>
              <td>Branding</td>
              <td>Strengthens visual identity and client recognition</td>
              <td>Brand Designer, Content Designer</td>
            </tr>
            <tr>
              <td>Digital Content</td>
              <td>Supports audience engagement across platforms</td>
              <td>Digital Content Creator, UX Writer</td>
            </tr>
          </tbody>
        </table>
      </section>
    </main>
</div>

<?php include_once("footer.inc"); ?>