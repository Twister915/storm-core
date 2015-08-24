<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/18/2015
 * Time: 5:04 PM
 */

namespace StormNetwork\StormCore\Player;


use pocketmine\Player;
use StormNetwork\StormCore\Player\Event\PlayerAuthenticateEvent;
use StormNetwork\StormCore\Player\Event\PlayerAuthenticationErrorEvent;
use StormNetwork\StormCore\Player\Event\PlayerDeauthenticateEvent;
use StormNetwork\StormCore\Http\StormClient;
use StormNetwork\StormCore\StormCore;

class StormPlayer extends StormOfflinePlayer {

    /**
     * @var StormSession
     */
    protected $session;

    /**
     * @var bool
     */
    protected $authenticated;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @return mixed
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * @return boolean
     */
    public function isAuthenticated() {
        return $this->authenticated;
    }

    /**
     * @param mixed $sessionJson
     * @return StormPlayer
     */
    protected function setSession($sessionJson) {
        if ($sessionJson == null) {
            $this->session = null;
            return $this;
        }
        $this->session = new StormSession($this, $sessionJson->Address);
        return $this;
    }

    /**
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @return Player
     */
    public function getPocketMinePlayer() {
        return StormCore::getInstance()->getServer()->getPlayerExact($this->username);
    }

    /**
     * @param boolean $authenticated
     * @return StormPlayer
     */
    public function setAuthenticated($authenticated) {
        $this->authenticated = $authenticated;
        if ($authenticated)
            StormCore::callEvent(new PlayerAuthenticateEvent($this));
        else
            StormCore::callEvent(new PlayerDeauthenticateEvent($this));

        return $this;
    }

    /**
     * @param $player Player
     * @return StormPlayer
     */
    public static function withPlayer($player) {
        $pl = new StormPlayer();
        $pl->username = $player->getName();
        $pl->authenticated = false;
        $pl->ip = $player->getAddress();
        return $pl;
    }

    protected function loadJson($data) {
        parent::loadJson($data->user);
        $this
            ->setAuthenticated(true)
            ->setSession($data->session);
        StormCore::log("Auth'd = " . $this->isAuthenticated());
    }

    // REAL FUNCTIONS
    /**
     * @param $password string
     */
    public function logUserIn($password) {
        StormClient::sendData("POST", ["username" => $this->getUsername(), "password" => $password, "ip" => $this->getIp()], "users/login", $this, function($uThis, $result) {
            $uThis->handleAuthCallback($result);
        });
    }

    public function register($password, $email) {
        StormClient::sendData("POST", ["username" => $this->getUsername(), "password" => $password, "email" => $email], "users/register", $this, function ($uThis, $result) {
            $uThis->handleAuthCallback($result);
        });
    }

    public function logout() {
        if (!$this->isAuthenticated()) return;
        $this->setAuthenticated(false);
        $this->setSession(null);
        StormClient::sendData("POST", ["userId" => $this->id], "users/logout", [], function ($result) {});
    }

    public function handleAuthCallback($result) {
        if ($result->code != 200) {
            $this->authenticated = false;
            StormCore::callEvent(new PlayerAuthenticationErrorEvent($this, StormClient::teaseError($result->response)));
            return;
        }

        $responseData = $result->response;
        $this->loadJson($responseData);
    }
}