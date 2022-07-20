<?php
class func extends Database{
    public function getArtist(){
        $sql = "SELECT ARTIST_ID,ARTIST_NAME,DOB,SONG FROM ARTIST WHERE STATUS = 1";
        $result = parent::executeQuery($sql);
        // print_r($result);
        return $result;
    }
    public function getArtistById($id){
        $sql = "SELECT ARTIST_ID,ARTIST_NAME FROM ARTIST WHERE ARTIST_ID = $id";
        $res = parent::executeQuery($sql);
        $fetchData = mysqli_fetch_object($res);
        return $fetchData->ARTIST_NAME;
    }
    public function getAllSongData(){
        $songSql = "SELECT SONG_ID,SONG_NAME,ARTIST_ID,RATING FROM SONGS ORDER BY RATING DESC LIMIT 0,10";
        $res = parent::executeQuery($songSql);
        return $res;
    }
}



?>