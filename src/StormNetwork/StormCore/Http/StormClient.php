<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/18/2015
 * Time: 5:21 PM
 */

namespace StormNetwork\StormCore\Http;


use pocketmine\Server;
use StormNetwork\StormCore\StormCore;

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
     * @param $customData array
     * @throws StormClientException
     */
    public static function sendData($method, $data, $route, $customData, $callback) {
        $thread = new StormHttpCallThreaded($method, $data, $route, $customData, function ($result) use($callback) {
            StormCore::getInstance()->getServer()->getScheduler()->scheduleTask(new StormCallbackTask($callback, $result));
        });
        Server::getInstance()->getScheduler()->scheduleAsyncTask($thread);
    }
}