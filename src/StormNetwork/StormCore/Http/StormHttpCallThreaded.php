<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/19/2015
 * Time: 4:44 PM
 */

namespace StormNetwork\StormCore\Http;


use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class StormHttpCallThreaded extends AsyncTask {
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
     * @var mixed
     */
    private $caller;

    private $result;

    /**
     * StormHttpCallThreaded constructor.
     * @param string $route
     * @param array $data
     * @param string $method
     * @param mixed $caller
     * @param callable $callback
     */
    public function __construct($route, array $data, $method, $caller, callable $callback) {
        $this->route = $route;
        $this->data = $data;
        $this->method = $method;
        $this->caller = $caller;
        $this->callback = $callback;
    }

    /**
     * Actions to execute when run
     *
     * @return void
     */
    public function onRun() {
        $ch = curl_init(StormClient::$apiHost . "/v1/" . $this->route);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . StormClient::$apiKey]);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->result = (object)["response" => json_decode($result, false), "code" => $code];
    }

    /**
     * Actions to execute when completed (on main thread)
     * Implement this if you want to handle the data in your AsyncTask after it has been processed
     *
     * @param Server $server
     *
     * @return void
     */
    public function onCompletion(Server $server) {
        $cb = $this->callback;
        $cb($this->caller, $this->result);
    }

}