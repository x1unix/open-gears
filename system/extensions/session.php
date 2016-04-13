<?php
session_start();

class Session {
  
  public static function get($key, $default='') {
    return (self::has($key)) ? $_SESSION[$key] : $default;
  }

  public static function has($key) {
    return isset($_SESSION[$key]);
  }

  private static function assign($key, $val) {
    $_SESSION[$key] = $val;
  }

  public static function set($key, $value, $allowOverwrite=true) {
    if( $allowOverwrite || !self::has($key) ) return self::assign($key, $value);
  }

}