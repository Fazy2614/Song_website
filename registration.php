<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>
    <form action="index.php?p=submit" method="post" name="registrationForm">
    <div class="login-formm" style="padding:50px;">
        <span class="login-fm hed">
            Register
        </span>
        <span class="login-fm">
            <label class="login_label" for="">Name</label>
            <input type="text" name="name" id="name" placeholder="Please enter your name">
        </span>
        <span class="login-fm">
            <label class="login_label" for="">Email Id</label>
            <input type="text" name="email" id="email" placeholder="Please enter your email id">
        </span>
        <div style="clear:both;"></div>
        <span class="login-fm">
            <label class="login_label" for="">Password</label>
            <input type="text" name="userPassword" id="userPassword" placeholder="Please enter a password">
        </span>
        <div style="clear:both;"></div>
        <span class="login-f2">
            <input type="hidden" name="mode" id="mode" >
            <a onclick="validateRegister()">Register</a>
        </span>
        <div style="clear:both;"></div>
        
    </div>
    </form>
</body>
</html>