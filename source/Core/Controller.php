<?php

namespace Source\Core;

use CoffeeCode\Router\Router;
use Jenssegers\Blade\Blade;

class Controller
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Blade
     */
    protected $blade;

    private $views = CONF_APP_VIEWS;
    private $cache = CONF_APP_VIEWS . '/cache';

    public function __construct($router)
    {
        $this->router = $router;
        $this->blade = new Blade($this->views, $this->cache);
    }
}
