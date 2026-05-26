<?php
$pageTitle = "Apply | G06 Agency";
$bodyId = "apply-body";

include_once("header.inc");

// Check if a job reference code was passed
$passed_ref = isset($_GET['job_ref']) ? trim($_GET['job_ref']) : "";
?>

<main class="page-container">
    <h2 style="text-align: center;">Expression of Interest (EOI)</h2>
    <p style="text-align: center; max-width: 600px; margin: 0 auto 20px;">Complete the form below to submit your job application. All fields are required unless stated otherwise.</p>
    
    <form action="process_eoi.php" method="post" novalidate="novalidate">
        
        <fieldset>
            <legend>Job Identification</legend>
            <label for="job_ref">Job Reference Number</label>
            <input type="text" id="job_ref" name="job_ref" value="<?php echo htmlspecialchars($passed_ref); ?>" placeholder="5 alphanumeric characters">
        </fieldset>

        <fieldset>
            <legend>Personal Details</legend>
            
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" placeholder="Max 20 alpha characters">
            
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" placeholder="Max 20 alpha characters">
            
            <label for="dob">Date of Birth</label>
            <input type="text" id="dob" name="dob" placeholder="DD/MM/YYYY">
            
            <label>Gender</label>
            <div style="display: flex; gap: 15px; align-items: center;">
                <label style="font-weight: normal;"><input type="radio" name="gender" value="Male"> Male</label>
                <label style="font-weight: normal;"><input type="radio" name="gender" value="Female"> Female</label>
                <label style="font-weight: normal;"><input type="radio" name="gender" value="Unspecified"> Other</label>
            </div>
        </fieldset>

        <fieldset>
            <legend>Address Details</legend>
            
            <label for="street">Street Address</label>
            <input type="text" id="street" name="street" placeholder="Max 40 characters">
            
            <label for="suburb">Suburb / Town</label>
            <input type="text" id="suburb" name="suburb" placeholder="Max 40 characters">
            
            <label for="state">State</label>
            <select id="state" name="state">
                <option value="">-- Select Your State --</option>
                <option value="VIC">VIC</option>
                <option value="NSW">NSW</option>
                <option value="QLD">QLD</option>
                <option value="NT">NT</option>
                <option value="WA">WA</option>
                <option value="SA">SA</option>
                <option value="TAS">TAS</option>
                <option value="ACT">ACT</option>
            </select>
            
            <label for="postcode">Postcode</label>
            <input type="text" id="postcode" name="postcode" placeholder="4 digits exact">
        </fieldset>

        <fieldset>
            <legend>Contact Information</legend>
            
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="example@domain.com">
            
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="8 to 12 numeric digits">
        </fieldset>

        <fieldset>
            <legend>Skills Framework</legend>
            
            <label>Technical Proficiencies</label>
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label style="font-weight: normal;"><input type="checkbox" name="skills[]" value="HTML5/CSS3"> HTML5 & CSS3 Interface Architectures</label>
                <label style="font-weight: normal;"><input type="checkbox" name="skills[]" value="PHP/MySQL"> PHP Application Development & MySQL Integration</label>
                <label style="font-weight: normal;"><input type="checkbox" name="skills[]" value="UI/UX-Design"> Visual Interface Blueprinting & Layout Design</label>
                <label style="font-weight: normal;"><input type="checkbox" name="skills[]" value="Git/GitHub"> Managed Version Control Tracking (Git / GitHub)</label>
            </div>
            
            <label for="other_skills">Other Skills (Optional)</label>
            <textarea id="other_skills" name="other_skills" rows="5" placeholder="Outline additional technical tools or background information here..."></textarea>
        </fieldset>

        <input type="submit" value="Submit Expression of Interest">
    </form>
</main>

<?php include_once("footer.inc"); ?>