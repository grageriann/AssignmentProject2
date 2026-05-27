<?php
$pageTitle = "About | G06 Agency";
$bodyId = "about-body";

include_once("header.inc");
require_once("settings.php");
?>

<style>
    main h2 {
        color: #1a73e8;
        border-bottom: 2px solid #eee;
        padding-bottom: 5px;
        margin-top: 25px;
    }
    .about-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0 30px 0;
    }
    .about-table caption {
        font-weight: bold;
        text-align: left;
        margin-bottom: 8px;
        color: var(--text-color);
    }
    .about-table th {
        background-color: var(--light-grey);
        color: var(--text-color);
        font-weight: bold;
        text-align: left;
        padding: 12px;
        border: 1px solid #ddd;
    }
    .about-table td {
        padding: 12px;
        border: 1px solid #ddd;
        color: var(--text-color);
    }
    .id-style {
        font-family: monospace;
        font-size: 1.05rem;
    }
    .team-border img {
        border: 1px solid #ccc;
        padding: 4px;
        background: #fff;
    }
</style>

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

<?php 
include_once("footer.inc"); 
?>