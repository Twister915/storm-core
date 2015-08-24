<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/19/2015
 * Time: 4:44 PM
 */

namespace StormNetwork\StormCore\Http;


use pocketmine\Thread;

class StormHttpCallThreaded extends Thread {
    /**
     * @var string
     */
    private $route;
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $method;
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var array
     */
    private $customData;

    /**
     * StormHttpCallThreaded constructor.
     * @param string $route
     * @param array $data
     * @param string $method
     * @param array $customData
     * @param callable $callback
     */
    public function __construct($route, array $data, $method, array $customData, callable $callback) {
        $this->route = $route;
        $this->data = $data;
        $this->method = $method;
        $this->customData = $customData;
        $this->callback = $callback;
    }


    public function run() {
        $ch = curl_init(StormClient::$apiHost . "/v1/" . $this->route);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($ch, CURLOPT_HEADER, ["Authorization: Bearer " . StormClient::$apiKey]);
        $cmh = curl_multi_init();
        curl_multi_add_handle($cmh, $ch);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $callback = $this->callback;
        $callback(["response" => json_decode($result, false), "code" => $code, "customData" => $this->customData]);
    }
}