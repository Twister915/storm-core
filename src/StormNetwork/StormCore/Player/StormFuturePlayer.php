<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 2:03 PM
 */

namespace StormNetwork\StormCore\Player;

use StormNetwork\StormCore\Player\Event\FuturePlayerLoadEvent;
use StormNetwork\StormCore\StormCore;

class StormFuturePlayer {
    /**
     * @var StormOfflinePlayer
     */
    private $represent;

    public function isLoaded() {
        return $this->represent != null;
    }

    public function dropIn($player) {
        $this->represent = $player;
        StormCore::callEvent(new FuturePlayerLoadEvent($player, $this));
    }

    public function __call($name, $arguments) {
        if (!$this->isLoaded()) throw new StormPlayerNotLoadedException();
        $method = new \ReflectionMethod("StormNetwork\\StormCore\\Player\\StormOfflinePlayer", $name);
        return $method->invoke($this->represent, $arguments);
    }
}