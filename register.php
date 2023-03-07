<!-- Student Name :       David Williams -->
<!-- Student Id Number :  C00263768 -->
<!-- Date :               07/03/2023 -->
<!-- Purpose :  Register for the website-->
<?php
	header("X-Frame-Options: DENY");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>Heath Service Centre | Register</title>
        <link href="register.css" rel="stylesheet" />
    </head>  
    <body>
        <?php 
            require_once 'connector.php';
        ?>
        
        <?php
            if(isset($_POST['register']))
            {
                $username = trim($_POST['username']); 
                $password = trim($_POST['password']);
                $confirmPassword = trim($_POST['confirmPassword']);
                $fullName  = trim($_POST['fullName']);
                $studentID = trim($_POST['studentID']);
                $email = trim($_POST['email']);
                $dateOfBirth = trim($_POST['dateOfBirth']);
                $phoneNumber = trim($_POST['phoneNumber']);
                //img 
                $img = file_get_contents($_FILES['img']['tmp_name']);
                $nextOfKin = trim($_POST['nextOfKin']);
                $doctorInfo = trim($_POST['doctorInfo']);
                $medicalConditions = trim($_POST['medicalConditions']);
                $medicalDeclaration = trim($_POST['medicalDeclaration']);
                
                $error['username'] = validateUsername($username); 
                $error['password'] = validatePassword ($password, $confirmPassword); 
                $error['fullName'] = validateFullName($fullName); 
                $error['studentID'] = validateStudentID($studentID); 
                $error['email'] = validateEmail($email); 
                $error['dateOfBirth'] = validateDateOfBirth($dateOfBirth); 
                $error['phoneNumber'] = validatePhoneNumber($phoneNumber); 
                $error['nextOfKin'] = validateNextOfKin($nextOfKin);
                $error['doctorInfo'] = validateDoctorInfo($doctorInfo);
                $error['medicalConditions'] = validateMedicalConditions($medicalConditions);
                $error['medicalDeclaration'] = validateMedicalDeclaration($medicalDeclaration);
                $error = array_filter($error); 
                
                $cipher = 'AES-128-CBC';
                $key = 'ThisIsASecretKey1928374650';

                // iv_hex
                $iv = random_bytes(16);
                $iv_hex = bin2hex($iv); 

                // hashedPassword 
                $hashedPassword = hash('sha3-256', $password, true);
                // hashedPassword_hex 
                $hashedPassword_hex = bin2hex($hashedPassword);

                // encryptedFullName
                $encryptedFullName = openssl_encrypt($fullName, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedFullName_hex
                $encryptedFullName_hex = bin2hex($encryptedFullName);

                // hashedStudentID
                $encrypted_studentID = openssl_encrypt($studentID, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedStudentID_hex
                $encryptedStudentID_hex = bin2hex($encrypted_studentID);

                // hashedEmail
                $encrypted_email = openssl_encrypt($email, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedEmail_hex
                $encryptedEmail_hex = bin2hex($encrypted_email);

                // encryptedDateOfBirth
                $encryptedDateOfBirth = openssl_encrypt($dateOfBirth, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedDateOfBirth_hex
                $encryptedDateOfBirth_hex = bin2hex($encryptedDateOfBirth);

                // encryptedPhoneNumber
                $encryptedPhoneNumber = openssl_encrypt($phoneNumber, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedPhoneNumber_hex
                $encryptedPhoneNumber_hex = bin2hex($encryptedPhoneNumber);

                // encryptedImg
                $encrypted_img = openssl_encrypt($img, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedImg_hex
                $encryptedImg_hex = bin2hex($encrypted_img);

                // encryptedNextOfKin
                $encryptedNextOfKin = openssl_encrypt($nextOfKin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedNextOfKin_hex
                $encryptedNextOfKin_hex = bin2hex($encryptedNextOfKin);

                // encryptedDoctorInfo
                $encryptedDoctorInfo = openssl_encrypt($doctorInfo, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedDoctorInfo_hex
                $encryptedDoctorInfo_hex = bin2hex($encryptedDoctorInfo);

                // encryptedMedicalConditions
                $encryptedMedicalConditions = openssl_encrypt($medicalConditions, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedMedicalConditions_hex
                $encryptedMedicalConditions_hex = bin2hex($encryptedMedicalConditions);

                // encryptedMedicalDeclaration
                $encryptedMedicalDeclaration = openssl_encrypt($medicalDeclaration, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                // encryptedMedicalDeclaration_hex
                $encryptedMedicalDeclaration_hex = bin2hex($encryptedMedicalDeclaration);

                if(empty($error))
                {
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                    
                    $sql = "INSERT INTO students (id, iv, username, password, fullName, studentID, email, dateOfBirth, phoneNumber, img, nextOfKin, doctorInfo, medicalConditions, medicalDeclaration) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $con -> prepare($sql); 
                    $id = NULL; 
                    
                    $stmt -> bind_param('isssssssssssss', $id, $iv_hex, $username, $hashedPassword_hex, $encryptedFullName_hex, $encrypted_studentID, $encrypted_email, $encryptedDateOfBirth_hex, $encryptedPhoneNumber_hex, $encryptedImg_hex, $encryptedNextOfKin, $encryptedDoctorInfo, $encryptedMedicalConditions, $encryptedMedicalDeclaration); 
                    
                    $stmt -> execute(); 
                    if($stmt -> affected_rows > 0)
                    {
                        printf('<script>alert("Register successfully"); location.href = "./login.php"</script>');
                    }
                    
                    $stmt->close();
                    $con->close();
                }
                else
                {
                   //display error msg 
                   echo "<ul class=‘error’>";
                   foreach ($error as $value)
                   {
                   echo "<li>$value</li>";
                   echo "</ul>";
                   }
                }
            }
        ?>
        <div class="content">
        <form class="user" action="" method="post" enctype='multipart/form-data'>
    
        <div class="container">
            <h1>Register</h1>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group <?php echo isset($error['username']) ? 'has-error' : '';?>">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($username) ? $username : '';?>" />
                    <?php if(isset($error['username'])) {?>
                        <span class="help-block"><?php echo $error['username'];?></span>
                    <?php } ?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['password']) ? 'has-error' : '';?>">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" value="" />
                    <?php if(isset($error['password'])) {?>
                        <span class="help-block"><?php echo $error['password'];?></span>
                    <?php } ?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['confirmPassword']) ? 'has-error' : '';?>">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" value="" />
                    <?php if(isset($error['confirmPassword'])) {?>
                        <span class="help-block"><?php echo $error['confirmPassword'];?></span>
                    <?php } ?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['fullName']) ? 'has-error' : '';?>">
                    <label for="fullName">Full Name</label>
                    <input type="text" name="fullName" id="fullName" class="form-control" value="<?php echo isset($fullName) ? $fullName : '';?>" />
                    <?php if(isset($error['fullName'])) {?>
                        <span class="help-block"><?php echo $error['fullName'];?></span>
                    <?php } ?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['studentID']) ? 'has-error' : '';?>">
                    <label for="studentID">Student ID</label>
                    <input type="text" name="studentID" id="studentID" class="form-control" value="<?php echo isset($studentID) ? $studentID : '';?>" />
                    <?php if(isset($error['studentID'])) {?>
                        <span class="help-block"><?php echo $error['studentID'];?></span>
                    <?php } ?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['email']) ? 'has-error' : '';?>">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($email) ? $email : '';?>" />
                    <?php if(isset($error['email'])) {?>
                        <span class="help-block"><?php echo $error['email'];?></span>
                    <?php } ?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['dateOfBirth']) ? 'has-error' : '';?>">
                    <label for="dateOfBirth">Date of Birth</label>
                    <input type="date" name="dateOfBirth" id="dateOfBirth" class="form-control" value="<?php echo isset($dateOfBirth) ? $dateOfBirth : '';?>">
                    <?php if(isset($error['dateOfBirth'])) {?>
                    <span class="help-block"><?php echo $error['dateOfBirth'];?></span>
                    <?php }?>
                </div>
                <br>
                    <div class="form-group <?php echo isset($error['phoneNumber']) ? 'has-error' : '';?>">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="<?php echo isset($phoneNumber) ? $phoneNumber : '';?>">
                    <?php if(isset($error['phoneNumber'])) {?>
                    <span class="help-block"><?php echo $error['phoneNumber'];?></span>
                    <?php }?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['img']) ? 'has-error' : '';?>">
                    <label for="img">Profile Image</label>
                    <input type="file" name="img" id="img" class="form-control">
                    <?php if(isset($error['img'])) {?>
                    <span class="help-block"><?php echo $error['img'];?></span>
                    <?php }?>
                </div>
                <br>
                <h1>Medical Declaration details</h1>
                <div class="form-group <?php echo isset($error['medicalDeclaration']) ? 'has-error' : '';?>">
                    <label for="medicalDeclaration">Medical Declaration</label>
                    <textarea name="medicalDeclaration" id="medicalDeclaration" class="form-control"><?php echo isset($medicalDeclaration) ? $medicalDeclaration : 'I have no medical conditions';?></textarea>
                    <?php if(isset($error['medicalDeclaration'])) {?>
                    <span class="help-block"><?php echo $error['medicalDeclaration'];?></span>
                    <?php }?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['medicalConditions']) ? 'has-error' : '';?>">
                    <label for="medicalConditions">Medical Conditions</label>
                    <textarea name="medicalConditions" id="medicalConditions" class="form-control"><?php echo isset($medicalConditions) ? $medicalConditions : '';?></textarea>
                    <?php if(isset($error['medicalConditions'])) {?>
                    <span class="help-block"><?php echo $error['medicalConditions'];?></span>
                    <?php }?>
                </div>
                <br>
                <div class="form-group <?php echo isset($error['doctorInfo']) ? 'has-error' : '';?>">
                    <label for="doctorInfo">Doctor Information</label>
                    <textarea name="doctorInfo" id="doctorInfo" class="form-control"><?php echo isset($doctorInfo) ? $doctorInfo : '';?></textarea>
                    <?php if(isset($error['doctorInfo'])) {?>
                    <span class="help-block"><?php echo $error['doctorInfo'];?></span>
                    <?php }?>
                </div>
                <br>
                <h3>Next Of kin contact details</h3>
                <div class="form-group <?php echo isset($error['nextOfKin']) ? 'has-error' : '';?>">
                    <label for="nextOfKin">Next of Kin Contact Information</label>
                    <textarea name="nextOfKin" id="nextOfKin" class="form-control"><?php echo isset($nextOfKin) ? $nextOfKin : '';?></textarea>
                    <?php if(isset($error['nextOfKin'])) {?>
                    <span class="help-block"><?php echo $error['nextOfKin'];?></span>
                    <?php }?>
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" name="register" class="btn btn-primary" value="Register">
                    <input type="reset" name="reset"  class="btn btn-primary" value="Reset">
                </div>
            </form>
        </div>
    </body>
</html>