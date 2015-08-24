<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 3:54 PM
 */

namespace StormNetwork\StormCore\Command;


use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use StormNetwork\StormCore\Player\StormPlayer;
use StormNetwork\StormCore\StormCore;


class StormCommand implements CommandExecutor {
    /**
     * @var CommandRegistration[]
     */
    private $subCommands = array();

    public final function addSubCommand($name, $command) {
        $subCommands[] = new CommandRegistration($name, $command);
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param string[] $args
     *
     * @return boolean
     */
    public final function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        $this->onCommand0($sender, $args);
        return true;
    }

    public final function onCommand0(CommandSender $sender, array $args) {
        //do sub command handling
        if (sizeof($args) > 0) {
            foreach ($this->subCommands as $subCommand) {
                if ($subCommand->getName() === $args[0]) {
                    $subCommand->getCommandHandler()->onCommand0($sender, array_slice($args, 1));
                    return;
                }
            }
        }
        try {
            $this->handleCommandUnspecific($sender, $args);
            if ($sender instanceof Player) {
                $this->handleCommandPlayer(StormCore::getInstance()->getPlayerManager()->getPlayerByPlayer($sender), $args);
            }
            if ($sender instanceof ConsoleCommandSender) {
                $this->handleCommandConsole($sender, $args);
            }
        } catch (StormCommandException $e) {
            $sender->sendMessage($e->messageFor($sender));
        }
    }

    protected function handleCommandUnspecific(CommandSender $sender, array $args) {}
    protected function handleCommandPlayer(StormPlayer $sender, array $args) {}
    protected function handleCommandConsole(ConsoleCommandSender $sender, array $args) {}
}