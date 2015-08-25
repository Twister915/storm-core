<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 11:20 PM
 */

namespace StormNetwork\StormCore\Frontend\Player;


use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use StormNetwork\StormCore\Command\BasicStormCommandException;
use StormNetwork\StormCore\Command\StormCommand;
use StormNetwork\StormCore\Http\StormClient;
use StormNetwork\StormCore\StormFormatter;

class NetDeOpCommand extends StormCommand {
    protected function handleCommandUnspecific(CommandSender $sender, array $args) {
        if (!$sender->hasPermission("storm.netop")) throw new BasicStormCommandException("You do not have permission for this command!");
        if (sizeof($args) < 1) throw new BasicStormCommandException("You did not supply a player!");
        $player = $args[0];
        StormClient::sendData('POST', ["username" => $player, 'op' => false], 'users/op', $sender, function ($pl, $resp) {
            if ($resp->code != 200) $pl->sendMessage(TextFormat::RED . $resp->response);
            else $pl->sendMessage(StormFormatter::withPath('deopped-player')->with("player", $resp->response->Username)->get());
        });
    }
}