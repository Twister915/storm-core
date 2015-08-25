<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 7:58 PM
 */

namespace StormNetwork\StormCore\Http;

class StormRequestEncapsulation {
    private $callable;
    private $caller;

    /**
     * StormRequestEncapsulation constructor.
     * @param $callable callable
     * @param $caller
     */
    public function __construct($callable, $caller) {
        $this->callable = $callable;
        $this->caller = $caller;
    }

    /**
     * @return callable
     */
    public function getCallable() {
        return $this->callable;
    }

    /**
     * @return mixed
     */
    public function getCaller() {
        return $this->caller;
    }
}