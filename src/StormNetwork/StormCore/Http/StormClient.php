<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/18/2015
 * Time: 5:21 PM
 */

namespace StormNetwork\StormCore\Http;

class StormClient {
    /**
     * @var string
     */
    static $apiKey;

    /**
     * @var string
     */
    static $apiHost;

    /**
     * @param $key string
     */
    public static function setApiKey($key) {
        self::$apiKey = $key;
    }

    public static function setApiHost($apiHost) {
        self::$apiHost = $apiHost;
    }

    /**
     * @param $method string
     * @param $data array
     * @param $route string
     * @param $callback callable
     * @param $caller mixed
     * @throws StormClientException
     */
    public static function sendData($method, $data, $route, &$caller, $callback) {
        (new StormHttpCallThreaded($method, $data, self::$apiHost . '/' . $route, $caller, $callback))->schedule();
    }

    public static function teaseError($obj) {
        if (is_string($obj)) {
            return $obj;
        }
        if (isset($obj->error)) return $obj->error;
        return 'error';
    }
}