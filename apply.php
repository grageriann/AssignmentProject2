<?php 
$pageTitle = "Join the Team | G06 Agency";
include_once("header.inc"); 

$passed_ref = isset($_GET["job_ref"]) ? htmlspecialchars(trim($_GET["job_ref"])) : "";
?>

<main>
  <h1 style="text-align: center">Application for Creative Roles</h1>

  <form action="process_eoi.php" method="post" novalidate>
    <fieldset>
      <legend>Position Details</legend>
      <label for="job_ref">Job Reference Number:</label>
      <input type="text" id="job_ref" name="job_ref" value="<?php echo $passed_ref; ?>" required pattern="[A-Za-z0-9]{5}" maxlength="5">
    </fieldset>

    <fieldset>
      <legend>Personal Details</legend>
      
      <label for="first_name">First Name:</label>
      <input type="text" id="first_name" name="first_name" required pattern="[A-Za-z]{1,20}" maxlength="20">

      <label for="last_name">Last Name:</label>
      <input type="text" id="last_name" name="last_name" required pattern="[A-Za-z]{1,20}" maxlength="20">

      <label for="dob">Date of Birth (DD/MM/YYYY):</label>
      <input type="text" id="dob" name="dob" required pattern="\d{2}/\d{2}/\d{4}" placeholder="dd/mm/yyyy">

      <p>Gender:</p>
      <label><input type="radio" name="gender" value="Male" required> Male</label>
      <label><input type="radio" name="gender" value="Female"> Female</label>
      <label><input type="radio" name="gender" value="Other"> Other</label>
    </fieldset>

    <fieldset>
      <legend>Address Details</legend>

      <label for="street">Street Address:</label>
      <input type="text" id="street" name="street" required maxlength="40">

      <label for="suburb">Suburb/Town:</label>
      <input type="text" id="suburb" name="suburb" required maxlength="40">

      <label for="state">State:</label>
      <select id="state" name="state" required>
        <option value="">-- Select State --</option>
        <option value="VIC">VIC</option>
        <option value="NSW">NSW</option>
        <option value="QLD">QLD</option>
        <option value="NT">NT</option>
        <option value="WA">WA</option>
        <option value="SA">SA</option>
        <option value="TAS">TAS</option>
        <option value="ACT">ACT</option>
      </select>

      <label for="postcode">Postcode:</label>
      <input type="text" id="postcode" name="postcode" required pattern="\d{4}" maxlength="4">
    </fieldset>

    <fieldset>
      <legend>Contact Information</legend>

      <label for="email">Email Address:</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Phone Number:</label>
      <input type="text" id="phone" name="phone" required pattern="[0-9 ]{8,12}" maxlength="12">
    </fieldset>

    <fieldset>
      <legend>Technical Expertise</legend>
      <p>Select your skills:</p>
      <label><input type="checkbox" name="skills[]" value="HTML"> HTML/CSS</label>
      <label><input type="checkbox" name="skills[]" value="JS"> JavaScript</label>
      <label><input type="checkbox" name="skills[]" value="UX"> UI/UX Design</label>

      <label for="other_skills">Other Skills (Software, Portfolio links):</label>
      <textarea id="other_skills" name="other_skills" rows="4"></textarea>
    </fieldset>

    <input type="submit" value="Submit Application" style="background: #1a73e8; color: white; padding: 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 20px;">
  </form>
</main>

<?php 
include_once("footer.inc"); 
?>