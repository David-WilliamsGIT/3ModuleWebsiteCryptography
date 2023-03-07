<!-- Student Name :       David Williams -->
<!-- Student Id Number :  C00263768 -->
<!-- Date :               07/03/2023 -->
<!-- Purpose :  Connector for all the pages to connect to the database-->
<?php
	header("X-Frame-Options: DENY");
?>
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'clubsAndSocieties');

$host = 'localhost';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password);

$sql = "CREATE DATABASE IF NOT EXISTS clubsAndSocieties;";
if ($conn->query($sql) === TRUE) {
  $conn->select_db("clubsAndSocieties");

  $sql = "CREATE TABLE IF NOT EXISTS students (
    id int NOT NULL AUTO_INCREMENT,
    iv varchar(32) NOT NULL,
    username varchar(256) NOT NULL,
    password mediumtext NOT NULL,
    fullName varchar(256) NOT NULL,
    studentID varchar(256) NOT NULL,
    email varchar(256) NOT NULL, 
    dateOfBirth varchar(256) NOT NULL, 
    phoneNumber varchar(256) NOT NULL,
    img MEDIUMTEXT NOT NULL,
    doctorInfo varchar(256) NOT NULL,
    nextOfKin varchar(256) NOT NULL,
    medicalConditions varchar(256) NOT NULL,
    medicalDeclaration varchar(256) NOT NULL,
    PRIMARY KEY (id));";
  if (!$conn->query($sql) === TRUE) {
    die("Error creating table: " . $conn->error);
  }
} else {
  die("Error creating database: " . $conn->error);
}

    // function to sanitize user input
function sanitizeInput($input) {
  if (is_array($input)) {
    foreach($input as $var=>$val) {
      $output[$var] = sanitizeInput($val); 
    }
  }
  else {
    $output = trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
  }
  return $output;
}

// username
function validateUsername($username) {
  // check if exists in db
  $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $sql = "SELECT username FROM students";
  $result = $con->query($sql);
  return "";
}

// password
function validatePassword($password, $confirmPassword) {
  // check if both are the same
  if ($password != $confirmPassword) {
    return "Both passwords are not the same.";
  }
}

// full name
function validateFullName($fullName) {
  // check to make sure only lowercase, uppercase, single quotes and spaces
  if (!preg_match("/^[a-zA-Z0-9' \-\.,]+$/", $fullName)) {
    return "The full name should only contain alphabets, spaces, single quotes, hyphens, periods, and commas.";
  }
}

// student ID
function validateStudentID($studentID) {
  // check to make sure only alphanumeric characters are present
    if (!preg_match("/^[a-zA-Z0-9]+$/", $studentID)) {
      return "The student ID should only contain alphanumeric characters.";
    }
  }
  
  // email
  function validateEmail($email) {
  // check to make sure the email is valid using PHP's built-in email validation function
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return "Invalid email format.";
    }
  }

// date of birth
function validateDateOfBirth($dateOfBirth) {
    // check if the date is valid and in the format of yyyy-mm-dd
    $dateOfBirthObj = DateTime::createFromFormat('Y-m-d', $dateOfBirth);
    if (!$dateOfBirthObj || $dateOfBirthObj->format('Y-m-d') !== $dateOfBirth) {
      return 'Please enter a valid date of birth in the format of yyyy-mm-dd';
    }
  }

// phone number
function validatePhoneNumber($phoneNumber) {
  // check to make sure only 10 digits with 0 at first
  if (!preg_match("/^[0][0-9]{9}$/", $phoneNumber)) {
    return "Phone number should only contain 10 digits with 0 as the first digit";
  }
}

function validateMedicalDeclaration($medicalDeclaration) {
    if(empty($medicalDeclaration)) {
    return "Please enter your medical declaration.";
    } 
    else 
    {
        return "";
    }
}
    
    // Validate Medical Conditions  
function validateMedicalConditions($medicalConditions) {
    if(empty($medicalConditions)) {
        return "Please enter your medical conditions.";
    } 
    else 
    {
        return "";
    }
}
    
    
    // Validate Doctor Information
function validateDoctorInfo($doctorInfo) {
    if(empty($doctorInfo)) {
    return "Please enter your doctor information.";
    } 
    else 
    {
        return "";
    }
}
    
    // Validate Next of Kin
function validateNextOfKin($nextOfKin) {
    if(empty($nextOfKin)) {
    return "Please enter your next of kin contact information.";
    } 
    else 
    {
        return "";
    }
}
?>