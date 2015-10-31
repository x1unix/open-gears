<?php

/**
 * Class EssentialRouter
 *
 * @author Denis Sedchenko
 * @date 31.10.2015
 * @version 1.0.1
 *
 * Provides a base routing (controller/activity)
 * Can be used in Router as secondary routing source
 */
class EssentialRouter
{
    /**
     * Array key with string source
     *
     * @var string Hook
     */
    public static $Hook = "path";

    /**
     * Default controller
     *
     * @var string
     */
    public static $DefaultController = DEFAULT_CONTROLLER;

    /**
     * Default activity
     *
     * @var string
     */
    public static $DefaultActivity = DEFAULT_ACTIVITY;

    /**
     * Values delimiter
     *
     * @var string
     */
    public static $Delimiter = "/";

    /**
     * Parse a array value
     *
     * @param array $source
     * @return array
     */
    private static function Parse($source) {
        $currentController = self::$DefaultController;
        $currentActivity = self::$DefaultActivity;

        $path = self::$DefaultController.'/'.self::$DefaultActivity;
        if(isset($source[self::$Hook])) $path = $source[self::$Hook];
        $path = explode(self::$Delimiter, $path);

        $currentController = $path[0];
        if(isset($path[1]) && strlen($path[1]) > 0) $currentActivity = $path[1];

        // Parsing additional arguments
        $rest = array();
        if(count($path) > 2) $rest = array_slice($path,2);


        return array("controller"=>$currentController,"activity"=>$currentActivity,"arguments"=>$rest);
    }

    /**
     * Load router from specified array
     *
     * @param array $source
     */
    public static function Get($source) {
        $_request = self::Parse($source);

        // Register some values in system scope
        System::$Scope += $_request;

        // Fus Ro Dah!
        System::Call($_request['controller'],$_request['activity']);
    }
}