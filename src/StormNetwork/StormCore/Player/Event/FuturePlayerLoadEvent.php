<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 2:31 PM
 */

namespace StormNetwork\StormCore\Player\Event;


use pocketmine\event\Event;
use StormNetwork\StormCore\Player\StormFuturePlayer;
use StormNetwork\StormCore\Player\StormOfflinePlayer;

class FuturePlayerLoadEvent extends Event {
    public static $handlerList = null;

    /**
     * @var StormOfflinePlayer
     */
    private $player;

    /**
     * @var StormFuturePlayer
     */
    private $futurePlayer;

    /**
     * PlayerAuthenticateEvent constructor.
     * @param StormOfflinePlayer $player
     * @param StormFuturePlayer $futurePlayer
     */
    public function __construct(StormOfflinePlayer $player, StormFuturePlayer $futurePlayer) { $this->player = $player; $this->futurePlayer = $futurePlayer; }


    /**
     * @return StormOfflinePlayer
     */
    public function getPlayer() {
        return $this->player;
    }

    /**
     * @return StormFuturePlayer
     */
    public function getFuturePlayer() {
        return $this->futurePlayer;
    }
}