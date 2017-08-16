<?php
require '../app/core/config.php';

class app {

    public function __construct()
    {
        $url = explode('/', $this->getURL());
        $controller = strtolower($url[0].'Controller');
        $action =( isset($url[1]) ) ? $url[1] : 'index';
        $param = ( isset($url[2]) ) ? $url[2] : null;

        require LIBS . ' Session.php';

        $arquivo = CONTROLLERS . $controller.'.php';
        if ( file_exists($arquivo)) {
            // Requirindo o controller
            require_once $arquivo;
            $controller = new $controller();

            // Checando o método
            if (method_exists($controller, $action)){
                if($param !== null){
                    $controller->{$action}($param);
                } else {
                    $controller->{$action}();
                }
            } else {
                echo 'Página não encontrada - Action';
            }
        } else {
            echo 'Página não encontrada - Controller';
        };
    }
    
    public function getURL()
    {
        $url = (isset($_GET['url'])) ? parse_url( trim($_GET['url'], PHP_URL_PATH)) : 'home/index';
        return $url;
    }
}