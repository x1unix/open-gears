<?php


class TimeStamp
{
  public static function ToInteger($str)
  {
    return strtotime($str);
  }
  public static function ToString($integer)
  {
    return date('Y-m-d H:i:s',intval($integer));
  }
  public static function Now($tostring=false)
  {
    if(!$tostring) return time();
    return self::ToString(time());
  }
}

?>