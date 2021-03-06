<?php

namespace StormNetwork\StormCore;


use pocketmine\command\PluginCommand;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use StormNetwork\StormCore\Command\CommandRegistration;
use StormNetwork\StormCore\Command\StormCommand;
use StormNetwork\StormCore\Frontend\Player\AuthenticationListener;
use StormNetwork\StormCore\Frontend\Player\LoginCommand;
use StormNetwork\StormCore\Frontend\Player\NetDeOpCommand;
use StormNetwork\StormCore\Frontend\Player\NetOpCommand;
use StormNetwork\StormCore\Frontend\Player\PunishmentListener;
use StormNetwork\StormCore\Frontend\Player\RegisterCommand;
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

    public static function log($message) {
        self::getInstance()->getLogger()->info($message);
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
        self::registerListener(new PunishmentListener());

        $this->registerStormCommand("login", new LoginCommand());
        $this->registerStormCommand("register", new RegisterCommand());
        $this->registerStormCommand("netop", new NetOpCommand());
        $this->registerStormCommand("netdeop", new NetDeOpCommand());

        $var = "yeah";
        StormClient::sendData('GET', [], 'ping', $var, function($t, $r) {
            if ($r->code != 200) Server::getInstance()->shutdown();
        });
    }

    public function onDisable() {
        foreach ($this->playerManager->getPlayers() as $player) {
            $player->logout();
        }
    }


    public function registerStormCommand($name, StormCommand $command) {
        $this->registerCommand(new CommandRegistration($name, $command));
    }

    public function registerCommand(CommandRegistration $stormCommand) {
        $cmd = new PluginCommand($stormCommand->getName(), $this);
        $cmd->setExecutor($stormCommand->getCommandHandler());
        $this->getServer()->getCommandMap()->register($this->getDescription()->getName(), $cmd);
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