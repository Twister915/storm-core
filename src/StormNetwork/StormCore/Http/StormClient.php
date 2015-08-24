<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/18/2015
 * Time: 5:21 PM
 */

namespace StormNetwork\StormCore\Http;


use pocketmine\Server;


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
    public static function sendData($method, $data, $route, $caller, $callback) {
        $thread = new StormHttpCallThreaded($method, $data, self::$apiHost . '/' . $route, $caller, $callback);
        Server::getInstance()->getScheduler()->scheduleAsyncTask($thread);
    }
}