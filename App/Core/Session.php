<?php

Class Session {

    public static function init()
    {
        if(session_id() == ''){
            session_start();
        }
    }

    /**
     * Esta função faz registrar uma seção passando a chave e o conteúdo da mesma
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Esta função pega a chave gravada anteriormente
     * @param $key
     * @return bool
     */
    public static function get($key)
    {
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return false;
        }
    }

    /**
     * Esta função destroy todas as seções abertas
     */
    public static function destroy()
    {
        session_destroy();
        unset($_SESSION);
    }

    /**
     * Esta função destroi uma seção ativa - somente
     * @param $key
     * @return bool
     */
    public static function remove($key)
    {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        } else {
            return false;
        }
    }

}