<!-- Student Name :       David Williams -->
<!-- Student Id Number :  C00263768 -->
<!-- Date :               07/03/2023 -->
<!-- Purpose :  userPage for the website-->
<?php
	header("X-Frame-Options: DENY");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>SETU Clubs & Societies | Student Account</title>
        <link href="userPage.css" rel="stylesheet" />
    </head>  
    <body>
        <?php 
            require_once 'connector.php';
        ?>
        
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'GET')
            {
                if(!empty($_GET['username']))
                {
                    $cipher = 'AES-128-CBC';
                    $key = 'Th1s1sAS3cr37K3y';
            
                    //retrieve username from URL
                    $username = trim($_GET['username']); 
            
                    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
                    $sql = "SELECT * FROM students WHERE username = ?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
            
                    if ($row = $result->fetch_assoc()) {
                        // Convert the IV from hexadecimal to binary format
                        $iv = hex2bin($row['iv']);      
                                    
                        $fullName_hex = $row['fullName'];
                        $fullName_bin = hex2bin($fullName_hex);
                        $fullName = openssl_decrypt($fullName_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            
                        $studentID_hex = $row['studentID'];
                        if (ctype_xdigit($studentID_hex)) {
                            $studentID_bin = hex2bin($studentID_hex);
                            $studentID = openssl_decrypt($studentID_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                        } else {
                            $studentID = "";
                        }
                                               
                        $email_hex = $row['email'];
                        if (ctype_xdigit($email_hex)) {
                            if (strlen($email_hex) % 2 != 0) {
                                $email_hex = "0" . $email_hex;
                            }
                            $email_bin = hex2bin($email_hex);
                            $email = openssl_decrypt($email_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                        } else {
                            $email = "";
                            // or handle the error in some other way
                        }
                        
                        $dateOfBirth_hex = $row['dateOfBirth'];
                        $dateOfBirth_bin = hex2bin($dateOfBirth_hex);
                        $dateOfBirth = openssl_decrypt($dateOfBirth_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            
                        $phoneNumber_hex = $row['phoneNumber'];
                        $phoneNumber_bin = hex2bin($phoneNumber_hex);
                        $phoneNumber = openssl_decrypt($phoneNumber_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            
                        $img_hex = $row['img'];
                        $img_bin = hex2bin($img_hex);
                        $img = openssl_decrypt($img_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            
                        $nextOfKin_hex = $row['nextOfKin'];
                        if (ctype_xdigit($nextOfKin_hex)) {
                            $nextOfKin_bin = hex2bin($nextOfKin_hex);
                            $nextOfKin = openssl_decrypt($nextOfKin_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                        } else {
                            // handle the case when $nextOfKin_hex is not a valid hexadecimal string
                        }                  

                        $doctorInfo_hex = $row['doctorInfo'];
                        if (strlen($doctorInfo_hex) % 2 != 0) {
                            $doctorInfo_hex = "0" . $doctorInfo_hex;
                        }
                        if (ctype_xdigit($doctorInfo_hex)) {
                            $doctorInfo_bin = hex2bin($doctorInfo_hex);
                            $doctorInfo = openssl_decrypt($doctorInfo_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                        } else {
                            $doctorInfo = "";
                        }

                        $medicalConditions_hex = $row['medicalConditions'];
                        if (strlen($medicalConditions_hex) % 2 != 0) {
                            $medicalConditions_hex = "0" . $medicalConditions_hex;
                        }
                        if (ctype_xdigit($medicalConditions_hex)) {
                            $medicalConditions_bin = hex2bin($medicalConditions_hex);
                            $medicalConditions = openssl_decrypt($medicalConditions_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                        } else {
                            $medicalConditions = "";
                        }

                        $medicalDeclaration_hex = $row['medicalDeclaration'];
                        if (strlen($medicalDeclaration_hex) % 2 != 0) {
                            $medicalDeclaration_hex = "0" . $medicalDeclaration_hex;
                        }
                        if (ctype_xdigit($medicalDeclaration_hex)) {
                            $medicalDeclaration_bin = hex2bin($medicalDeclaration_hex);
                            $medicalDeclaration = openssl_decrypt($medicalDeclaration_bin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                        } else {
                            $medicalDeclaration = "Invalid hexadecimal string";
                        }

                    }
                     $result -> free();
                     $con -> close();
                }
                else 
                {
                    $location = "login.php"; 
                    echo "<script type='text/javascript'>alert('Please login to view citizen details');window.location='$location'</script>";
                }

            }
            
            else
            { 
                $username = trim($_POST['username']); 
                $fullName  = trim($_POST['fullName']);
                $studentID = trim($_POST['studentID']);
                $email = isset($email) ? $email : '';
                $dateOfBirth = trim($_POST['dateOfBirth']);
                $phoneNumber = trim($_POST['phoneNumber']);
                //img 
                $img = file_get_contents($_FILES['img']['tmp_name']);
                $nextOfKin = trim($_POST['nextOfKin']);
                $doctorInfo = trim($_POST['doctorInfo']);
                $medicalConditions = trim($_POST['medicalConditions']);
                $medicalDeclaration = trim($_POST['medicalDeclaration']);
                
                $error['username'] = validateUsername($username); 
                $error['fullName'] = validateFullName($fullName); 
                $error['studentID'] = validateFullName($studentID); 
                $error['email'] = validateFullName($email); 
                $error['dateOfBirth'] = validateDateOfBirth($dateOfBirth); 
                $error['phoneNumber'] = validatePhoneNumber($phoneNumber); 
                $error['nextOfKin'] = validateNextOfKin($nextOfKin);
                $error['doctorInfo'] = validateDoctorInfo($doctorInfo);
                $error['medicalConditions'] = validateMedicalConditions($medicalConditions);
                $error['medicalDeclaration'] = validateMedicalDeclaration($medicalDeclaration); 
                $error = array_filter($error); 
                
                $cipher = 'AES-128-CBC';
                $key = 'ThisIsASecretKey1928374650';
                
                //iv_hex
                $iv = random_bytes(16);
                $iv_hex = bin2hex($iv); 

                //hashedPassword 
                $hashedPassword = hash('sha3-256', $password, true);
                //hashedPassword_hex 
                $hashedPassword_hex = bin2hex($hashedPassword);
                
                //encryptedFullName
                $encryptedFullName = openssl_encrypt($fullName, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                //encryptedFullName_hex
                $encryptedFullName_hex = bin2hex($encryptedFullName);
                
                //hashedStudentID
                $encrypted_studentID = openssl_encrypt($studentID, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                //encryptedStudentID_hex
                $encryptedStudentID_hex = bin2hex($encrypted_studentID);

                //hashedEmail
                if (isset($_POST['email'])) {
                    $encrypted_email = openssl_encrypt(trim($_POST['email']), $cipher, $key, OPENSSL_RAW_DATA, $iv);
                    //encryptedEmail_hex
                    $encryptedEmail_hex = bin2hex($encrypted_email);
                
                    $email = trim($_POST['email']);
                } else {
                    // handle the case when the email field is not set
                }                

                //encryptedDateOfBirth
                $encryptedDateOfBirth = openssl_encrypt($dateOfBirth, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                //encryptedDateOfBirth_hex
                $encryptedDateOfBirth_hex = bin2hex($encryptedDateOfBirth);
                
                //encryptedPhoneNumber
                $encryptedPhoneNumber = openssl_encrypt($phoneNumber, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                //encryptedPhoneNumber_hex
                $encryptedPhoneNumber_hex = bin2hex($encryptedPhoneNumber);
                
                //encryptedImg
                $encrypted_img = openssl_encrypt($img, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                //encryptedImg_hex
                $encryptedImg_hex = bin2hex($encrypted_img);

                //encryptedNextOfKin
                $encryptedNextOfKin = openssl_encrypt($nextOfKin, $cipher, $key, OPENSSL_RAW_DATA, $iv);

                //encryptedDoctorInfo
                $encryptedDoctorInfo = openssl_encrypt($doctorInfo, $cipher, $key, OPENSSL_RAW_DATA, $iv);

                //encryptedMedicalConditions
                $encryptedMedicalConditions = openssl_encrypt($medicalConditions, $cipher, $key, OPENSSL_RAW_DATA, $iv);

                //encryptedMedicalDeclaration
                $encryptedMedicalDeclaration = openssl_encrypt($medicalDeclaration, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                
                
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
                   echo "<ul class='error'>";
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
        
             
            <div class="bigTextDiv">
                <h1 class="bigText">SETU Clubs & Societies | Student Account</h1>
            </div>
             
            <!-- username --> 
            <label class="txtBox">Username - Cannot be Changed :</label>
            <br>
            <input type="text" class="txtBox" id="username" name="username" value="<?php echo $username?>" readonly/>
              
            <br><br>
            
            <!-- fullName --> 
            <label class="txtBox">Full Name :</label>
            <br>
            <input type="text" class="txtBox" id="fullName" name="fullName" value="<?php echo $fullName?>" required="required"/>
            
            <br><br>

            <!-- studentID -->
            <label class="txtbox">studentID :</label>
            <br>
            <input type="text" class="txtbox" id="studentID" name="studentID" value="<?php echo $studentID?>" required="required"/>

            <br><br>

            <!-- email -->
            <label class="txtbox">email :</label>
            <br>
            <input type="email" class="txtbox" id="email" name="email value="<?php echo $email?>" required="required"/>

            <br><br>
            <!-- dateOfBirth --> 
            <label class="txtBox">Date Of Birth :</label>
            <br>
            <input type="date" class="txtBox" id="dateOfBirth" name="dateOfBirth" value="<?php echo $dateOfBirth?>" required="required"/>
            
            <br><br>
            
            <!-- phoneNumber --> 
            <label class="txtBox">Phone Number :</label>
            <br>
            <input type="text" class="txtBox" id="phoneNumber" name="phoneNumber" value="<?php echo $phoneNumber?>" required="required" maxlength="10"/>
            
            <br><br><br>
            
            <!-- img --> 
            <label class="txtBox">Your student ID image :</label>
            <br>
            <?php $display_img = '<img src="data:image/jpeg;base64,'.base64_encode( $img ).'" width="650px" height="500px"/>'; ?>
            <label><?php echo $display_img?></label>
            <input type="file" class="txtBox" id="img" name="img" accept="image/*" required="required"/>

            <br><br>

            <label class="txtBox">Doctor Info :</label>
            <br>
            <textarea class="txtBox" id="doctorInfo" name="doctorInfo"><?php echo htmlspecialchars($doctorInfo) ? $doctorInfo : ''; ?></textarea>

            <br>

            <label class="txtBox">Medical Conditions :</label>
            <br>
            <textarea class="txtBox" id="medicalConditions" name="medicalConditions"><?php echo htmlspecialchars($medicalConditions); ?></textarea>


            <br><br>

            <label class="txtBox">I declare that the information given above is true and complete :</label>
            <br>
            <input type="checkbox" id="medicalDeclaration" name="medicalDeclaration" <?php if($medicalDeclaration) echo "checked='checked'"; ?> />

            <br><br>


            <label class="txtBox">Next of Kin Full Name :</label>
            <br>
            <input type="text" class="txtBox" id="nextOfKin" name="nextOfKin" value="<?php echo isset($nextOfKin) ? $nextOfKin : '' ?>" />

            <br><br>
            
            
            <input type="submit" class="btn2" id="update" value="Update" name="update" />
            </form>
            
            <br> 
        <a href="home.php" class="txtAtBtm">Home</a>
            <br><br>
        <a href="register.php" class="txtAtBtm">Register</a>
        </div>
        
        
    </body>
</html>