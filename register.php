<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "social-network");
if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_errno();
}

//Declaring variables to prevent errors
$fname = "";
$lname = "";
$em = "";
$em2 = "";
$password = "";
$password2 = "";
$date = "";
$error_array = array();

if(isset($_POST['register_button'])) {

    // Registration form values
    $fname = strip_tags($_POST['reg_fname']); //remove html tags
    $fname = str_replace(' ', '', $fname);    //remove spaces
    $fname = ucfirst(strtolower($fname));     // Uppercase first letter, lowercase everything else
    $_SESSION['reg_fname'] = $fname; // stores first name into session

    $lname = strip_tags($_POST['reg_lname']); 
    $lname = str_replace(' ', '', $lname);    
    $lname = ucfirst(strtolower($lname));
    $_SESSION['reg_lname'] = $lname; // stores last name into session var

    $em = strip_tags($_POST['reg_email']); 
    $em = str_replace(' ', '', $em);    
    $em = ucfirst(strtolower($em));
    $_SESSION['reg_email'] = $em; // stores email into session var

    $em2 = strip_tags($_POST['reg_email2']); 
    $em2 = str_replace(' ', '', $em2);    
    $em2 = ucfirst(strtolower($em2));
    $_SESSION['reg_email2'] = $em2; // stores email2 into session var

    $password = strip_tags($_POST['reg_password']);
    $_SESSION['reg_password'] = $password; // stores password into session var

    $password2 = strip_tags($_POST['reg_password2']);
    $_SESSION['reg_password2'] = $password2; // stores password into session var

    $date = date("y-m-d"); // current date

    if($em == $em2) {
        //check if email is valid
        if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            //check if email already exists
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

            //count the number of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if($num_rows > 0) {
                array_push($error_array, "Email already in use<br>");
            }
        } else {
            array_push($error_array, "Invalid email format<br>");
        }
    } else {
        array_push($error_array, "Emails don't match<br>");
    }
    


    if(strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
    }
    if(strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
    }
    if($password != $password2) {
        array_push($error_array, "Your passwords do not match<br>");
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Your password can only contain english characters or numbers<br>");
        }
    }
    if(strlen($password > 30) || strlen($password) < 5) {
        array_push($error_array, "Your password must be between 5 and 30 characters<br>");
    }

    if(empty($error_array)) {
        $password = md5($password); // encrypt password before sending to database
        
        // generate username by concatening first and last name
        $username = strtolower($fname . "_". $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        // if username exists add number to username
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        }
    }
}



?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to feedSocial</title>
</head>
<style>
    input {
        display: block;
    }
</style>
<body>
    <form action="register.php" method="POST">
        <input type="text" name="reg_fname" placeholder="First Name" required
        value="<?php if(isset($_SESSION['reg_fname'])) {
        echo $_SESSION['reg_fname'];}?>">
        <?php if(in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>";?>

        <input type="text" name="reg_lname" placeholder="Last Name" required
        value="<?php if(isset($_SESSION['reg_lname'])) {
        echo $_SESSION['reg_lname'];}?>">
        <?php if(in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>";?>

        <input type="email" name="reg_email" placeholder="Email" required
        value="<?php if(isset($_SESSION['reg_email'])) {
        echo $_SESSION['reg_email'];}?>" />
        <?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>";
        else if (in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
        else if (in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>";?>

        <input type="email" name="reg_email2" placeholder="Confirm Email" required
        value="<?php if(isset($_SESSION['reg_email2'])) {
        echo $_SESSION['reg_email2'];}?>" />

        <input type="password" name="reg_password" placeholder="your password" required>
        <?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>";
        else if (in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
        else if (in_array("Your password must be between 5 and 30 characters<br>", $error_array)) echo "Your password must be between 5 and 30 characters<br>";?>

        <input type="password" name="reg_password2" placeholder="Confirm password" required>
        <input type="submit" name="register_button" value="Register">
    </form>
    
</body>
</html>