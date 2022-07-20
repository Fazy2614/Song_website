<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index.php?p=submit" method="post" name="loginForm">
    <div class="login-formm" style="padding:50px;">
        <span class="login-fm hed">
            Login to access your Account
        </span>
        <span class="login-fm">
            <label class="login_label" for="">Email Id</label>
            <input type="text" name="userName" id="userName" placeholder="Please enter Email Id">
        </span>
        <div style="clear:both;"></div>
        <span class="login-fm">
            <label class="login_label" for="">Password</label>
            <input type="text" name="password" id="password" placeholder="Please enter password">
        </span>
        <div style="clear:both;"></div>
        <span class="login-f2">
            <input type="hidden" name="mode" id="mode">
            <a onclick="validateLogin()">Login</a>
        </span>
        <div style="clear:both;"></div>
        <span class="lst-lnn">
            <b><a class="rgistrlnk_txt" href="index.php?p=registration">Register</a></b> if you don't have an account.
        </span>
    </div>
    </form>
</body>
</html>