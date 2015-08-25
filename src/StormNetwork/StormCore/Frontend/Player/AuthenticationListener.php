<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 3:54 PM
 */

namespace StormNetwork\StormCore\Frontend\Player;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use StormNetwork\StormCore\Player\Event\PlayerAuthenticationErrorEvent;
use StormNetwork\StormCore\Player\Event\PlayerAuthenticateEvent;
use StormNetwork\StormCore\StormCore;
use StormNetwork\StormCore\StormFormatter;

class AuthenticationListener implements Listener {
    private $lastMsg = array();

    /**
     * @param $player Player
     * @return bool
     */
    private function playerIsAuthenticated($player) {
        return StormCore::getInstance()->getPlayerManager()->getPlayerByPlayer($player)->isAuthenticated();
    }

    /**
     * @param $event PlayerEvent|BlockEvent
     */
    private function handleAuthenticatedEvent($event) {
        if (!$this->playerIsAuthenticated($event->getPlayer())) {
            $event->setCancelled(true);
            $uuid = $event->getPlayer()->getUniqueId();
            if (isset($this->lastMsg[$uuid]) && time() - $this->lastMsg[$uuid] < 5)
                return;
            $this->lastMsg[$uuid] = time();
            $event->getPlayer()->sendMessage(StormFormatter::withPath("player-need-auth")->get());
        }
    }

    /**
     * @param PlayerMoveEvent $event
     */
    public function onPlayerMove(PlayerMoveEvent $event) {
        $this->handleAuthenticatedEvent($event);
    }

    public function onPlayerChat(PlayerChatEvent $event) {
        $this->handleAuthenticatedEvent($event);
    }

    public function onPlayerCommand(PlayerCommandPreprocessEvent $event) {
//        $this->handleAuthenticatedEvent($event);
    }

    public function onPlayerInteract(PlayerInteractEvent $event) {
        $this->handleAuthenticatedEvent($event);
    }

    public function onPlayerPlaceBlock(BlockPlaceEvent $event) {
        $this->handleAuthenticatedEvent($event);
    }

    public function onPlayerBreakBlock(BlockBreakEvent $event) {
        $this->handleAuthenticatedEvent($event);
    }

    public function onPlayerJoin(PlayerJoinEvent $event) {
        $event->setJoinMessage(null);
    }

    public function onPlayerLeave(PlayerQuitEvent $event) {
        if (!$this->playerIsAuthenticated($event->getPlayer())) $event->setQuitMessage(null);
        unset($this->lastMsg[$event->getPlayer()->getUniqueId()]);
    }

    public function onPlayerDamage(EntityDamageEvent $event) {
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;
        if (!$this->playerIsAuthenticated($player)) $event->setCancelled(true);
    }

    public function onPlayerAuthenticate(PlayerAuthenticateEvent $event) {
        $player = $event->getPlayer()->getPocketMinePlayer();
        StormCore::getInstance()->getServer()->broadcastMessage(StormFormatter::withPath("player-join")->with("player", $player->getName())->withPrefix(false)->get());
        $player->setOp($event->getPlayer()->isOperator());
        $player->sendMessage(StormFormatter::withPath("player-login")->get());
    }

    public function onPlayerAuthenticateError(PlayerAuthenticationErrorEvent $event) {
        $event->getPlayer()->getPocketMinePlayer()->sendMessage(StormFormatter::withPath("player-auth-fail")->with("message", $event->getError())->get());
    }
}