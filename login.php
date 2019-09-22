<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Team-Action</title>
    <link href="https:fonts.googleapis.com/css?family=Poppins:300.400.500&display=swap" rel="stylesheet">
    <style>
        
        body{
    font-family:Arial, Helvetica, sans-serif;
    margin-top:100px; 
    background-image:url("https://res.cloudinary.com/fizy2019/image/upload/v1568708926/IMG-20190917-WA0003_atn9f6.jpg");
    background-repeat:no-repeat;
    background-attachment: fixed;
    background-size:cover;
}
.wrapper{
    display:flex;
    justify-content:center; 
    align-items:center;
}
.header1{
    font-size:70px;
    text-align:center;
    color:white;
    font-family:"Verdana" Lucida Console,sans-serif;
}
.header2{
    color:white;
    font-size:40px;  
}
.text{
    color:white;
    font-stretch:20px; 
}
.flex1{
    flex:0.4;
}
.flex1{
    text-align:center;
    letter-spacing:2px;
}
.flex2{
    flex:0.2;
}
.flex2{
    border-style:solid;
    border-color:white;
    padding-top:10px;
    padding-left:10px;
    padding-right:10px;  
    border-radius:4px;
    text-align:center;
}
input[type=email]{
    width:300px;
    padding:12px 20px;
    margin:30px 0;
    display:inline-block;
    border:1px solid #ccc;
    border-radius:4px;
    box-sizing:border-box;
}
input[ type=password]{
    width:300px;
    padding:12px 20px;
    margin:30px 0;
    display:inline-block;
    border:1px solid #ccc;
    border-radius:4px;
    box-sizing:border-box;
    
}
input[type=submit]{
    width:300px;
    background-color:tomato;
    color:white;
    padding:12px 19px;
    margin:30px 0;
    border:none;
    border-radius:4px;
    cursor:pointer;
}
.remember-me{
    color:white;
}
    </style>
</head>
<body>
    <main>
        <div class="wrapper">
            <!--text starts here-->
            <div class="flex1">
                <header class="header1">Welcome Back</header>
                <p class="text">Team Action is building a community of</p>
                    <p class="text">world class developers one code at a time</p>
                    <br>
                    <br>
                    <br>
                <p class="text">Learn more about #Team_Action/> </p>
            </div>
            <!--form starts here-->
                <div class="flex2">
                    <header class="header2">Account Login</header>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                 <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>

                    </form>
                    </div>
                </div>
        </div>
    </main>
    
</body>
</html>