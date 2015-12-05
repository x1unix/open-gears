<?php
// OPEN-GEARS FRAMEWORK [1.0] (MAGURO)
// 2015 © Denis Sedchenko

include('config.php');

if(!defined("IFCONFIG")) die("<b>OpenGears Load Error</b><br />Failed to load configuration file, check if config.php exists and if 'IFCONFIG' defined.");

include(CORE.'kernel.php');

System::Init();

// Load System Extensions
Extensions::load(
    array('base','convert','ajaxResponse','baseRouter')
);


try {
  EssentialRouter::Get($_GET);
}
catch(ControllerNotFoundException $e){
    System::$Scope["error"] = $e;
    System::Call("error");
}
catch(Exception $e){
    System::$Scope["error"] = $e;
    System::Call("error","ServerError");
}

?>
