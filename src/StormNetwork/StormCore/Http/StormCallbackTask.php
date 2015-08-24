<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 1:54 PM
 */

namespace StormNetwork\StormCore\Http;


use pocketmine\scheduler\Task;

class StormCallbackTask extends Task {
    /**
     * @var callable
     */
    private $callback;
    /**
     * @var mixed
     */
    private $result;

    /**
     * Actions to execute when run
     *
     * @param $currentTick
     *
     * @return void
     */
    public function onRun($currentTick) {
        $ca = $this->callback;
        $ca($this->result);
    }
}