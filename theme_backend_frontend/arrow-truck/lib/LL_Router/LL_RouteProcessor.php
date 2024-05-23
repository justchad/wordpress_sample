<?php

class LL_RouteProcessor
{
    /**
     * The matched route found by the router.
     *
     * @var Route
     */
    private $matched_route;

    /**
     * The router.
     *
     * @var Router
     */
    private $router;

    /**
     * The routes we want to register with WordPress.
     *
     * @var Route[]
     */
    private $routes;

    /**
     * Constructor.
     *
     * @param Router  $router
     * @param Route[] $routes
     */
    public function __construct(LL_Router $router, array $routes = array())
    {
        $this->router = $router;
        $this->routes = $routes;
    }

    /**
     * Initialize processor with WordPress.
     *
     * @param Router  $router
     * @param Route[] $routes
     */
    public static function init(LL_Router $router, array $routes = array())
    {
        $self = new self($router, $routes);

        add_action('init', array($self, 'register_routes'), 10);
        add_action('rest_api_init', array($self, 'register_api_routes'));
        add_action('parse_request', array($self, 'match_request'));
        add_action('template_include', array($self, 'load_route_template'), 100);
        add_action('template_redirect', array($self, 'call_route_hook'));
    }

    /**
     * Checks to see if a route was found. If there's one, it calls the route hook.
     */
    public function call_route_hook()
    {
        if ( is_admin() )
            return;

        if ( !$this->matched_route instanceof LL_Route ) {
            return;
        }

        if ( !$this->matched_route->is_rest() && $this->matched_route->get_middleware() !== 'open' ) {
            return call_user_func_array( 'LL_Middleware::'.$this->matched_route->get_middleware(), array($this->router) );
        }


        if ( !$this->matched_route->has_hook() ) {
            return;
        }

        do_action($this->matched_route->get_hook());
    }

    /**
     * Checks to see if a route was found. If there's one, it loads the route template.
     *
     * @param string $template
     *
     * @return string
     */
    public function load_route_template($template)
    {
        if ( !$this->matched_route instanceof LL_Route || !$this->matched_route->has_template() ) {
            return $template;
        }

        $route_template = $this->matched_route->get_template();

        if ( is_array( $route_template ) ) {
            return call_user_func_array( $route_template, array($this->router) );
        } elseif (!empty($route_template)) {
            return $route_template;
        }

        return $template;
    }

    /**
     * Attempts to match the current request to a route.
     *
     * @param WP $environment
     */
    public function match_request(WP $environment)
    {
        $matched_route = $this->router->match($environment->query_vars);

        if ($matched_route instanceof LL_Route) {
            $this->matched_route = $matched_route;
        }

        if ($matched_route instanceof WP_Error && in_array('route_not_found', $matched_route->get_error_codes())) {
            wp_die($matched_route, 'Route Not Found', array('response' => 404));
        }
    }

    /**
     * Register all our routes into WordPress.
     */
    public function register_routes()
    {
        $routes = apply_filters('ll_router_routes', $this->routes);

        foreach ($routes as $name => $route) {
            $this->router->add_route($name, $route);
        }

        $this->router->compile();

        $routes_hash = md5(serialize($routes));

        if ($routes_hash != get_option('ll_router_routes_hash')) {
            flush_rewrite_rules();
            update_option('ll_router_routes_hash', $routes_hash);
        }
    }

    public function register_api_routes()
    {
        $this->router->compile_rest();
    }
}
