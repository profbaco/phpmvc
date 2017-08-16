<?php
class View {
    protected $view;

    public function render($view, $title = '')
    {
        $this->view = $view;

        if($title != ''){
            $this->title = $title;
        } else {
            $this->title = TITLE;
        }

        include VIEWS . 'tema/layout.php';
    }

    public function content()
    {
        include_once VIEWS . $this->view . '.php';
    }

}