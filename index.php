<?php
// OPEN-GEARS FRAMEWORK [0.5.3]
// 2015 © Denis Sedchenko

require_once('config.php');

if(!defined("IFCONFIG")) die("<b>OpenGears Load Error</b><br />Failed to load configuration file, check if config.php exists and if 'IFCONFIG' defined.");

require_once(CORE.'kernel.php');
require_once(CORE.'controller.php');
require_once(DRIVERS.'db.php');

$currentController = DEFAULT_CONTROLLER;
$currentActivity = DEFAULT_ACTIVITY;
  
$path = DEFAULT_CONTROLLER.'/'.DEFAULT_ACTIVITY;
if(isset($_GET['path'])) $path = $_GET['path'];
$path = explode("/", $path);

$currentController = $path[0];
if(isset($path[1]) && strlen($path[1]) > 0) $currentActivity = $path[1];

header('X-Based-On: OpenGears/'.System::$Version);

try {
  System::Call($currentController,$currentActivity); 
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