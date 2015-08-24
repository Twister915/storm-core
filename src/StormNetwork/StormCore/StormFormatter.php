<?php
/**
 * Created by PhpStorm.
 * User: Electric
 * Date: 8/24/2015
 * Time: 4:05 PM
 */

namespace StormNetwork\StormCore;


use pocketmine\utils\TextFormat;

final class StormFormatter {
    private static $prefixValue;

    public static function loadPrefix() {
        if (!StormCore::getInstance()->getFormats()->exists("prefix")) self::$prefixValue = '';
        else self::$prefixValue = self::withPath("prefix")->withPrefix(false)->get();
    }

    public static function withPath($path) {
        return new StormFormatter(StormCore::getInstance()->getFormats()->get($path, 'Not found!'));
    }

    /**
     * @var string
     */
    private $format;
    /**
     * @var string[]
     */
    private $input = array();
    /**
     * @var boolean
     */
    private $prefix = true;
    /**
     * @var boolean
     */
    private $coloredInputs;

    private function __construct($format) {
        $this->format = $format;
    }

    /**
     * @param $key
     * @param $value
     * @return StormFormatter
     */
    public function with($key, $value) {
        $this->input[$key] = $value;
        return $this;
    }

    public function withPrefix($value) {
        $this->prefix = $value;
        return $this;
    }

    public function withColoredInputs() {
        $this->coloredInputs = !$this->coloredInputs;
        return $this;
    }

    public function get() {
        $working = self::colorChat($this->format);
        foreach ($this->input as $key=>$value) {
            $working = preg_replace('{{' . $key . '}}', $this->coloredInputs ? self::colorChat($value) : $value, $working);
        }
        if ($this->prefix) $working = self::$prefixValue . $working;
        return $working;
    }

    private static function colorChat($str) {
        return preg_replace('&(?=[0-9a-fA-Fk-oK-OrR])', TextFormat::ESCAPE, $str);
    }
}