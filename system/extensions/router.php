<?php

/**
 * OpenGears Advanced Router
 *
 * @version 1.0
 * @author Denis Sedchenko
 * @date 31.10.2015
 *
 */
final class Router
{
    public static $routes = array();
    private static $params = array();
    public static $requestedUrl = '';

    /**
     * Добавить маршрут
     * Add Route
     */
    public static function AddRoute($route, $destination=null) {
        if ($destination != null && !is_array($route)) {
            $route = array($route => $destination);
        }
        self::$routes = array_merge(self::$routes, $route);
    }

    /**
     * Разделить переданный URL на компоненты
     * Split URL to elements
     */
    public static function SplitUrl($url) {
        return preg_split('/\//', $url, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Текущий обработанный URL
     * Current URL
     */
    public static function GetCurrentUrl() {
        return (self::$requestedUrl?:'/');
    }

    /**
     * Обработка переданного URL
     * Dispatch received URL
     */
    public static function Dispatch($requestedUrl = null) {

        // Если URL не передан, берем его из REQUEST_URI
        if ($requestedUrl === null) {
            $uri = reset(explode('?', $_SERVER["REQUEST_URI"]));
            $requestedUrl = urldecode(rtrim($uri, '/'));
        }

        self::$requestedUrl = $requestedUrl;

        // если URL и маршрут полностью совпадают
        if (isset(self::$routes[$requestedUrl])) {
            self::$params = self::SplitUrl(self::$routes[$requestedUrl]);
            return self::ExecuteAction();
        }

        foreach (self::$routes as $route => $uri) {
            // Заменяем wildcards на рег. выражения
            if (strpos($route, ':') !== false) {
                $route = str_replace(':any', '(.+)', str_replace(':num', '([0-9]+)', $route));
            }

            if (preg_match('#^'.$route.'$#', $requestedUrl)) {
                if (strpos($uri, '$') !== false && strpos($route, '(') !== false) {
                    $uri = preg_replace('#^'.$route.'$#', $uri, $requestedUrl);
                }
                self::$params = self::SplitUrl($uri);

                break; // URL обработан!
            }
        }
        return self::ExecuteAction();
    }

    /**
     * Запуск соответствующего действия/экшена/метода контроллера
     * Execute controller or action
     */
    public static function ExecuteAction() {
        $controller = isset(self::$params[0]) ? self::$params[0]: DEFAULT_CONTROLLER;
        $action = isset(self::$params[1]) ? self::$params[1]: DEFAULT_ACTIVITY;
        $params = array_slice(self::$params, 2);

        // Register some values in system scope
        System::$Scope += array("controller"=>$controller,"activity"=>$action,"arguments"=>$params);

        // Fus Ro Dah!
        return System::Call($controller,$action);

    }
}