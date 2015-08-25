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
     * @var StormRequestEncapsulation[]
     */
    static $running = array();

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
        $id = rand(0, 1000);
        $thread = (new StormHttpCallThreaded($method, $data, self::$apiHost . '/' . $route, $id));
        self::$running[$id] = new StormRequestEncapsulation($callback, $caller);
        $thread->schedule();
    }

    public static function finished($id, $result) {
        $thing = self::$running[$id];
        unset(self::$running[$id]);
        $cb = $thing->getCallable();
        $cb($thing->getCaller(), $result);
    }

    public static function teaseError($obj) {
        if (is_string($obj)) {
            return $obj;
        }
        if (isset($obj->error)) return $obj->error;
        return 'error';
    }
}