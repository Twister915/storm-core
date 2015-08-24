<?php

namespace StormNetwork\StormCore;


use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use StormNetwork\StormCore\Player\PlayerManager;

final class StormCore extends PluginBase {
    private static $instance = null;

    /**
     * @var PlayerManager
     */
    private $playerManager;

    /**
     * @return StormCore
     */
    public static function getInstance() {
        return self::$instance;
    }

    /**
     * @param $event Event
     */
    public static function callEvent($event) {
        self::getInstance()->getServer()->getPluginManager()->callEvent($event);
    }

    public static function registerListener(Listener $listener) {
        $plugin = self::getInstance();
        $plugin->getServer()->getPluginManager()->registerEvents($listener, $plugin);
    }

    public function onEnable() {
        self::$instance = $this;

        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->reloadConfig();
        StormClient::setApiKey($this->getConfig()->get("api-key"));
        StormClient::setApiHost($this->getConfig()->get("api-host"));
    }

    /**
     * @return PlayerManager
     */
    public function getPlayerManager() {
        return $this->playerManager;
    }
}