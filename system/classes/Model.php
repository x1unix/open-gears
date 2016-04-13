<?
abstract class Model {
  public static function Get($modelName) {
    return System::GetModel($modelName);
  }
}