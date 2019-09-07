<?php
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
$error_array = "";

if(isset($_POST['register_button'])) {

    // Registration form values
    $fname = strip_tags($_POST['reg_fname']); //remove html tags
    $fname = str_replace(' ', '', $fname);    //remove spaces
    $fname = ucfirst(strtolower($fname));     // Uppercase first letter, lowercase everything else

    $lname = strip_tags($_POST['reg_lname']); 
    $lname = str_replace(' ', '', $lname);    
    $lname = ucfirst(strtolower($lname));     

    $em = strip_tags($_POST['reg_email']); 
    $em = str_replace(' ', '', $em);    
    $em = ucfirst(strtolower($em));     

    $em2 = strip_tags($_POST['reg_email2']); 
    $em2 = str_replace(' ', '', $em2);    
    $em2 = ucfirst(strtolower($em2));   

    $password = strip_tags($_POST['reg_password']);
    $password2 = strip_tags($_POST['reg_password2']);

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
                echo "Email already in use";
            }
        } else {
        echo " Emails don't match";
        }

        if(strlen($fname) > 25 || strlen($fname) < 2) {
            echo "Your first name must be betwee 2 and 25 characters";
        }
        if(strlen($lname) > 25 || strlen($lname) < 2) {
            echo "Your last name must be betwee 2 and 25 characters";
        }
        if($password != $password2) {
            echo "Your passwords do not match";
        } else {
            if (preg_match('/[^A-Za-z0-9]/', subject))
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
        <input type="text" name="reg_fname" placeholder="First Name" required>
        <input type="text" name="reg_lname" placeholder="Last Name" required>
        <input type="email" name="reg_email" placeholder="Email" required>
        <input type="email" name="reg_email2" placeholder="Confirm Email" required>
        <input type="password" name="reg_password" placeholder="your password" required>
        <input type="password" name="reg_password2" placeholder="Confirm password" required>
        <input type="submit" name="register_button" value="Register">
    </form>
    
</body>
</html>