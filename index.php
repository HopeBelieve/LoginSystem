<?php
    spl_autoload_register(function ($classRequirement) {
        require_once $classRequirement . ".php";
    });
    $default_security = DefaultSecuritySetting::GetObject();

    require_once "FilteringUtils.php";

    require "ConnectingInfo.php";
    $conn = new pdo_db_connection( $db_host_, $db_name_, $username_, $password_);

    $name = $password = $email = "";
    $nameErr = $emailErr = $passwordErr = "";
    $emailMissingErr = $passwordMissingErr = "";
    $successfullyRegistration = true;
    $salt = '$6$rounds=5000$';

    if($_SERVER["REQUEST_METHOD"] == "POST" ){
        if($_POST["action"] == "Register"){
            if(empty($_POST["name"])){
                $nameErr = "Name is required";
            }else{
                $name = filterInput($_POST["name"]);
                if(!preg_match($nameRegex, $name)){
                    $nameErr = "Only letters and white space allowed";
                    $name = "";
                    $successfullyRegistration = false;
                }
            }

            if(empty($_POST["email"])){
                $emailErr = "Email is required";
            }else{
                $email = filterInput($_POST["email"]);
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $emailErr = "Error in email format";
                    $email = "";
                    $successfullyRegistration = false;
                }
            }
            if (empty($_POST["password"])) {
                $passwordErr = "Password is required";
            }
            else{
                $password = filterInput($_POST["password"]);
                if(!preg_match($passwordRegex, $password)) {
                    $passwordErr = "Only letters and white space allowed";
                    $password = "";
                    $successfullyRegistration = false;
                }else{
                    #$password = password_verify($password, PASS);
                    $password = crypt($password, $salt);
                    #$password = password_verify($password, PASSWORD_BCRYPT);
                    #$password = hash('sha1', $password, true);;
                }
            }
            if($successfullyRegistration){
                $conn->RegisterUser($name, $email, $password);
            }
        }
        elseif ($_POST["action"] == "Login"){
            $successfullyLogin = true;
            $email = filterInput($_POST["email"]);
            $password = filterInput($_POST["password"]);
            if(empty($name)){
                $emailMissingErr = "Name is required";
            }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $emailMissingErr = "Error in email format";
                $successfullyLogin = false;
            }
            if(empty($password)){
                $passwordMissingErr = "Password is required";
            }elseif (!preg_match($passwordRegex, $password)) {
                $passwordMissingErr = "Only letters and white space allowed";
                $successfullyLogin = false;
            }
            if($successfullyLogin){
                $password = crypt($password, $salt);
                $user = $conn->LoginUser($email, $password);
                if(!empty($user)){
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["name"] = $user["name"];
                    header("Location: Logged.php");
                }else{
                    echo "User does not exist";
                    echo "<br>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .fixed-width {
            display: inline-block;
            width: 100px; /* Set the desired width */
            padding: 10px; /* Optional: adds space inside the span */
            /*border: 1px solid #ddd; /* Optional: adds a border for better visibility */
            text-align: center; /* Optional: centers text inside the span */
            box-sizing: border-box; /* Ensures padding and border are included in the width */
            text-align: left;
        }

        td {
            padding: 10px; /* Add padding inside cells */
            vertical-align: top; /* Align content to the top */
        }
        .error{
            color: red;
        }

    </style>
</head>
<body>

<table>
    <tr>
        <td style="text-align: center">Register user</td>
        <td style="text-align: center">Login user</td>
    </tr>
    <tr>
        <td>
            <div>
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
                    <span class = "fixed-width"> Name: </span> <input type="text" name="name"> <span class="error">* <?php echo $nameErr;?></span> <br>
                    <span class = "fixed-width"> Email: </span> <input type="text" name="email"> <span class="error">* <?php echo $emailErr;?></span> <br>
                    <span class = "fixed-width"> Password: </span> <input type="password" name="password"> <span class="error">* <?php echo $passwordErr;?></span> <br>
                    <div><input name="action" value="Register" type="submit" style="width: 100%; height: 100%; box-sizing: border-box;"></div>
                </form>
            </div>
        </td>
        <td>
            <div>
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                    <span class = "fixed-width"> Email: </span> <input type="text" name="email"> <span class="error">* <?php echo $emailMissingErr;?></span> <br>
                    <span class = "fixed-width"> Password: </span> <input type="password" name="password"> <span class="error">* <?php echo $passwordMissingErr;?></span> <br>
                    <div><input name="action" value="Login" type="submit" style="width: 100%; height: 100%; box-sizing: border-box;"></div>
                </form>
            </div>
        </td>
    </tr>

</table>

</body>
</html>


<?php
    /*
    echo $name;
    echo "<br>";
    echo $email;
    echo "<br>";
    echo $password;
    echo "<br>";
*/
?>






