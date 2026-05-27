<?php
//   validation that the form was submitted via POST method,
//   otherwise redirect back to the application form.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit;
}

require_once "settings.php";

// Auto-create EOI table if missing on clean environment
$tableCheckQuery = "CREATE TABLE IF NOT EXISTS eoi (
  EOInumber INT AUTO_INCREMENT PRIMARY KEY,
  JobReferenceNumber VARCHAR(5) NOT NULL,
  FirstName VARCHAR(20) NOT NULL,
  LastName VARCHAR(20) NOT NULL,
  DOB VARCHAR(10) NOT NULL,
  Gender VARCHAR(20) NOT NULL,
  StreetAddress VARCHAR(40) NOT NULL,
  SuburbTown VARCHAR(40) NOT NULL,
  State VARCHAR(3) NOT NULL,
  Postcode CHAR(4) NOT NULL,
  EmailAddress VARCHAR(100) NOT NULL,
  PhoneNumber VARCHAR(12) NOT NULL,
  Skills TEXT,
  OtherSkills TEXT,
  Status ENUM('New', 'Current', 'Final') DEFAULT 'New',
  DateSubmitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $tableCheckQuery);

// sanitization to clear the user input of malicious code.
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

$job_reference  = clean_input($_POST["job_ref"] ?? "");
$first_name     = clean_input($_POST["first_name"] ?? "");
$last_name      = clean_input($_POST["last_name"] ?? "");
$dob            = clean_input($_POST["dob"] ?? "");
$gender         = clean_input($_POST["gender"] ?? "");
$street_address = clean_input($_POST["street"] ?? "");
$suburb         = clean_input($_POST["suburb"] ?? "");
$state          = clean_input($_POST["state"] ?? "");
$postcode       = clean_input($_POST["postcode"] ?? "");
$email          = clean_input($_POST["email"] ?? "");
$phone          = clean_input($_POST["phone"] ?? "");
$other_skills   = clean_input($_POST["other_skills"] ?? "");

$skills = "";
if (isset($_POST["skills"]) && is_array($_POST["skills"])) {
    $cleaned_skills = array_map('clean_input', $_POST["skills"]);
    $skills = implode(", ", $cleaned_skills);
}

$errors = [];

// Validation Logic Rules
if (!preg_match("/^[A-Za-z0-9]{5}$/", $job_reference)) {
    $errors[] = "Job reference identifier must span exactly 5 alphanumeric characters.";
}
if (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
    $errors[] = "First name must contain only alpha characters and not exceed 20 characters.";
}
if (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
    $errors[] = "Last name must contain only alpha characters and not exceed 20 characters.";
}

// Date of Birth Validation
if (!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $dob)) {
    $errors[] = "Date of Birth format must match the DD/MM/YYYY structure perfectly.";
} else {
    $parts = explode('/', $dob);
    $day = (int)$parts[0];
    $month = (int)$parts[1];
    $year = (int)$parts[2];
    if (!checkdate($month, $day, $year)) {
        $errors[] = "The Date of Birth provided is an invalid calendar configuration.";
    } else {
        $birthDate = DateTime::createFromFormat('d/m/Y', $dob);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        if ($age < 15 || $age > 80) {
            $errors[] = "Applicant age must fall between 15 and 80 years old.";
        }
    }
}

if (empty($gender)) {
    $errors[] = "Gender identification field is required.";
}
if (strlen($street_address) < 1 || strlen($street_address) > 40) {
    $errors[] = "Street location must be between 1 and 40 characters.";
}
if (strlen($suburb) < 1 || strlen($suburb) > 40) {
    $errors[] = "Suburb must be between 1 and 40 characters.";
}

$valid_states = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
if (!in_array($state, $valid_states)) {
    $errors[] = "Please specify a valid Australian state territory.";
}

// Nested Postcode Validation to avoid out-of-bounds index warnings
if (!preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode configurations must consist of exactly 4 numeric characters.";
} else {
    $first_digit = $postcode[0];
    if ($state === "VIC" && $first_digit !== '3' && $first_digit !== '8') $errors[] = "VIC postcodes must start with 3 or 8.";
    if ($state === "NSW" && $first_digit !== '1' && $first_digit !== '2') $errors[] = "NSW postcodes must start with 1 or 2.";
    if ($state === "QLD" && $first_digit !== '4' && $first_digit !== '9') $errors[] = "QLD postcodes must start with 4 or 9.";
    if ($state === "NT"  && $first_digit !== '0') $errors[] = "NT postcodes must start with 0.";
    if ($state === "WA"  && $first_digit !== '6') $errors[] = "WA postcodes must start with 6.";
    if ($state === "SA"  && $first_digit !== '5') $errors[] = "SA postcodes must start with 5.";
    if ($state === "TAS" && $first_digit !== '7') $errors[] = "TAS postcodes must start with 7.";
    if ($state === "ACT" && $first_digit !== '0') $errors[] = "ACT postcodes must start with 0.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "The email layout provided does not conform to specifications.";
}
if (!preg_match("/^[0-9 ]{8,12}$/", $phone)) {
    $errors[] = "Phone values must contain between 8 and 12 digits.";
}

$success = false;
$eoi_number = null;

// Database processing
if (empty($errors)) {
    $query = "INSERT INTO eoi (JobReferenceNumber, FirstName, LastName, DOB, Gender, StreetAddress, SuburbTown, State, Postcode, EmailAddress, PhoneNumber, Skills, OtherSkills, Status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')";
    
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "sssssssssssss", $job_reference, $first_name, $last_name, $dob, $gender, $street_address, $suburb, $state, $postcode, $email, $phone, $skills, $other_skills);
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
            $eoi_number = mysqli_insert_id($conn);
        } else {
            $errors[] = "Database execution failed. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $errors[] = "Database preparation failed. Please check backend settings.";
    }
}
mysqli_close($conn);

// Set structural template variables used by header.inc
$pageTitle = "Application Status | G06 Agency";
$bodyId = "status-page"; // Fixes undefined variable error in template layout

include_once("header.inc");
?>

<main class="page-container">
    <div style="max-width:600px; margin:40px auto; padding:30px; border:1px solid #ccc; border-radius:8px; text-align:center; background:var(--white);">
        <?php if ($success): ?>
            <h1 style="color: #1a73e8; margin-bottom: 15px;">Application Received Successfully</h1>
            <p>Thank you for applying to join the team at G06 Creative Digital Media Agency.</p>
            <div style="background: var(--hover-blue); padding:15px; margin:20px 0; border-radius:6px; font-size:1.3rem; border: 1px solid #b3d7ff; font-weight: bold; color: var(--primary-blue);">
                Your Tracking EOI Number is: #<?php echo htmlspecialchars($eoi_number); ?>
            </div>
            <p>Please save this identification reference safely for your evaluation updates.</p>
            <p style="margin-top:25px;"><a href="index.php" style="display:inline-block; padding:10px 20px; background:var(--primary-blue); color:var(--white); text-decoration:none; border-radius:4px; font-weight: bold;">Return to Home Screen</a></p>
        <?php else: ?>
            <h1 style="color:#d32f2f; margin-bottom: 15px;">Application Submission Error</h1>
            <p>We found the following configuration errors in your form submission:</p>
            <ul style="text-align: left; color: #d32f2f; margin: 20px 0; padding-left: 20px; line-height: 1.8;">
                <?php 
                foreach ($errors as $error) {
                    echo "<li>" . htmlspecialchars($error) . "</li>";
                }
                ?>
            </ul>
            <p style="margin-top:25px;"><a href="apply.php" style="display:inline-block; padding:10px 20px; background:var(--dark-grey); color:var(--white); text-decoration:none; border-radius:4px; font-weight: bold;">Return to Application Form</a></p>
        <?php endif; ?>
    </div>
</main>

<?php include_once("footer.inc"); ?>