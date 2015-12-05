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
   echo "<h1>404</h1> <p>Requested controller doesn't exists</p>";
}
catch(ViewNotFoundException $e){
  echo "<b>System error<b/>: Requested view doesn't exists";
}
catch(ActivityNotFoundException $e){
  echo "<b>404</b>: Requested method doens't appear in requested controller";
}
catch(MySQLConnectException $e){
  echo "<b>Database connection error</b>: Failed to connect to MYSQL Server, try to check AP data in configuration file or server availability";
}
catch (Exception $e) {
  echo "<b>Uncatchable system exception</b>: $e";
}

?>
