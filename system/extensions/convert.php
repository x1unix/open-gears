<?php
  /**
   * OpenGears Conversion Module
   *
   * @version 1.0.0
   * @package opengears
   * @author Denis Sedchenko [sedchenko.in.ua]
   */
  

class Convert
{
	public static function toBoolean($var)
	{
		$type = typeof($var);
		switch ($type) {
			case 'string':
				return (strtolower($var) == "true");
				break;
			case 'integer':
				return (bool) $var;
				break;
			case 'boolean':
				return $var;
				break;
			default:
				return false;
				break;
		}
	}
	public static function toString($var){
		$type = typeof($var);
		switch ($type) {
			case 'boolean':
				if($var == true) return "true";
				else return "false";
				break;
			default:
				return strval($var);
				break;
		}
	}
	public static function toDouble($var)
	{
		return doubleval($var);
	}
	public static function toFloat($var)
	{
		return floatval($var);
	}
}
?>
