<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 4:38 PM
 */

namespace StormNetwork\StormCore\Frontend\Player;


use StormNetwork\StormCore\Command\BasicStormCommandException;
use StormNetwork\StormCore\Command\StormCommand;
use StormNetwork\StormCore\Player\StormPlayer;
use StormNetwork\StormCore\StormFormatter;

class RegisterCommand extends StormCommand {
    protected function handleCommandPlayer(StormPlayer $sender, array $args) {
        if ($sender->isAuthenticated()) throw new BasicStormCommandException("You are already authenticated!");
        if (sizeof($args) < 3 || $args[1] !== $args[2]) throw new BasicStormCommandException("Correct usage: [email] [password] [password again]");
        $sender->register($args[1], $args[0]);
        $sender->getPocketMinePlayer()->sendMessage(StormFormatter::withPath("awaiting-register-callback")->get());
    }
}