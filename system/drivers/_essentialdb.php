<?php
  /**
   * OpenGears Basic MySQL Driver
   * @version 0.5.1
   * @package opengears
   * @author Denis Sedchenko [sedchenko.in.ua]
   */
class DataBase 
{

  public $Hostname  = DB_HOST;
  public $User      = DB_USER;
  public $Password  = DB_PASS;
  public $Base      = DB_BASE;
  public $Table     = "";

  public $Connection;

  /**
   * Connect to MYSQL database
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
   * @return String $str
   */
  public function EscapeString($str)
  {
    $this->Connect();
    return $this->Connection->escape_string($str);
  }

  /**
   * Execute MYSQL query
   * @return MySQLiResult $result
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
   * Return an array of rows from MySQLi Result
   * @return String[] $a
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
   * Return an single row from MySQLi Result
   * @return String[] $result
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
   * Execute SQL query and return rows array
   * @return String[] $result
   */
  public function GetRows($queryString)
  {
    return $this->ToRows($this->Query($queryString));
  }

  /**
   * Execute SQL query and return single row
   * @return String[] $result
   */
  public function GetRow($queryString)
  {
    return $this->ToRow($this->Query($queryString));
  }

  /**
   * Returns a count of items
   * @return int $result
   */
  public function Count($table,$items="*",$additionals="")
  {
    if($additionals!="") $additionals = " ".$additionals;
    $a = $this->getRow("select count($items) from $table$additionals;");
    return intval($a["count(".$items.")"]);
  }


}


class MySQLConnectException extends Exception { }
class MySQLQueryException extends Exception { }
class MySQLiResultException extends Exception { }
?>