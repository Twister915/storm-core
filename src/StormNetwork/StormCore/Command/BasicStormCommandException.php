<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 4:41 PM
 */

namespace StormNetwork\StormCore\Command;


use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use StormNetwork\StormCore\Command\StormCommandException;

class BasicStormCommandException extends StormCommandException {
    /**
     * @var string
     */
    private $msg;

    /**
     * BasicStormCommandException constructor.
     * @param $msg string
     */
    public function __construct($msg) { $this->msg = $msg; }

    /**
     * @return string
     */
    public function getMsg() {
        return $this->msg;
    }

    public function messageFor(CommandSender $player) {
        return TextFormat::RED . $this->msg;
    }
}