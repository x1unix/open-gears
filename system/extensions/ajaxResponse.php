<?php
  /**
   * OpenGears AJAX Response Module
   *
   * Provides an AJAX response template
   * @version 0.8
   * @package opengears
   * @author Denis Sedchenko [sedchenko.in.ua]
   */

Extensions::request("convert");

class AJAXResponse
{
	public static function error($desc)
	{
		return json_encode(array("success"=>false,"response"=>$desc));
	}
	public static function reply($arr,$success=true)
	{
		$r = array("success"=>$success,"response"=>$arr);
		return json_encode($r);
	}
}
?>