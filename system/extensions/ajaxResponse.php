<?php
  /**
   * OpenGears AJAX Response Module
   *
   * Provides an AJAX response template
   * @version 1.0.0
   * @package com.opengears.ext.ajax
   * @author Denis Sedchenko [sedchenko.in.ua]
   */

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
