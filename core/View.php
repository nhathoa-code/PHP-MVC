<?php

namespace Mvc\Core;

class View {
    protected $viewPath;
    protected $data = [];

    public function __construct($viewPath) {
        $this->viewPath = $viewPath;
    }

    public function render() {

        extract($this->data);

        include_once ROOT_PATH . '/views/' . $this->viewPath . '.php';
    }

    public function setData(array $data = array())
    {
        $this->data = $data;
    }
}