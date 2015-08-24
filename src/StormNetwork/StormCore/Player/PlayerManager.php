<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/18/2015
 * Time: 4:51 PM
 */

namespace StormNetwork\StormCore\Player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use StormNetwork\StormCore\Http\StormClient;
use StormNetwork\StormCore\StormCore;

class PlayerManager implements Listener {
    /**
     * @var StormPlayer[]
     */
    private $players = array();

    public function __construct() {
        foreach (StormCore::getInstance()->getServer()->getOnlinePlayers() as $player) {
            $this->initPlayer($player);
        }
        StormCore::registerListener($this);
    }

    /**
     * @param $player Player
     */
    private function initPlayer($player) {
        StormCore::log($player->getUniqueId() . " init");
        $this->players[] = StormPlayer::withPlayer($player);
    }

    /**
     * @param $player Player
     */
    private function clearPlayer($player) {
        $this->getPlayerByPlayer($player)->logout();
        unset($this->players[$player->getUniqueId()]);
    }

    /**
     * @param PlayerPreLoginEvent $event
     *
     * @priority LOWEST
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event) {
        $this->initPlayer($event->getPlayer());
    }

    /**
     * @param PlayerQuitEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerLogout(PlayerQuitEvent $event) {
        $this->clearPlayer($event->getPlayer());
    }

    /**
     * @param $name string
     * @return null|StormPlayer
     */
    public function getPlayerByName($name) {
        return $this->getPlayerByNameWithFind($name, false);
    }

    /**
     * @param $name string
     * @param $find boolean
     * @return null|StormPlayer|StormFuturePlayer
     */
    public function getPlayerByNameWithFind($name, $find) {
        foreach ($this->players as $pl) {
            if ($pl->getUsername() == $name) return $pl;
        }
        if ($find) {
            $futurePlayer = new StormFuturePlayer();
            StormClient::sendData("GET", [], "users/get/name/" . $name, [], function ($result) use($futurePlayer) {
                $futurePlayer->dropIn(StormOfflinePlayer::withJson($result->response));
            });
            return $futurePlayer;
        }
        return null;
    }

    /**
     * @param $player Player
     * @return StormPlayer
     */
    public function getPlayerByPlayer($player) {
        foreach ($this->players as $pl) {
            if ($pl->getUsername() == $player->getName()) return $pl;
        }
        return null;
    }

    /**
     * @param $json object
     * @return null|StormPlayer|StormFuturePlayer
     */
    public function getPlayerByJSON($json) {
        return $this->getPlayerByID($json->id, true);
    }

    /**
     * @param $id int
     * @param $find boolean
     * @return null|StormPlayer|StormFuturePlayer
     */
    public function getPlayerByID($id, $find) {
        foreach ($this->players as $pl) {
            if ($pl->getId() == $id) return $pl;
        }
        if ($find) {
            $futurePlayer = new StormFuturePlayer();
            StormClient::sendData("GET", [], "users/get/id/" . $id, [], function ($result) use($futurePlayer) {
                $futurePlayer->dropIn(StormOfflinePlayer::withJson($result->response));
            });
            return $futurePlayer;
        }
        return null;
    }
}