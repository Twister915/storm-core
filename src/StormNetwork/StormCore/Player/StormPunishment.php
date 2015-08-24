<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/18/2015
 * Time: 5:17 PM
 */

namespace StormNetwork\StormCore\Player;


final class StormPunishment {
    /**
     * @var string
     */
    private $reason;
    /**
     * @var StormPlayer
     */
    private $punisher;
    /**
     * @var StormPlayer
     */
    private $target;
    /**
     * @var string
     */
    private $type;
    /**
     * @var \DateTime
     */
    private $expirationDate;

    /**
     * StormPunishment constructor.
     * @param string $reason
     * @param StormPlayer $punisher
     * @param StormPlayer $target
     * @param string $type
     * @param \DateTime $expirationDate
     */
    public function __construct($reason, StormPlayer $punisher, StormPlayer $target, $type, \DateTime $expirationDate) {
        $this->reason = $reason;
        $this->punisher = $punisher;
        $this->target = $target;
        $this->type = $type;
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return string
     */
    public function getReason() {
        return $this->reason;
    }

    /**
     * @return StormPlayer
     */
    public function getPunisher() {
        return $this->punisher;
    }

    /**
     * @return StormPlayer
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate() {
        return $this->expirationDate;
    }
}