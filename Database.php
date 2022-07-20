<?php

class Database {

//192.168.0.104

    private $ini_array = array();
    //master
    private $db_host_master = 'localhost';
    private $db_user_master = 'root';
    private $db_pass_master = '123456';
    //DB NAME
    private $db_name = 'song_project_20july2022';
    //slave
    private $db_host_slave = 'localhost';
    private $db_user_slave = 'root';
    private $db_pass_slave = '123456';
    private $con = false;
    // Checks to see if the connection is active // Database
    private $result = array();         // Results that are returned from the query

    /*
     * Connects to the database, only one connection
     * allowed
     */
    private $logger;

    public function __construct($captch = 0) {
        if ($captch == 0) {
            //$this->logger = Logger::getLogger(__CLASS__);
            //$this->logger->debug('Hello!');
        }
    }

    // //$this->logger = Logger::getLogger(__CLASS__);
    // //$this->logger->debug('Hello!');

    public function connect($db_type = "master") {
        $this->con = false;
        $host_name = "db_host_" . $db_type;
        $host_user = "db_user_" . $db_type;
        $host_password = "db_pass_" . $db_type;
        $host = $this->$host_name;
        $user = $this->$host_user;
        $password = $this->$host_password;
//echo $host."===". $user."===".$password."$this->db_name"; die;
        if (!$this->con) {
            $myconn = @mysqli_connect($host, $user, $password, $this->db_name);
          
            // //$this->logger->info("connection set: " . $myconn->connect_errno);
            if ($myconn) {
                $seldb = @mysqli_select_db($myconn, $this->db_name);
                // //$this->logger->info("db select: " . $seldb);
                if ($seldb) {
                    // //$this->logger->info("db selected" . $this->db_name);
                    $this->con = $myconn;
                } else {
                    $this->con = false;
                }
            } else {
                $this->con = false;
            }
        }
    }

    function executeQueryCaptcha($sql) {
        $this->connectCaptcha();
        $result = @mysqli_query($this->con, $sql) or die(header('location: errorData/error.html'));
        return $result;
    }

    public function connectCaptcha($db_type = "master") {
        $this->con = false;
        $host_name = "db_host_" . $db_type;
        $host_user = "db_user_" . $db_type;
        $host_password = "db_pass_" . $db_type;
        $host = $this->$host_name;
        $user = $this->$host_user;
        $password = $this->$host_password;

        if (!$this->con) {
            $myconn = @mysqli_connect($host, $user, $password, $this->db_name);
            if ($myconn) {
                $seldb = @mysqli_select_db($myconn, $this->db_name);
                if ($seldb) {
                    $this->con = $myconn;
                } else {
                    $this->con = false;
                }
            } else {
                $this->con = false;
            }
        }
    }

    public function connect1() {
        $this->con = false;
        if (!$this->con) {
            $myconn = @mysqli_connect($this->db_host_second, $this->db_user_second, $this->db_pass_second);
            //$this->logger->info("connection set: " . $myconn);
            if ($myconn) {
                $seldb = @mysqli_select_db($this->db_name_second, $myconn);
                //$this->logger->info("db select: " . $seldb);
                if ($seldb) {
                    //$this->logger->info("db selected" . $this->db_name_second);
                    $this->con = true;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /*
     * Changes the new database, sets all current results
     * to null
     */

    public function setDatabase($name) {
        if ($this->con) {
            if (@mysqli_close()) {
                $this->con = false;
                $this->results = null;
                $this->db_name = $name;
                $this->connect();
            }
        }
    }

    /*
     * Check`s to see if the table exists when performing
     * queries
     */

       public function tableExists($table) {
        $this->connect("slave");
        $query='SHOW TABLES FROM ' . $this->db_name . ' LIKE "' . $table . '"';
        //$this->logger->info("Executing Query tableExists: " . $query);
        $tablesInDb = @mysqli_query($this->con,$query);      
        if ($tablesInDb) {
            if (mysqli_num_rows($tablesInDb) == 1) {
                return true;
            } else {
                return false;
            }
        }
    }


    /*
     * Selects information from the database.
     * Required: table (the name of the table)
     * Optional: rows (the columns requested, separated by commas)
     *           where (column = value as a string)
     *           order (column DIRECTION as a string)
     */

    public function select($table, $rows = '*', $where = null, $order = null) {
        $q = 'SELECT ' . $rows . ' FROM ' . $table;
        if ($where != null)
            $q .= ' WHERE ' . $where;
        if ($order != null)
            $q .= ' ORDER BY ' . $order;

        $query = @mysqli_query($q);
        if ($query) {
            $this->numResults = mysqli_num_rows($query);
            for ($i = 0; $i < $this->numResults; $i++) {
                $r = mysqli_fetch_array($query);
                $key = array_keys($r);
                for ($x = 0; $x < count($key); $x++) {
                    // Sanitizes keys so only alphavalues are allowed
                    if (!is_int($key[$x])) {
                        if (mysqli_num_rows($query) > 1)
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        else if (mysqli_num_rows($query) < 1)
                            $this->result = null;
                        else
                            $this->result[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
            mysqli_close($this->con);
            return true;
        }
        else {
            return false;
        }
    }

    /** Author: Arif Husain
     * @param $value
     * @return string
     */
    public function clean_string($value, $link) {
        if ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || ini_get('magic_quotes_sybase')) {
            $value = stripslashes($value);
        }
// escape things properly
        return mysqli_real_escape_string($value, $link);
    }

    /*
     * Insert values into the table
     * Required: table (the name of the table)
     *           values (the values to be inserted)
     * Optional: rows (if values don't match the number of rows)
     */

    public function insert($table, $values, $rows = null) {
        if ($this->tableExists($table)) {
            $insert = 'INSERT INTO ' . $table;
            if ($rows != null) {
                $insert .= ' (' . $rows . ')';
            }

            for ($i = 0; $i < count($values); $i++) {
                if (is_string($values[$i]))
                    $values[$i] = '"' . $values[$i] . '"';
            }
            $values = implode(',', $values);
            $insert .= ' VALUES (' . $values . ')';

            $ins = @mysqli_query($insert);
            mysqli_close($this->con);

            if ($ins) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
     * Insert values into the table
     * Required: table (the name of the table)
     *           values (the values to be inserted)
     * Optional: rows (if values don't match the number of rows)
     */

  public function insertAssociativeArray($table, $userList,$insid =0) {
        

        if ($this->tableExists($table)) {

            $i = 0;
            foreach ($userList as $key => $value) {
                if ($i == 0) {
              
                      $ins = "INSERT INTO $table set $key='$value'";

                } else { 
                
                     $ins = $ins .','.$key.'="'.$value.'"';
                    
                }
                $i = $i + 1;
            }
            $ins;

            //$this->logger->info("Executing Query for insertAssociativeArray: " . $ins);
           $this->connect("master");
           $result = @mysqli_query($this->con,$ins) or die(header('location: errorData/error.html'));          
        

            if ($result) {
                 if($insid){
               return  mysqli_insert_id($this->con)  ;
        }
                else{
                    return;
            }
            } else {
                return;
            }
        }
    }


     public function insertAssociativeArrayEncrypt($table, $userList,$insid =0) {
       

        if ($this->tableExists($table)) {

            $i = 0;
            foreach ($userList as $key => $value) {
                if ($i == 0) {

                    $ins = "INSERT INTO $table set $key='$value'";

                } else {
                    $ins = $ins .','.$key.'="'.$value.'"';
                }
                $i = $i + 1;
            }
            $ins;

            //$this->logger->info("Executing Query for insertAssociativeArray: " . $ins);
            $this->connect("master");
            $result = @mysqli_query($this->con,$ins) or die(header('location: errorData/error.html'));
            mysqli_close($this->con);      
            if ($result) {
                if($insid){
                return mysqli_insert_id($this->con)  ;
                }
                else{
                    return;
            } }else {
                return;
            }
        }
    }


    /* --------------------------- maxrow---------------------------------------------- */

    public function maxId($table, $fieldName, $where = null) {
        $this->connect();
        $query = "SELECT max($fieldName) as maxid  FROM $table";
        if ($where != null)
            $query .= " WHERE $where";
        $sqlRs = mysqli_query($query);
        $maxrow = mysqli_fetch_array($sqlRs);
        mysqli_close($this->con);
        return $maxrow['maxid'];
    }

    public function duplicateField($table, $fieldName, $where = null) {
        $this->connect();
        $query = "SELECT $fieldName  FROM  $table";
        if ($where != null)
            $query .= " WHERE  $where";
        $sqlRs = mysqli_query($query);
        $maxrow = mysqli_num_rows($sqlRs);
        mysqli_close($this->con);
        return $maxrow;
    }

    /*

      field addUpdateMenu
     */

    public function addUpdate($table, $listValue, $updatefield, $menuOrder) {

        $this->connect();

        foreach ($updatefield as $key => $value) {
            $query = "update $table set  $key='$value' where $key='$menuOrder'";
            //$this->logger->info("Executing Query addUpdate: " . $query);
            mysqli_query($query) or die(header('location: errorData/error.html'));
            mysqli_close($this->con);
        }



        foreach ($listValue as $key => $value) {
            if ($i == 0) {
                $ins = "INSERT INTO $table set $key='$value'";
            } else {
                $ins = $ins . ",$key='$value'";
            }
            $i = $i + 1;
        }


        $ins = @mysqli_query($ins);
        mysqli_close($this->con);
    }

    /*
     * Deletes table or records where condition is true
     * Required: table (the name of the table)
     * Optional: where (condition [column =  value])
     */

    public function delete($table, $where = null) {

        $this->connect();

        if ($this->tableExists($table)) {
            if ($where == null) {
                $delete = 'DELETE ' . $table;
            } else {
                $delete = 'DELETE FROM ' . $table . ' WHERE ' . $where;
            }
            $del = @mysqli_query($delete);
            mysqli_close($this->con);

            if ($del) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Updates the database with the values sent
     * Required: table (the name of the table to be updated
     *           rows (the rows/values in a key/value array
     *           where (the row/condition in an array (row,condition) )
     */

    public function update($table, $rows, $where) {



        
        if ($this->tableExists($table)) {


            // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row
            for ($i = 0; $i < count($where); $i++) {

                if ($i % 2 != 0) {
                    if (is_string($where[$i])) {
                        if (($i + 1) != null)
                            $where[$i] = '"' . $where[$i] . '" AND ';
                        else
                            $where[$i] = '"' . $where[$i] . '"';
                    }
                }
            }
            $where = implode('=', $where);


            $update = 'UPDATE ' . $table . ' SET ';
            $keys = array_keys($rows);
            for ($i = 0; $i < count($rows); $i++) {
                if (is_string($rows[$keys[$i]])) {
                    $update .= $keys[$i] . '="' . $rows[$keys[$i]] . '"';
                } else {
                    $update .= $keys[$i] . '=' . $rows[$keys[$i]];
                }

                // Parse to add commas
                if ($i != count($rows) - 1) {
                    $update .= ',';
                }
            }



            if ($update) {
                $update .= ' WHERE ' . $where;
                //$this->logger->info("Executing Query for Update: " . $update);
            $this->connect("master");
                $query = @mysqli_query($this->con,$update) or die(header('location: errorData/error.html')) ;               
                mysqli_close($this->con);

                if ($query) {

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }



    /*
     * Returns the result set
     */

    public function getResult() {
        return $this->result;
    }

    # Function to get the current page URL
    /**
     * @author  Arif Husain <arif.hussain@gingerwebs.in>
     * @return string
     */

    public function currentPageURL() {
        if (!defined('SERVER_PROTOCOL'))
            define('SERVER_PROTOCOL', ( ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) || (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == 443))) ? "https" : "http"));

        $pageURL = SERVER_PROTOCOL . '://';
        if ($_SERVER["SERVER_PORT"] != "80")
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        else
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

        return $pageURL;
    }

    /**
     * @author  Arif Husain <arif.hussain@gingerwebs.in>
     * @param $sql
     * @return resource
     */
    function executeQuery($sql,$insid=0) {
             $trempArr = explode(" ", trim($sql));
        if (trim($sql) != "") {
            if (strpos(strtolower($trempArr[0]), "select") !== false) {
                $this->connect("slave");
            } else {
                $this->connect("master");
            }
        }

        $result = @mysqli_query($this->con,$sql) ;
        $errorMsg= "\n------------------------------------------<<<<<<<<<<<<<<<<<<<<<<<<<<  Executed  Query at [".date("d-m-Y H:i:s")."]: >>>>>>>>>>>>>>>>>>>>>>>>----------------------------------------------\n";
//            var_dump($this->con); echo "mm11mm";
        if(!$result){
            $error_no= mysqli_errno($this->con);
            $error_throw= mysqli_error($this->con);
            $errorMsg.= "{ ".$sql." }\n".
                ( (isset($error_no) && !empty($error_no)) ? "( Error number: [".$error_no. "]==> Error Message: ".$error_throw : '' ).") occured on URL {".$this->currentPageURL()."} at ".date("d-m-Y H:i:s");
            $errorMsg.="\n--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n";
            //$this->logger->info( $errorMsg);
           die(header('location: errorData/error.html'));
            exit;
        }
        else{
            $errorMsg.= "{ ".$sql." }";
            $errorMsg.="\n--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n";
            //$this->logger->info( $errorMsg);
            if($insid){
                return mysqli_insert_id($this->con);
            }else{
            return $result;
            }
        }
    }


    function executeQuery1($sql) {
        //$this->logger->info("Executing Query : " . $sql);
        $this->connect1();
        $result = @mysqli_query($sql) or die(header('location: errorData/error.html'));
        return $result;
    }

    /**
     * @author  Arif Husain <arif.hussain@gingerwebs.in>
     * @function ( query ) to get db result
     * @param $query
     * @param string $return_type
     * @param string $dataset_gruop_type
     * @param string $set_key
     * @return array|mixed|resource
     */
    public function query($query, $return_type = 'all', $dataset_gruop_type = 'objects', $set_key = '') {

        $result = $this->executeQuery($query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_object($result)) {
                if (isset($dataset_gruop_type) && ($dataset_gruop_type == 'arrays')) {
                    $row = (array) $row;

                    if (isset($set_key) && !empty($set_key))
                        $resultset[$row[$set_key]] = $row;
                    else
                        $resultset[] = $row;
                }
                else {
                    if (isset($set_key) && !empty($set_key))
                        $resultset[$row->$set_key] = $row;
                    else
                        $resultset[] = $row;
                }
            }

            if (isset($resultset)) {
                if ($return_type == 'first')
                    return $resultset[0];
                else if ($return_type == 'last')
                    return end($resultset);
                else
                    return $resultset;
            }
        }
        else {
            if (is_array($return_type) && ($return_type == 'first'))
                return current($this->resultset);
            else if (is_array($return_type) && ($return_type == 'last'))
                return end($this->resultset);
            else
                return mysqli_num_rows($result);
        }
    }

    /*
     * Get Single Result.
     */

    function getSingleResult($sql) {
        $response = "";
        $result = mysqli_query($sql);
        if ($line = mysqli_fetch_array($result)) {
            $response = $line[0];
        }
        mysqli_close($this->con);
        return $response;
    }

    public function getListBox($table, $rows = '*', $where = null, $order = null, $selected) {
        $this->connect();
        $q = 'SELECT ' . $rows . ' FROM ' . $table;
        if ($where != null)
            $q .= ' WHERE ' . $where;
        if ($order != null)
            $q .= ' ORDER BY ' . $order;
        $query = $this->executeQuery($q);
        while ($result = mysqli_fetch_array($query)) {
            echo $selected;

            if ($selected == $result[0]) {
                $sal = " selected";
            } else {
                $sal = "";
            }

            echo "<option $sal value=\"" . $result[0] . "\">" . $result[1] . "</option>\n";
        }
    }

    public function Pagination($tbl_name, $limit, $path, $column = NULL) {
        if ($column == NULL)
            $query = "SELECT COUNT(*) as num FROM $tbl_name";
        else
            $query = "SELECT COUNT($column) as num FROM $tbl_name";

        //$this->logger->info("Executing Query for pagination: " . $query);
        $row = mysqli_fetch_array(mysqli_query($query));
        mysqli_close($this->con);
        $total_pages = $row['num'];

        $adjacents = "2";

        $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
        $page = ($page == 0 ? 1 : $page);

        if ($page)
            $start = ($page - 1) * $limit;
        else
            $start = 0;

        //$sql = "SELECT id FROM $tbl_name LIMIT $start, $limit";
        //$result = mysqli_query($sql);

        $prev = $page - 1;
        $next = $page + 1;
        if ($limit != '' or $limit != 0) {
            $lastpage = ceil($total_pages / $limit);
        }
        $lpm1 = $lastpage - 1;
        $from = $start + 1;
        $to = $start + $limit;
        if ($to >= $total_pages)
            $to = $total_pages;
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<div class='pagination'>";
            if ($page > 1)
                $pagination .= "<div id='displayitem4'>Item <b>" . $from . "-" . $to . "</b> of $total_pages </div><a href='" . $path . "page=$prev'>« previous</a>";
            else
                $pagination .= "<div id='displayitem4'>Item <b>" . $from . "-" . $to . "</b> of $total_pages </div> <span class='disabled'>« previous</span>";

            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class='current'>$counter</span>";
                    else
                        $pagination .= "<a href='" . $path . "page=$counter'>$counter</a>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>$counter</span>";
                        else
                            $pagination .= "<a href='" . $path . "page=$counter'>$counter</a>";
                    }
                    $pagination .= "...";
                    $pagination .= "<a href='" . $path . "page=$lpm1'>$lpm1</a>";
                    $pagination .= "<a href='" . $path . "page=$lastpage'>$lastpage</a>";
                }
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<a href='" . $path . "page=1'>1</a>";
                    $pagination .= "<a href='" . $path . "page=2'>2</a>";
                    $pagination .= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>$counter</span>";
                        else
                            $pagination .= "<a href='" . $path . "page=$counter'>$counter</a>";
                    }
                    $pagination .= "..";
                    $pagination .= "<a href='" . $path . "page=$lpm1'>$lpm1</a>";
                    $pagination .= "<a href='" . $path . "page=$lastpage'>$lastpage</a>";
                }
                else {
                    $pagination .= "<a href='" . $path . "page=1'>1</a>";
                    $pagination .= "<a href='" . $path . "page=2'>2</a>";
                    $pagination .= "..";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>$counter</span>";
                        else
                            $pagination .= "<a href='" . $path . "page=$counter'>$counter</a>";
                    }
                }
            }

            if ($page < $counter - 1)
                $pagination .= "<a href='" . $path . "page=$next'>next »</a>";
            else
                $pagination .= "<span class='disabled'>next »</span>";
            $pagination .= "</div>\n";
        }


        return $pagination;
    }

    /* Pagination Useing Multiple table */

    public function PaginationMultipleTable($sqlQuery, $limit, $path, $column = NULL) {


        if ($column == NULL)
            $query = $sqlQuery;
        else
            $query = $sqlQuery;
        //$this->logger->info("Executing Query for pagination: " . $query);
        $row = mysqli_num_rows(mysqli_query($query));
        mysqli_close($this->con);
        $total_pages = $row;

        $adjacents = "2";

        $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
        $page = ($page == 0 ? 1 : $page);

        if ($page)
            $start = ($page - 1) * $limit;
        else
            $start = 0;

        //$sql = "SELECT id FROM $tbl_name LIMIT $start, $limit";
        //$result = mysqli_query($sql);

        $prev = $page - 1;
        $next = $page + 1;
        if ($limit != '' or $limit != 0) {
            $lastpage = ceil($total_pages / $limit);
        }
        $lpm1 = $lastpage - 1;
        $from = $start + 1;
        $to = $start + $limit;
        if ($to >= $total_pages)
            $to = $total_pages;
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<div class='pagination'>";
            if ($page > 1)
                $pagination .= "<div id='displayitem4'>Item <b>" . $from . "-" . $to . "</b> of $total_pages </div><a href='" . $path . "page=$prev'>« previous</a>";
            else
                $pagination .= "<div id='displayitem4'>Item <b>" . $from . "-" . $to . "</b> of $total_pages </div> <span class='disabled'>« previous</span>";

            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class='current'>$counter</span>";
                    else
                        $pagination .= "<a href='" . $path . "page=$counter'>$counter</a>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>$counter</span>";
                        else
                            $pagination .= "<a href='" . $path . "page=$counter'>$counter</a>";
                    }
                    $pagination .= "...";
                    $pagination .= "<a href='" . $path . "page=$lpm1'>$lpm1</a>";
                    $pagination .= "<a href='" . $path . "page=$lastpage'>$lastpage</a>";
                }
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<a href='" . $path . "page=1'>1</a>";
                    $pagination .= "<a href='" . $path . "page=2'>2</a>";
                    $pagination .= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>$counter</span>";
                        else
                            $pagination .= "<a href='" . $path . "page=$counter'>$counter</a>";
                    }
                    $pagination .= "..";
                    $pagination .= "<a href='" . $path . "page=$lpm1'>$lpm1</a>";
                    $pagination .= "<a href='" . $path . "page=$lastpage'>$lastpage</a>";
                }
                else {
                    $pagination .= "<a href='" . $path . "page=1'>1</a>";
                    $pagination .= "<a href='" . $path . "page=2'>2</a>";
                    $pagination .= "..";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>$counter</span>";
                        else
                            $pagination .= "<a href='" . $path . "page=$counter'>$counter</a>";
                    }
                }
            }

            if ($page < $counter - 1)
                $pagination .= "<a href='" . $path . "page=$next'>next »</a>";
            else
                $pagination .= "<span class='disabled'>next »</span>";
            $pagination .= "</div>\n";
        }


        return $pagination;
    }

    public function DisplayCategory($catid = '', $sep = '', $pcatid = 0, $selected = '', $tableNeme) {

        $this->connect();
        echo $sql = "select Id ,MenuName,ParentID from $tableNeme where ParentID=$pcatid";
        $result = mysqli_query($sql) or die(header('location: errorData/error.html'));
        mysqli_close($this->con);


        while ($line = mysqli_fetch_array($result)) {


            $parent_cat_id = $line['ParentID'];
            $combo = "<option value=" . $line['Id'];
            if ($line['Id'] == $selected) {
                $combo .= " selected";
            }
            $combo .= ">$sep" . $line['MenuName'] . "</option>";

            echo $combo;
            $this->DisplaySubCategory($line['Id'], $sep, $parent_cat_id, $selected, $tableNeme);
        }
    }

    public function DisplaySubCategory($subid, $sep, $pcid, $subselected = '', $tableNeme) {
        $this->connect();
        $sep .= "&raquo;";
        $sql = "select Id ,MenuName  from $tableNeme where ParentID='$subid'";
        $r1 = mysqli_query($sql);

        while ($lr = mysqli_fetch_array($r1)) {
            $pcid = $lr[ParentID];
            $nu = "<option value='" . $lr['Id'] . "'";
            if ($lr['Id'] == $subselected) {
                $nu .= " selected ";
            }
            $nu .= ">$sep&nbsp;" . $lr['MenuName'] . "&nbsp;</option>";
            echo $nu;
            $this->DisplaySubCategory($lr['Id'], $sep, $pcid, $subselected, $tableNeme);
        }
        mysqli_close($this->con);
    }

    public function updateAssoc($table, $values_array_set, $conditions_array_set)
    {
        $this->connect("master");
        $fields = $values = array();
        foreach($values_array_set as $key=>$value)
        {
            $fields[] = $key;
            $values[] = $value;
        }

        $filter_cols = $filter_vals = array();
        foreach($conditions_array_set as $filter_key=>$filter_value)
        {
            $filter_cols[] = $filter_key;
            $filter_vals[] = $filter_value;
        }

        $errorMsg= "\n------------------------------------------<<<<<<<<<<<<<<<<<<<<<<<<<<  Executed  Query at [".date("d-m-Y H:i:s")."]: >>>>>>>>>>>>>>>>>>>>>>>>----------------------------------------------\n";
        if(empty($table))
        {
            $errorMsg.='table name missing';
            //$this->logger->info( $errorMsg);
            return null;
        }
        if(empty($fields))
        {
            $errorMsg= 'Fields can not be empty set';
            //$this->logger->info( $errorMsg);
            return null;
        }

        if(empty($values))
        {
            $errorMsg= 'Values set can not be empty';
            //$this->logger->info( $errorMsg);
            return null;
        }

        $sql = "UPDATE $table SET ";
        foreach($fields as $key => $fieldname)
        {
            $sql .= "$fieldname ='".$values[$key]."'";
            if($key < count($fields)-1)
                $sql .=',';
        }
        if(!empty($filter_cols))
        {
            $sql .= ' WHERE ';
            foreach($filter_cols as $fkey => $filtername)
            {
                $sql .= "$filtername ='".$filter_vals[$fkey]."'";
                if($fkey < count($filter_cols)-1)
                    $sql .=' AND ';
            }
        }


        $queryResult = @mysqli_query($this->con,$sql) ;
        if(!$queryResult){
            $error_no= mysqli_errno($this->con);
            $error_throw= mysqli_error($this->con);
            $errorMsg.= "{ ".$sql." }\n".
                ( (isset($error_no) && !empty($error_no)) ? "( Error number: [".$error_no. "]==> Error Message: ".$error_throw : '' ).") occured on URL {".$this->currentPageURL()."} at ".date("d-m-Y H:i:s");
            $errorMsg.="\n--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n";
           // //$this->logger->info( $errorMsg);
         die(header('location: errorData/error.html'));
            exit;
        }
        else{
            $errorMsg.= "{ ".$sql." }";
            $errorMsg.="\n--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n";
            ////$this->logger->info( $errorMsg);
            return $queryResult;
        }
    }


    function backup_table($table_name, $backup_table_name) {

        db_query("CREATE TABLE $backup_table_name LIKE $table_name");

        db_query("ALTER TABLE $backup_table_name DISABLE KEYS");

        db_query("INSERT INTO $backup_table_name SELECT * FROM $table_name");

        db_query("ALTER TABLE $backup_table_name ENABLE KEYS");
    }

    function get_Id() {



        $this->connect();
        $id = mysqli_insert_id();
        return $id;
    }

}

?>
