<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 8:07 PM
 */

namespace StormNetwork\StormCore\Frontend\Player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use StormNetwork\StormCore\Http\StormClient;

class PunishmentListener implements Listener {
    public function onPlayerLogin(PlayerLoginEvent $event) {
        StormClient::sendData('POST', ['username' => $event->getPlayer()->getName(), 'ip' => $event->getPlayer()->getAddress()], 'punishments/targeted/noAuth', $this, function ($ca, $resp) {
            if ($resp->code != 200) return;
            $punishments = $resp->response;
            foreach ($punishments as $punishment) {
                $ca->handlePunishment($punishment);
            }
        });
    }

    private function handlePunishment($punishment) {
        //todo implement punishment handler
    }
}