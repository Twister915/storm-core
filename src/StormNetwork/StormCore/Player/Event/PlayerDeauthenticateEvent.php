<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/19/2015
 * Time: 11:47 PM
 */

namespace StormNetwork\StormCore\Player\Event;


use pocketmine\event\Event;
use StormNetwork\StormCore\Player\StormPlayer;

class PlayerDeauthenticateEvent extends Event {
    public static $handlerList = null;

    /**
     * @var StormPlayer
     */
    private $player;

    /**
     * PlayerDeauthenticateEvent constructor.
     * @param StormPlayer $player
     */
    public function __construct(StormPlayer $player) { $this->player = $player; }

    /**
     * @return StormPlayer
     */
    public function getPlayer() {
        return $this->player;
    }
}