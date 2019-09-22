<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <title>Register Team Action</title>
    <meta name="viewport" content="width=device-width">
    <style>
        * {
            -moz-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
        }
        body {
            background: tomato;
            padding: 40px;
        }
        .main-content {
            width: 400px;
            height: auto;
            margin: auto;
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        .login-container {
            border-bottom: 1px tomato solid;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            padding: 15px;
            font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #000000;
        }
        .user-container {
            border-bottom: 1px tomato solid;
            padding: 15px;
        }
        .submit-container {
            padding: 15px;
        }
        form {
            width: 100%;
            height: auto;
            font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .input-format,
        button {
            width: 100%;
            padding: 10px;
            display: block;
            font-size: 15px;
            margin: 20px auto;
            font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        
        .input-format button{
            background: #fff;
            color: black;
        }
        
        select {
            color: #8a97a0;
            margin-top: 10px;
        }
        input::placeholder {
            color: #8a97a0;
        }
        button {
            border-radius: 25px;
            text-align: center;
            color: #fff;
            background: tomato;
            border: none;
            font-size: 30px;
            font-weight: bold;
            transition: 0.3s;
             box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        
        button:hover{
            background: #296E01;
            color: white;
             box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 4), 0 6px 20px 0 rgba(0, 0, 0, 0.4);
            
        }
        
        label {
            font-weight: normal;
            font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 18px;
            color: #000000;
            font-weight: 400;
        }
        input[type="checkbox"]:focus+label {
            font-weight: bold;
        }
        a {
            text-decoration: none;
        }
        a:link {}
        p {
            font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #8a97a0;
            font-size: 1em;
            text-align: center;
        }
        a:link {
            text-decoration: none;
            color: #8a97a0;
        }
        a:visited {
            text-decoration: none;
            color: #8a97a0;
        }
        .register-form input[type=text],
        .register-form input[type=date],
        .register-form input[type=datetime],
        .register-form input[type=number],
        .register-form input[type=search],
        .register-form input[type=time],
        .register-form input[type=url],
        .register-form input[type=email],
        .register-form input[type=password] {
            background: transparent;
            border: none;
            border-bottom: 1px solid #000000;
            outline: none;
        }
        .register-form select {
            line-height: 1.3;
            outline: none;
            border-radius: 25px;
            width: 100%;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .register-form input[type=text]:focus,
        .register-form input[type=date]:focus,
        .register-form input[type=datetime]:focus,
        .register-form input[type=number]:focus,
        .register-form input[type=search]:focus,
        .register-form input[type=time]:focus,
        .register-form input[type=url]:focus,
        .register-form input[type=email]:focus,
        .register-form input[type=password]:focus {
            border: none;
            border-bottom: 2px solid #7d4a6c;
     }
    </style>
</head>

<body>
    <!--form Begin -->
        <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>

</html>