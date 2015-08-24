<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 4:40 PM
 */

namespace StormNetwork\StormCore\Command;


use pocketmine\command\CommandSender;
use pocketmine\Player;

abstract class StormCommandException extends \Exception{
    public abstract function messageFor(CommandSender $player);
}