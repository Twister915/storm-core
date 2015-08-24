<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 2:41 PM
 */

namespace StormNetwork\StormCore\Player;


class StormSession {
    /**
     * @var StormOfflinePlayer
     */
    private $user;
    /**
     * @var string
     */
    private $address;

    /**
     * StormSession constructor.
     * @param StormOfflinePlayer $user
     * @param string $address
     */
    public function __construct(StormOfflinePlayer $user, $address) {
        $this->user = $user;
        $this->address = $address;
    }

    /**
     * @return StormOfflinePlayer
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }
}