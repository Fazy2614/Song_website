<?php 
$getId = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add artist</title>
</head>
<body>
    <form action="index.php?p=submit" name="addArtistForm" method="post">
    <div class="innr-cnnt innr_pad" id="div1">
    <span class="login-fm hed">
            Add Artist
        </span>
        <div class="pdng-inns exp_widthexp">
            <label for="name">Artist Name<span style="color: red;" >*</span></label>
            <input type="text" name="artistName" id="artistName">
        </div>
        <div class="pdng-inns exp_widthexp">
            <label for="name">Date Of Birth<span style="color: red;" >*</span></label>
            <input type="date" name="dob" id="dob">
        </div>
        <div class="pdng-inns exp_widthexp">
            <label for="name">Song<span style="color: red;" >*</span></label>
            <input type="text" name="song" id="song">
        </div>
        <div style="margin-left:50%;">
            <a onclick="validateArtist()" class="btn-cnt">Save</a>
        </div>
        <input type="hidden" name="mode" id="mode">
        <input type="hidden" name="id" id="id" value="<?php echo $userId;?>">
    </div>
    </form>
</body>
</html>