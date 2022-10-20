<?php

namespace Bitrix\Main\Config;

class Option
{
    /**
     * @var array
     */
    public static $options = [];

    public static function load($moduleId) {}

    public static function get($moduleId, $name, $default = "", $siteId = false)
    {
        return static::$options[$moduleId][$name] ?? $default;
    }

    public static function getCacheTtl() {}

    public static function getDefaultSite() {}

    public static function delete($moduleId, array $filter = array()) {}

    public static function getRealValue($moduleId, $name, $siteId = false) {}

    public static function clearCache($moduleId) {}

    public static function set($moduleId, $name, $value = "", $siteId = "")
    {
        static::$options[$moduleId][$name] = $value;
    }

    public static function getDefaults($moduleId) {}

    public static function getForModule($moduleId, $siteId = false) {}

    public static function loadTriggers($moduleId) {}
}