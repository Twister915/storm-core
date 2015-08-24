<?php

namespace StormNetwork\StormCore;


use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use StormNetwork\StormCore\Frontend\Player\AuthenticationListener;
use StormNetwork\StormCore\Player\PlayerManager;
use StormNetwork\StormCore\Http\StormClient;

final class StormCore extends PluginBase {
    private static $instance = null;

    /**
     * @var PlayerManager
     */
    private $playerManager;

    /**
     * @var Config
     */
    private $formats;

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
        $this->playerManager = new PlayerManager();

        $this->writeDefault("formats.yml");
        $this->formats = new Config($this->getDataFolder() . "formats.yml", Config::YAML);
        StormFormatter::loadPrefix();

        self::registerListener(new AuthenticationListener());
    }

    private function writeDefault($name) {
        if(!file_exists($this->getDataFolder() . $name)){
            $this->saveResource($name, false);
        }
    }

    /**
     * @return Config
     */
    public function getFormats() {
        return $this->formats;
    }

    /**
     * @return PlayerManager
     */
    public function getPlayerManager() {
        return $this->playerManager;
    }
}