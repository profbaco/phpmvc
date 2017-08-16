<?php
require('view.php');

class Controller {
    public function __construct()
    {
        $this->view = new View();
        Session::init();
    }

    public function validadeKeys($keys, $where)
    {
        foreach( $keys as $key ) {
            if (empty($where[$key]) or !isset($where[$key])){
                exit('Não foi encontrado o campo ' . $key);
            }
        }
        return true;
    }
}