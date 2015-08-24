<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 4:42 PM
 */

namespace StormNetwork\StormCore\Command;

class CommandRegistration {
    /**
     * @var string
     */
    private $name;
    /**
     * @var StormCommand
     */
    private $commandHandler;

    /**
     * CommandRegistration constructor.
     * @param string $name
     * @param StormCommand $commandHandler
     */
    public function __construct($name, StormCommand $commandHandler) {
        $this->name = $name;
        $this->commandHandler = $commandHandler;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return StormCommand
     */
    public function getCommandHandler() {
        return $this->commandHandler;
    }
}