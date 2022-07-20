<?php 
$page = $_GET['p'];
$getId = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Songs</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script src="js/validate.js" type="text/javascript"></script>
</head>
<body>
    <?php if($page == "form"){?>
        <li class="nav_li nav_li_active" style="float: right;margin-right:5%;"><a href="index.php?p=home&id=<?php echo $getId;?>">Home</a></li>
    <?php } ?>
    <?php if($page != "home"){ ?>
        <li class="nav_li nav_li_active" style="float: right;margin-right:5%;"><a href="index.php?p=login">Login</a></li>
    <?php }else{ ?>
        <li class="nav_li nav_li_active" style="float: right;margin-right:5%;"><a href="index.php?p=logout">Logout</a></li>
    <?php } ?>
    <li class="nav_li nav_li_active" style="float: right;"><a href="index.php?p=registration">Register</a></li>
    <div style="clear:both;"></div>
</body>
</html>