<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/19/2015
 * Time: 11:48 PM
 */

namespace StormNetwork\StormCore\Player\Event;


use StormNetwork\StormCore\Player\StormPlayer;

class PlayerRegisterEvent {
    public static $handlerList = null;

    /**
     * @var StormPlayer
     */
    private $player;

    /**
     * PlayerRegisterEvent constructor.
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