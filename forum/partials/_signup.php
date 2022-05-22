<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $v_code)
{
    require("PHPMailer\PHPMailer.php");
    require("PHPMailer\SMTP.php");
    require("PHPMailer\Exception.php");
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'razzsuwal@gmail.com';                     //SMTP username
        $mail->Password   = '9810026560abc';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('razzsuwal@gmail.com', 'C-sOLUTION');
        $mail->addAddress($email);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email verification from C-sOLUTION';
        $mail->Body    = "Thanks for Registation <br>
        Click the link below to verify the email adress <br>
        <a href ='http://localhost/FORUM/partials/_verify.php?email=$email&v_code=$v_code'> Verifiy </a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
$showAlert = false;
$showError = false;
$showError2 = false;
$showError5 = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    // Check whether this username exists
    $existSql = "SELECT * FROM `user` WHERE email = '$email'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        // $exists = true;
        $showError2 = true;
    } else {
        // $exists = false; 
        if (($password == $cpassword)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $v_code = bin2hex(random_bytes(8));
            $sql = "INSERT INTO `user` ( `email`, `password`, `dt`, `verification_code`, `is_verified`) VALUES ('$email', '$hash', current_timestamp(), '$v_code', '0')";
            $result = mysqli_query($conn, $sql);
            if ($result && sendMail($_POST['email'], $v_code)) {
                $showAlert = true;
            }
            else {
                $showError5 = true;
            }
        } else {
            $showError = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="/FORUM/css/style_form.css">
    <script src="/FORUM/partials/script.js"></script>
</head>

<body>
    <?php include '_header.php'; ?>
    <div id="myform">
        <p class="my_para1"> <b> SIGN UP</b> <br> Register your own account, it's quick and easy. </p>
        <form class="form1" name="register" action="_signup.php" onsubmit="return func()" method="post">
            <div>
                <label for="email">Email:</label>
            </div>
            <input type="email" name="email" id="email" placeholder="Enter valid email">
            <br><br>
            <div>
                <label for="password">Password: </label>
            </div>
            <input type="password" name="password" id="password" placeholder="Enter strong password">
            <br><br>
            <div>
                <label for="cpassword">Confirm Password: </label>
            </div>
            <input type="password" name="cpassword" id="cpassword">
            <div>
                <small>Make sure you have type same password</small>
            </div>
            <br>

            <div>
                <button class="buttom" type="submit">Sign Up</button>
            </div>
            <br>
            <a class="users" href="/FORUM/partials/_login.php">Alerady have account</a>

        </form>
    </div>
    <?php
    if ($showAlert) {
        echo '<script> alert("Form have submitted succesfully , please verify email"); </script';
    }
    if ($showError) {
        echo '<script> alert("password donot match"); </script';
    }
    if ($showError2) {
        echo '<script> alert("username is already taken"); </script';
    }
    if ($showError5) {
        echo '<script> alert("Server Down"); </script';
    }
    ?>
    <?php include "_footer.php"; ?>
</body>

</html>