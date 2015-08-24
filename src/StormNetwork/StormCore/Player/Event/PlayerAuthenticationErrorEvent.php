<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/19/2015
 * Time: 11:50 PM
 */

namespace StormNetwork\StormCore\Player\Event;

use pocketmine\event\Event;
use StormNetwork\StormCore\Player\StormPlayer;

class PlayerAuthenticationErrorEvent extends Event {
    public static $handlerList = null;

    /**
     * @var StormPlayer
     */
    private $player;

    /**
     * @var mixed
     */
    private $error;

    /**
     * PlayerAuthenticationErrorEvent constructor.
     * @param StormPlayer $player
     * @param mixed $error
     */
    public function __construct(StormPlayer $player, $error) {
        $this->player = $player;
        $this->error = $error;
    }

    /**
     * @return StormPlayer
     */
    public function getPlayer() {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getError() {
        return $this->error;
    }
}