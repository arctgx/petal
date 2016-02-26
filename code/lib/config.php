<?php
class config {

    protected static $_conf = array();

    protected static function _init() {
        if (empty(self::$_conf)) {
            self::$_conf = array(
                'db' => include CONF_PATH.'db.php',
            );
        }
    }

    public static function getDBConf() {
        self::_init();
        return self::$_conf['db'];;
    }

}