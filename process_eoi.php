<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit;
}

require_once "settings.php";

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
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

if (!preg_match("/^[A-Za-z0-9]{5}$/", $job_reference)) {
    $errors[] = "Job reference number must be exactly 5 alphanumeric characters.";
}

if (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
    $errors[] = "First name must only contain letters and be 20 characters or less.";
}

if (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
    $errors[] = "Last name must only contain letters and be 20 characters or less.";
}

if (!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $dob)) {
    $errors[] = "Date of birth must use the dd/mm/yyyy format.";
} else {
    $date_parts = explode('/', $dob);
    if (count($date_parts) === 3) {
        if (!checkdate($date_parts[1], $date_parts[0], $date_parts[2])) {
            $errors[] = "The Date of Birth provided is not a valid calendar date.";
        }
    } else {
        $errors[] = "The Date of Birth layout must use the slashes separator format.";
    }
}

if (empty($gender)) {
    $errors[] = "Gender selection is required.";
}

if (empty($street_address) || strlen($street_address) > 40) {
    $errors[] = "Street address is required and must be 40 characters or less.";
}

if (empty($suburb) || strlen($suburb) > 40) {
    $errors[] = "Suburb/Town is required and must be 40 characters or less.";
}

if (!in_array($state, ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"])) {
    $errors[] = "State must be a valid Australian territory option.";
}

if (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} else {
    $first_digit = substr($postcode, 0, 1);
    if ($state === "VIC" && $first_digit !== "3" && $first_digit !== "8") $errors[] = "VIC postcodes must start with 3 or 8.";
    if ($state === "NSW" && $first_digit !== "1" && $first_digit !== "2") $errors[] = "NSW postcodes must start with 1 or 2.";
    if ($state === "QLD" && $first_digit !== "4" && $first_digit !== "9") $errors[] = "QLD postcodes must start with 4 or 9.";
    if ($state === "NT"  && $first_digit !== "0") $errors[] = "NT postcodes must start with 0.";
    if ($state === "WA"  && $first_digit !== "6") $errors[] = "WA postcodes must start with 6.";
    if ($state === "SA"  && $first_digit !== "5") $errors[] = "SA postcodes must start with 5.";
    if ($state === "TAS" && $first_digit !== "7") $errors[] = "TAS postcodes must start with 7.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "The email address layout is invalid.";
}

if (!preg_match("/^[0-9 ]{8,12}$/", $phone)) {
    $errors[] = "Phone number must be between 8 and 12 digits/spaces.";
}

if (count($errors) > 0) {
    $pageTitle = "Validation Error | G06 Agency";
    include_once("header.inc");
    ?>
    <main class="message-card" style="max-width:600px; margin:20px auto; padding:20px; border:1px solid #ccc; background:#fff; font-family:sans-serif;">
        <h1 style="color:#d32f2f;">Application Processing Error</h1>
        <p>Please fix the following validation criteria rule breaks:</p>
        <ul style="color:#d32f2f; font-weight:bold;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
        <p style="margin-top:20px;"><a href="apply.php" style="display:inline-block; padding:10px 15px; background:#1a73e8; color:#fff; text-decoration:none; border-radius:4px;">Return to Application Form</a></p>
    </main>
    <?php
    include_once("footer.inc");
    exit;
}

$query = "INSERT INTO eoi 
(JobReferenceNumber, FirstName, LastName, DOB, Gender, StreetAddress, SuburbTown, State, Postcode, EmailAddress, PhoneNumber, Skills, OtherSkills, Status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param(
    $stmt,
    "sssssssssssss",
    $job_reference,
    $first_name,
    $last_name,
    $dob,
    $gender,
    $street_address,
    $suburb,
    $state,
    $postcode,
    $email,
    $phone,
    $skills,
    $other_skills
);

$success = mysqli_stmt_execute($stmt);
$eoi_number = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);
mysqli_close($conn);

$pageTitle = "Application Success | G06 Agency";
include_once("header.inc");
?>
<main class="message-card" style="max-width:600px; margin:50px auto; padding:30px; border:1px solid #ccc; border-radius:8px; text-align:center; background:#fff; font-family:sans-serif;">
    <?php if ($success): ?>
        <h1 style="color:#2e7d32;">Application Received Successfully</h1>
        <p>Thank you for your interest in joining G06 Creative Digital Media Agency.</p>
        <div style="background:#e8f0fe; padding:15px; margin:20px 0; border-radius:6px; font-size:1.2rem;">
            Your tracking EOI Identification Number is: <strong>#<?php echo htmlspecialchars($eoi_number); ?></strong>
        </div>
        <p>Please note down this index token for your application interview process.</p>
        <p style="margin-top:25px;"><a href="index.php" style="display:inline-block; padding:10px 20px; background:#1a73e8; color:white; text-decoration:none; border-radius:4px;">Return to Home Screen</a></p>
    <?php else: ?>
        <h1 style="color:#d32f2f;">Database Operations Error</h1>
        <p>A structural runtime issue stopped database registration. Please contact support teams.</p>
        <p style="margin-top:25px;"><a href="apply.php" style="display:inline-block; padding:10px 20px; background:#d32f2f; color:white; text-decoration:none; border-radius:4px;">Try Resubmitting</a></p>
    <?php endif; ?>
</main>
<?php 
include_once("footer.inc"); 
?>