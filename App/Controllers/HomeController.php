<?php
class HomeController
{


    public function index()
    {
        echo 'Acessando o primeiro controller';
    }

    public function outra()
    {
        echo 'outra';
    }

    public function parametro($parametro)
    {
        echo 'O parâmetro passado é: ' . $parametro;
    }

}