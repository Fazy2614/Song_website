<?php 
$mode = trim($_POST['mode']);
// echo $mode;die;
$ob = new func();

if($mode == "addSong"){
    $songName = trim($_POST['songName']);
    $artist = trim($_POST['artist']);
    $rating = $_POST['finalRating'];
    $userId = $_POST['id'];
    $target_Path = "document/"; 

    $sql = "INSERT INTO SONGS SET SONG_NAME = '$songName',ARTIST_ID = $artist,RATING = $rating";
    $exec = $ob->executeQuery($sql);
    $idSql = "SELECT MAX(SONG_ID) AS ID FROM SONGS";
    $execute = $ob->executeQuery($idSql);
    $insertId = mysqli_fetch_object($execute);
    $maxId = $insertId->ID;
    $info = pathinfo($_FILES['artwork']['name']);
    $ext = $info['extension']; // get the extension of the file
    $newname = $maxId.".".$ext; 
    $target = 'document/'.$newname;
    move_uploaded_file($_FILES['artwork']['tmp_name'], $target);
    echo "<script>alert('Your have successfully added a song');</script>";
    echo "<script>window.location.href='index.php?p=home&id=$userId';</script>";
    die;
}

if($mode == "registerMode"){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['userPassword']);    
    $fetchUser = "SELECT EMAIL FROM USER WHERE EMAIL = '$email'";
    $execFetchUser = $ob->executeQuery($fetchUser);
    $numRows = mysqli_num_rows($execFetchUser);
    if($numRows == 0){
        $insertUserSql = "INSERT INTO USER SET NAME='$name',EMAIL='$email',PASSWORD='$password'";
        $execInsertUserSql = $ob->executeQuery($insertUserSql);
        echo "<script>alert('Your have successfully registered, Please login with your email id and password');</script>";
        echo "<script>window.location.href='index.php?p=login';</script>";
        die;
    }else{
        echo "<script>alert('Your have already registered with the entered email id, Please login with your email id and password')</script>";
        echo "<script>window.location.href='index.php?p=login';</script>";
        die;
    }
}
if($mode == "userLogin"){
    $email = trim($_POST['userName']);
    $pass = trim($_POST['password']);
    // print_r($_POST);die;
    $getUserSql = "SELECT USER_ID,NAME,EMAIL,PASSWORD FROM USER WHERE EMAIL = '$email' AND PASSWORD = '$pass'";
    $execGetUser = $ob->executeQuery($getUserSql);
    $fetchNumRows = mysqli_num_rows($execGetUser);
    $fetchUser = mysqli_fetch_object($execGetUser);
    $userId = $fetchUser->USER_ID;
    if($fetchNumRows > 0){
        echo "<script>alert('You have successfully logged in');</script>";
        echo "<script>window.location.href='index.php?p=home&id=$userId';</script>";
    }else{
        echo "<script>alert('Invalid login details.!!');</script>";
        echo "<script>window.location.href='index.php?p=login';</script>";
        die;
    }
}
if($mode == "addArtistMode"){
    $artistName = $_POST['artistName'];
    $dob = $_POST['dob'];
    $song = $_POST['song'];
    $getId = $_POST['id'];
    $inserArtist = "INSERT INTO ARTIST SET ARTIST_NAME = '$artistName',DOB = '$dob',SONG = '$song'";
    $execInsert = $ob->executeQuery($inserArtist);
    echo "<script>alert('Successfully inserted artist);</script>";
    echo "<script>window.location.href='index.php?p=form&id=$getId';</script>";
    die; 
}
?>