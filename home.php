<?php
$ob = new func();
$userId = $_GET['id'];
if(empty($userId)){
    echo "<script>alert('Please login to continue');</script>";
    echo "<script>window.location.href='index.php?p=login';</script>";
    die;
}else{
    $fetchUserSql = "SELECT USER_ID,NAME,EMAIL,PASSWORD FROM USER WHERE USER_ID = $userId";
    $exec = $ob->executeQuery($fetchUserSql);
    $fetchData = mysqli_fetch_object($exec);
    $name = $fetchData->NAME;
}
$getAllSongData = $ob->getAllSongData();
$getArtistData = $ob->getArtist();

$c = 0;
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
    <div style="float: right;">
        <li class="nav_li nav_li_active"><a href="index.php?p=form&id=<?php echo $userId;?>">Add Song</a></li>
    </div>  
    <span class="login-fm hed" style="font-weight: bold;">
        Welcome <?php echo ucwords(strtolower($name)); ?>
    </span>
    <span class="login-fm hed" style="font-size: 16px;">
        Top 10 Songs
    </span>
    <table class='qualifctn-dv prv_table_wapper'>
        <tr>
            <td>Artwork</td>
            <td>Song Name</td>
            <td>Artist</td>
            <td>Rating</td>
        </tr>
        
            <?php while($fetchSong = mysqli_fetch_object($getAllSongData)){ 
            $c++;
            ?>
            <tr>
            <td><img style="width: 90px;height:110px;" src="document/<?php echo $fetchSong->SONG_ID;?>.jpg"></td>
            <td><?php echo $fetchSong->SONG_NAME; ?></td>
            <td><?php echo $ob->getArtistById($fetchSong->ARTIST_ID); ?></td>
            <td><?php echo $fetchSong->RATING; ?></td>
            </tr>
            <?php } ?>
        
    </table>
    <span class="login-fm hed" style="font-size: 16px;">
        Top 10 Artist
    </span>
    <table class='qualifctn-dv prv_table_wapper'>
        <tr>
            <td>Artist Name</td>
            <td>Date of Birth</td>
            <td>Songs</td>
        </tr>
        <?php while($fetchArtist = mysqli_fetch_object($getArtistData)){ ?>
            <tr>
                <td><?php echo $fetchArtist->ARTIST_NAME; ?></td>
                <td><?php echo $fetchArtist->DOB; ?></td>
                <td><?php echo $fetchArtist->SONG; ?></td>
            </tr>
        <?php }?>
    </table>
</body>
</html>