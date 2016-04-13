<?
class Dir {
  
  public static function Exists($path) {
    return file_exists($path) && is_dir($path);
  }

  public static function Create($path, $recursive=true) {
    return mkdir($path, 0777, $recursive);
  }
}