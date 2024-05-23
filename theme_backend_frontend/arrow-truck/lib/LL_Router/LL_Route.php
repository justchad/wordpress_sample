<?php

class LL_Route
{
    protected static $singleton;
    private $hook;
    private $path;
    private $template;
    private $method;
    private $middleware;

    /**
     * Constructor.
     *
     * @param string $path
     * @param string $hook
     * @param string $template
     */
    public function __construct( $path, $hook = '', $template = '', $method = '', $middleware = 'open' )
    {
        $this->path = $path;
        $this->hook = $hook;
        $this->template = $template;
        $this->method = $method;
        $this->middleware = $middleware;
    }

    /**
     * Get the singleton instance of the Router
     *
     * @return Rareloop\Router\Router
     */
    private static function instance()
    {
        if (!isset(static::$singleton)) {
            static::$singleton = new self('','','',false);
        }

        return static::$singleton;
    }

    public static function default($path, $hook = '', $template = '', $middleware = 'open')
    {
        return new self($path, $hook, $template, false, $middleware);
    }

    public static function get($path, $hook = '', $template = '', $middleware = 'open')
    {
        return new self($path, $hook, $template, WP_REST_Server::READABLE, $middleware);
    }

    public static function post($path, $hook = '', $template = '', $middleware = 'open')
    {
        return new self($path, $hook, $template, WP_REST_Server::CREATABLE, $middleware);
    }

    public static function patch($path, $hook = '', $template = '', $middleware = 'open')
    {
        return new self($path, $hook, $template, WP_REST_Server::EDITABLE, $middleware);
    }

    public static function delete($path, $hook = '', $template = '', $middleware = 'open')
    {
        return new self($path, $hook, $template, WP_REST_Server::DELETABLE, $middleware);
    }

    public static function all($path, $hook = '', $template = '', $middleware = 'open')
    {
        return new self($path, $hook, $template, WP_REST_Server::ALLMETHODS, $middleware);
    }

    public function get_hook()
    {
        return $this->hook;
    }

    public function get_path()
    {
        return $this->path;
    }

    public function get_template()
    {
        return $this->template;
    }

    public function has_hook()
    {
        return !empty($this->hook);
    }

    public function has_template()
    {
        return !empty($this->template);
    }

    public function is_rest()
    {
        return !is_bool( $this->method );
    }


    public function get_method()
    {
        return $this->method;
    }

    public function get_middleware()
    {
        return $this->middleware;
    }
}
