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
    private $url;
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $method;

    private $result;
    /**
     * @var string
     */
    private $key;

    private $id;

    private $scheduleCount = 0;
    private $shouldReschdule = false;

    /**
     * StormHttpCallThreaded constructor.
     * @param string $url
     * @param array $data
     * @param string $method
     */
    public function __construct($method, $data, $url, $id) {
        $this->url = $url;
        $this->data = $data;
        $this->method = $method;
        $this->id = $id;
        $this->key = StormClient::$apiKey;
    }

    /**
     * Actions to execute when run
     *
     * @return void
     */
    public function onRun() {
        $ch = curl_init($this->url);
        if ($this->method !== "GET") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $this->key]);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
        $header_size = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
        $body = substr($result, $header_size);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error !== "") {
            $this->shouldReschdule = true;
            return;
        }

        $this->result = (object)["response" => strpos($type, 'json') !== false ? json_decode($body) : $body, "code" => (int)$code];
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
        if ($this->shouldReschdule) {
            $this->schedule();
            return;
        }
        StormClient::finished($this->id, $this->result);
    }

    public function schedule() {
        $this->scheduleCount++;
        if ($this->scheduleCount > 10) throw new StormClientException("10 failed attempts to make request!");
        Server::getInstance()->getScheduler()->scheduleAsyncTask($this);
    }

}