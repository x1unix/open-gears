<?php
  /**
   * OpenGears Extended MySQL Driver
   *
   * @version 1.0.0
   * @package opengears
   * @author Denis Sedchenko [sedchenko.in.ua]
   */

define("SQL_INSERT","INSERT INTO `@s` SET @f;");
define("SQL_UPDATE","UPDATE `@s` SET @f WHERE @w;");
define("SQL_DELETE","DELETE FROM `@s` WHERE @f;");
define("SQL_SELECT","SELECT @q FROM `@s`@w@order LIMIT @min, @max;");


class MySQLConnectException extends Exception { }
class MySQLQueryException extends Exception { }
class MySQLiResultException extends Exception { }
class MySQLiImportException extends Exception { }
class MySQLiInternalException extends Exception { }

/**
 * Class DataBase
 */
class DataBase 
{

    /**
     * @var string Server host
     */
  public $Hostname  = DB_HOST;

    /**
     * @var string Server user name
    */
  public $User      = DB_USER;

    /**
     * @var string User password
     */
  public $Password  = DB_PASS;

    /**
     * @var string Default MySQL database
     */
  public $Base      = DB_BASE;

    /**
     * @var string Default MySQL table
     */
  public $Table     = "";

  public $Connection;

    /**
     * Connect to MySQL database
     *
     * @throws MySQLConnectException
     */
  public function Connect()
  {
    if(!$this->Connection){
      try {
        $this->Connection = mysqli_connect($this->Hostname,$this->User,$this->Password,$this->Base);
      } catch (Exception $e) {
        throw new MySQLConnectException($e, 1);
        
      }
      if(!$this->Connection) throw new MySQLConnectException("Failed to connect to MySQL dataBase '".$this->Base."'", 1);
    }
  }

    /**
     * Return an escaped string
     *
     * @param string $str
     * @return string
     * @throws MySQLConnectException
     */
  public function EscapeString($str)
  {
    $this->Connect();
    return $this->Connection->escape_string($str);
  }

    /**
     * Execute a query to MySQL
     *
     * @param string $queryString Query
     * @return mysqli_result
     * @throws MySQLConnectException
     * @throws MySQLQueryException
     */
  public function Query($queryString) 
  {
    $this->Connect();
    $result = $this->Connection->query($queryString);
    if(!$result){
      $err = $this->Connection->error;
      $this->Connection->close();
      throw new MySQLQueryException($err.". Query string: ".$queryString, 1);
    }else{
      return $result;
    } 
  }

    /**
     * Convert a mysqli_result to array
     *
     * @param string $MySQLIQueryResult MySQLi Query result
     * @return array|null
     * @throws MySQLiResultException
     */
  public function ToRows($MySQLIQueryResult)
  {
    try {
      $a = array();
      while($item = mysqli_fetch_assoc($MySQLIQueryResult))
      {
        array_push($a, $item);
      }
      return $a;
    } catch (Exception $e) {
      throw new MySQLiResultException($e, 1);
    }
  }

    /**
     * Convert a mysqli_result to array of single row
     *
     * @param string $MySQLIQueryResult MySQLi Query result
     * @return array|null
     * @throws MySQLiResultException
     */
  public function ToRow($MySQLIQueryResult)
  {
    try {
      return mysqli_fetch_assoc($MySQLIQueryResult);
    } catch (Exception $e) {
      throw new MySQLiResultException($e, 1);
    }
  }

    /**
     * Execute MySQL query and return rows
     * @param string $queryString Query
     * @return array
     * @throws MySQLQueryException
     * @throws MySQLiResultException
     */
  public function GetRows($queryString)
  {
    return $this->ToRows($this->Query($queryString));
  }

    /**
     * Execute MySQL query and get a single row from result
     *
     * @param string $queryString Query
     * @return array
     * @throws MySQLQueryException
     * @throws MySQLiResultException
     */
  public function GetRow($queryString)
  {
    return $this->ToRow($this->Query($queryString));
  }

    /**
     * Get rows count
     *
     * @param string $table MySQL table
     * @param string $items Selector
     * @param string $flags Additional flags
     * @return int
     */
    public function Count($table,$items="*",$flags="")
    {
        if($flags!="") $flags = " ".$flags;
        $a = $this->getRow("select count($items) from $table$flags;");
        return intval($a["count(".$items.")"]);
    }

    /**
     * Import SQL dump to the database
     *
     * @param string $file SQL dump path
     * @return bool|mysqli_result
     * @throws MySQLQueryException
     * @throws MySQLiImportException
     */
    public function Import($file)
    {
        if(!is_dir($file) && file_exists($file))
        {
            $result = false;
            $fp = fopen ($file, "r");
            $buffer = fread($fp, filesize($file));
            fclose ($fp);
            $prev = 0;
            while ($next = strpos($buffer,";",$prev+1))
            {
                $a = substr($buffer,$prev+1,$next-$prev);
                $result = $this->Query($a);
                $prev = $next;
            }

            return $result;
        }else{
            throw new MySQLiImportException("File not found: '{$file}'");
        }
    }

    /**
     * Join array into MySQL values string
     *
     * @param array $data Array with data
     * @param string $separator Delimiter
     * @return string Result
     * @throws MySQLiInternalException
     */
    public function JoinArray($data,$separator = ", ")
    {
        $count = 0;
        $fields = '';
        if(is_array($data))
        {
            try {
                foreach($data as $col => $val) {
                    if ($count++ != 0) $fields .= $separator;
                    $col = mysqli_real_escape_string($this->Connection,$col);
                    $val = mysqli_real_escape_string($this->Connection,$val);
                    $fields .= "`$col` = '$val'";
                }
            }catch(Exception $ex) {
                throw new MySQLiInternalException($ex);
            }
        }else{
            throw new MySQLiInternalException("Data parameter must be an array,".gettype($data)." given");
        }
        return $fields;
    }

    //Костыль, позволяющий строить быстро SQL запросы (а может и нет)
    /**
     * MySQL query factory
     *
     * @param string $source Query source (Database or table)
     * @param string $separator String delimiter
     * @param string $template Query template
     * @param array $data Array with values
     * @param bool|false $where Use Where parameter
     * @param string $_s Delimiter
     * @return string MySQL Query String
     * @throws MySQLiInternalException
     */
    public function QueryBuilder($source,$separator,$template,$data,$where=false,$_s = ", ")
    {
        $count = 0;
        $fields = '';
        $_args = "";
        if(is_array($data))
        {
            $fields = $this->JoinArray($data,$separator);
        }else{
            $fields = $data;
        }
        $count = 0;
        if(is_array($where))
        {
            $_args = $this->JoinArray($where,$_s);
        }else{
            if($where !== false) $_args = $where;
        }
        $q = $template;
        $q = str_replace("@s", $source, $q);
        $q = str_replace("@f", $fields, $q);
        $q = str_replace("@w", $_args, $q);
        return $q;
    }

    /**
     * Search values in MySQL
     *
     * @param string $source Where to search (DB, table)
     * @param string $field Field to compare
     * @param string $search Search query
     * @return bool|mysqli_result
     * @throws MySQLQueryException
     */
    public function Search($source,$field,$search)
    {
        $search = preg_replace("#\#s=#msi", "", $search);
        $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
        $search = preg_replace("#  +#msi", " ", $search);
        $q = "SELECT * FROM `{$source}` WHERE ` {$field}` LIKE '%{$search}%';";
        return $this->Query($q);
    }

    /**
     * Find rows in MySQL
     *
     * @param string $source Where to search (table, database)
     * @param string $field Which field to compare
     * @param string $search Search query
     * @return String[] Founded rows
     * @throws MySQLiResultException
     */
    public function Find($source,$field,$search)
    {
        return $this->toRows(
            $this->Search($source,$field,$search)
        );
    }

    /**
     * Find a single row in MySQL
     *
     * @param string $source Where to search (table, database)
     * @param string $field Which field to compare
     * @param string $search Search query
     * @return String[] Founded rows
     * @throws MySQLiResultException
     */
    public function FindSingle($source,$field,$search)
    {
        return $this->toRow(
            $this->Search($source,$field,$search)
        );
    }


    /**
     * Select data from MySQL
     *
     * @param string $source Database or table name
     * @param string $as Fields
     * @param bool|false $order_by Order by
     * @param mixed $where Where
     * @param int $from Start entry
     * @param int $to Count of elements
     * @return mysqli_result Query result
     * @throws MySQLQueryException
     * @throws MySQLiInternalException
     */
    public function Select($source,$as="*",$order_by=false,$where="",$from=0,$to=1000)
    {
        $q = str_replace("@q", $as, SQL_SELECT);
        $q = str_replace("@s", $source, $q);
        $w = "";
        if($where!== "") {
            $w = " WHERE ";
            $w .= $this->JoinArray($where," AND ");
        }
        $q = str_replace("@w", $w, $q);
        $o = "";
        if($order_by !== false) $o = " ".$order_by;
        $q = str_replace("@order", $o, $q);
        $q = str_replace("@min", $from, $q);
        $q = str_replace("@max", $to, $q);

        return $this->Query($q);
    }

    /**
     * Select an array of rows
     *
     * @param string $source Database or table name
     * @param string $fields Fields
     * @param bool|false $order_by Order by
     * @param mixed $where Where
     * @param int $from Start entry
     * @param int $count Count of elements
     * @return array
     * @throws MySQLQueryException
     * @throws MySQLiInternalException
     */
    public function SelectAll($source,$fields="*",$order_by=false,$where="",$from=0,$count=1000)
    {
        return $this->toRows(
            $this->Select($source,$fields,$order_by,$where,$from,$count)
        );
    }

    /**
     * Select an single row
     *
     * @param string $source Database or table name
     * @param string $fields Fields
     * @param bool|false $order_by Order by
     * @param mixed $where Where
     * @param int $from Start entry
     * @param int $count Count of elements
     * @return array
     * @throws MySQLQueryException
     * @throws MySQLiInternalException
     */
    public function SelectOne($source,$fields="*",$order_by=false,$where="",$from=0,$count=1000)
    {
        return $this->toRows(
            $this->Select($source,$fields,$order_by,$where,$from,$count)
        );
    }

    /**
     * Update a row(s)
     *
     * @param string $source Database or table nam
     * @param array $_set Values
     * @param array $_where Filter
     * @return Database $this Instance
     * @throws MySQLQueryException
     * @throws MySQLiInternalException
     */
    public function Update($source,$_set,$_where)
    {
        $this->Query(
            $this->QueryBuilder($source,", ",SQL_UPDATE,$_set,$_where)
        );
        return $this;
    }

    /**
     * Insert a row
     *
     * @param string $into Table
     * @param array $data Array with data
     * @return Database $this Instance
     * @throws MySQLQueryException
     */
    public function Insert($into,$data)
    {
        $this->Query(
            $this->QueryBuilder($into,", ",SQL_INSERT,$data)
        );
        return $this;
    }


    /**
     * Delete a row(s) from table
     *
     * @param string $from Table name
     * @param array $where Name and value
     * @return Database $this Instance
     * @throws MySQLQueryException
     */
    public function Delete($from,$where)
    {
        $this->Query(
            $this->QueryBuilder($from," AND ",SQL_DELETE,$where)
        );
        return $this;
    }


    /**
     * Create a MySQL database dump
     *
     * @param string $dump_dir Directory path to store dump file
     * @param string $dump_name Dump filename
     * @param int $insert_records Max records count to insert
     * @throws MySQLQueryException
     */
    public function MakeDump($dump_dir="tmp",$dump_name="dump.sql",$insert_records=5000)
    {
        try{
            $this->Connect();
            $res = $this->Query("SHOW TABLES");
            $fp = fopen( $dump_dir."/".$dump_name, "w" );
            while( $table = mysqli_fetch_row($res) )
            {
                $query="";
                if ($fp)
                {
                    $res1 = $this->Query("SHOW CREATE TABLE ".$table[0]);
                    $row1=mysqli_fetch_row($res1);
                    $query="\nDROP TABLE IF EXISTS `".$table[0]."`;\n".$row1[1].";\n";
                    fwrite($fp, $query); $query="";
                    $r_ins = $this->Query('SELECT * FROM `'.$table[0].'`');
                    if(mysqli_num_rows($r_ins)>0){
                        $query_ins = "\nINSERT INTO `".$table[0]."` VALUES ";
                        fwrite($fp, $query_ins);
                        $i=1;
                        while( $row = mysqli_fetch_row($r_ins) )
                        { $query="";
                            foreach ( $row as $field )
                            {
                                if ( is_null($field) )$field = "NULL";
                                else $field = "'".mysqli_escape_string($this->Connection,$field)."'";
                                if ( $query == "" ) $query = $field;
                                else $query = $query.', '.$field;
                            }
                            if($i>$insert_records){
                                $query_ins = ";\nINSERT INTO `".$table[0]."` VALUES ";
                                fwrite($fp, $query_ins);
                                $i=1;
                            }
                            if($i==1){$q="(".$query.")";}else $q=",(".$query.")";
                            fwrite($fp, $q); $i++;
                        }
                        fwrite($fp, ";\n");
                    }
                }
            } fclose ($fp);

        }catch(Exception $iex){
            throw new MySQLQueryException($iex);
        }
    }




}


?>
