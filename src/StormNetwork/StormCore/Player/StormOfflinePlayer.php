<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 2:06 PM
 */

namespace StormNetwork\StormCore\Player;

use StormNetwork\StormCore\Http\StormClient;
use StormNetwork\StormCore\StormCore;

class StormOfflinePlayer { //todo permissions and roles
    /**
     * @var string
     */
    protected $username;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;
    
    /**
     * @var StormPunishment[]
     */
    protected $punishments;

    /**
     * @var string[]
     */
    protected $meta;

    /**
     * @var boolean
     */
    protected $operator;

    /**
     * @var StormSession[]
     */
    protected $sessions;

    /**
     * @param $json string
     * @return StormOfflinePlayer
     */
    public static function withJson($json) {
        $pl = new StormOfflinePlayer();
        $jsData = json_decode($json, true);
        $pl->loadJson($jsData);
        return $pl;
    }

    protected function loadJson($userData) {
        $this
            ->setUsername($userData->Username)
            ->setId($userData->ID)
            ->setEmail($userData->Email)
            ->setMeta($userData->Metadata)
            ->setSessions($userData->Sessions)
            ->setOperator($userData->Operator);
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }


    /**
     * @return StormPunishment[]
     */
    public function getPunishments() {
        return $this->punishments;
    }

    /**
     * @param string $username
     * @return StormOfflinePlayer
     */
    protected function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * @param $meta object[]
     * @return $this StormOfflinePlayer
     */
    protected function setMeta($meta) {
        $this->meta = array();
        if ($meta == null) return $this;
        foreach ($meta as $metaEntry) {
            $this->meta[$metaEntry->Key] = $metaEntry->Value;
        }
        return $this;
    }

    public function updatePunishments() {
        StormClient::sendData('GET', [], 'punishments/targeted/auth/' . $this->id, $this, function ($uThis, $resp) {
            if ($resp->code != 200) return;
            $uThis->setPunishmentsJson($resp->response);
        });
    }

    /**
     * @param object[] $punishmentsJson
     * @return StormOfflinePlayer
     */
    protected function setPunishmentsJson($punishmentsJson) {
        $punishments = array();
        $playerManager = StormCore::getInstance()->getPlayerManager();
        foreach ($punishmentsJson as $punishment) {
            $punishments[] = new StormPunishment(
                $punishment->Reason,
                $playerManager->getPlayerByJSON($punishment->Punisher),
                $playerManager->getPlayerByJSON($punishment->Target),
                $punishment->Type,
                $punishment->ExpirationDate
            );
        }
        $this->punishments = $punishments;
        return $this;
    }

    /**
     * @param int $id
     * @return StormOfflinePlayer
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $email
     * @return StormOfflinePlayer
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @param mixed[] $sessions
     * @return StormOfflinePlayer
     */
    public function setSessions($sessions) {
        $this->sessions = array();
        foreach ($sessions as $session) {
            $this->sessions[] = new StormSession($this, $session->Address);
        }
        return $this;
    }

    /**
     * @param boolean $operator
     * @return StormOfflinePlayer
     */
    public function setOperator($operator) {
        $this->operator = $operator;
        return $this;
    }


    /**
     * @return \string[]
     */
    public function getMeta() {
        return $this->meta;
    }
}