<?php 
$pageTitle = "Home | G06 Digital Media Agency";
$bodyId = "index-body";
include_once("header.inc"); 
?>

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
    <form method="get" action="jobs.php">
      <input type="text" name="search" placeholder="Enter job title, keywords or reference code...">
      <button type="submit">Search</button>
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

<?php include_once("footer.inc"); ?>