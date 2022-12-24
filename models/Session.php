<?php

/**
 * Модель для работы с php сессией
 */
class Session extends Core {

    /**
     * Запускаем сессию
     */
    public function __construct() {
        parent::__construct();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return false|mixed
     */
    public function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return false;
    }

    /**
     * @param $key
     *
     * @return void
     */
    public function clear($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function setFlash($key, $value) {
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * @param $key
     *
     * @return false|mixed
     */
    public function getFlash($key) {
        if (isset($_SESSION['_flash'][$key])) {
            $data = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);

            return $data;
        }

        return false;
    }

}
