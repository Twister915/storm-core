<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/18/2015
 * Time: 5:05 PM
 */

namespace StormNetwork\StormCore\Player;


interface Permissible {
    /**
     * @param $permission string
     * @return boolean
     */
    public function hasPermission($permission);

    /**
     * @return void
     */
    public function reloadPermissions();

    /**
     * @return array
     */
    public function getEffectivePermissions();
}