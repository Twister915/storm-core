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

class LoginCommand extends StormCommand {
    protected function handleCommandPlayer(StormPlayer $sender, array $args) {
        if ($sender->isAuthenticated()) throw new BasicStormCommandException("You are already authenticated!");
        if (sizeof($args) < 1) throw new BasicStormCommandException("You must supply a password!");
        $sender->logUserIn($args[0]);
        $sender->getPocketMinePlayer()->sendMessage(StormFormatter::withPath("awaiting-auth-callback")->get());
    }
}