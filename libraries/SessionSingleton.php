<?php
final class SessionSingleton {
    public static function start() {
        static $instance = null;
        if( $instance === null ) {
            $instance = new SessionSingleton();
            $instance->startSession();
        }
        return $instance;
    }

    private function startSession() {
        session_start();
    }

    private function __construct() { }
    private function __clone() { }
}