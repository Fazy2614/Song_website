<?php
// include("config/Database.php");
$obk= new func();
$getArtist = $obk->getArtist();
$getId = $_GET['id'];
// print_r($getArtist);die;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="index.php?p=submit" name="songForm" method="POST" enctype='multipart/form-data'>
    <div class="innr-cnnt innr_pad" id="div1">
        <span class="login-fm hed">
            Add Song
        </span>
        <div class="pdng-inns exp_widthexp">
            <label for="name">Song Name<span style="color: red;" >*</span></label>
            <input type="text" name="songName" id="songName">
        </div>
        <div class="pdng-inns exp_widthexp">
            <label for="name">Artist<span style="color: red;" >*</span></label>
            <select name="artist" id="artist">
                <option value="0">Select Artist</option>
                <?php while($fetch = mysqli_fetch_object($getArtist)){ ?>
                    <option value="<?php echo $fetch->ARTIST_ID ?>"><?php echo $fetch->ARTIST_NAME;?></option>
                <?php } ?>
            </select>
            
                <a class="btn-cnt" style="margin-right: 30%;" href="index.php?p=addArtist&id=<?php echo $getId;?>">Add Artist</a>
        </div>
        <div class="pdng-inns exp_widthexp">
            <label for="">Upload Artwork<span style="color: red;" >*</span></label>
            <input type="file" name="artwork">
        </div>
        <div class="pdng-inns exp_widthexp">
            <label for="rating">Rating<span style="color: red;" >*</span></label>
            <table>
                <tr>
                    
                    <?php for($i = 1;$i < 6;$i++){ ?>
                    <td>
                        <img id="img<?php echo $i;?>" src="images/unselected_star.png" alt="" style="width: 20px;" onclick="selectRating('<?php echo $i;?>')">  
                        <input type="hidden" name="rating" id="rating" value="">                      
                    </td>    
                    <?php } ?>
                </tr>
            </table>
        </div>
        <input type="hidden" name="id" id="id" value="<?php echo $getId; ?>">
        <input type="hidden" name="finalRating" id="finalRating">
        <input type="hidden" name="mode" id="mode" value="addSong">
        <div class="pdng-inns exp_widthexp">
            <a onclick="validations()" class="btn-cnt">Save</a>
        </div>
    </div>
    </form>
</body>
</html>