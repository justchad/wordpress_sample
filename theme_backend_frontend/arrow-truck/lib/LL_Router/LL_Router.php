<?php
class LL_Router {

    private $routes;
    private $route_variable;
    private $is_rest_request;
    private $is_ajax_request;
    private $is_server_request;

    public function __construct($route_variable = 'route_name', array $routes = array())
    {
        $this->routes = array();
        $this->route_variable = $route_variable;
        $this->is_rest_request = ( defined( 'REST_REQUEST' ) && REST_REQUEST );
        $this->is_ajax_request = wp_doing_ajax();
        $this->is_server_request = ( !$this->is_rest_request && !$this->is_ajax_request );

        foreach ($routes as $name => $route) {
            $this->add_route($name, $route);
        }
    }


    public function add_route($name, LL_Route $route)
    {
        $this->routes[$name] = $route;
    }


    public function compile()
    {
        add_rewrite_tag('%'.$this->route_variable.'%', '(.+)');

        foreach ($this->routes as $name => $route) {
            if ( !$route->is_rest() )
                $this->add_rule($name, $route);
        }
    }

    public function compile_rest()
    {
        $router = $this;
        $groups = [];
        $router_params = array(
            'is_ajax_request' => $router->is_ajax_request,
            'is_rest_request' => $router->is_rest_request,
            'is_server_request' => $router->is_server_request,
        );

        foreach ($this->routes as $name => $route) {

            if ( !$route->is_rest() )
                continue;

            $request_setup = array(
                'methods'  => $route->get_method(),
                'callback' => $route->get_template(),
                'permission_callback' => function( WP_REST_Request $request ) use ( $router_params, $name, $route ) {
                    if ( !wp_verify_nonce( $request->get_header('X-WP-Nonce'), 'wp_rest' ) ) {

                        return new WP_Error(
                            'rest_not_found',
                            __( 'Resource not found' ),
                            array( 'status' => 404 )
                        );
                    }

                    $request->is_ajax_request   = $router_params['is_ajax_request'];
                    $request->is_rest_request   = $router_params['is_rest_request'];
                    $request->is_server_request = $router_params['is_server_request'];

                    return call_user_func_array( 'LL_Middleware::'.$route->get_middleware(), array($request) );
                }
            );


            if ( !isset($groups[$route->get_path()]) ) {
                $groups[$route->get_path()] = array();
            }

            $groups[$route->get_path()][] = $request_setup;
        }

        foreach( $groups as $path => $routes ) {
            register_rest_route( 'll/api/v1', $path, $routes, true );
        }
    }

    /**
     * Flushes all WordPress routes.
     *
     * @uses flush_rewrite_rules()
     */
    public function flush()
    {
        flush_rewrite_rules();
    }

    /**
     * Tries to find a matching route using the given query variables. Returns the matching route
     * or a WP_Error.
     *
     * @param array $query_variables
     *
     * @return LL_Route|WP_Error
     */
    public function match(array $query_variables)
    {
        if (empty($query_variables[$this->route_variable])) {
            return new WP_Error('missing_route_variable');
        }

        $route_name = $query_variables[$this->route_variable];
        if (!isset($this->routes[$route_name])) {
            return new WP_Error('route_not_found');
        }

        return $this->routes[$route_name];
    }

    /**
     * Adds a new WordPress rewrite rule for the given LL_Route.
     *
     * @param string $name
     * @param LL_Route  $route
     * @param string $position
     */
    private function add_rule($name, LL_Route $route, $position = 'top')
    {
        add_rewrite_rule($this->generate_route_regex($route), 'index.php?'.$this->route_variable.'='.$name, $position);
    }

    /**
     * Generates the regex for the WordPress rewrite API for the given route.
     *
     * @param LL_Route $route
     *
     * @return string
     */
    private function generate_route_regex(LL_Route $route)
    {
        return '^'.ltrim(trim($route->get_path()), '/').'$';
    }

    public function is_rest_request()
    {
        return ( defined( 'REST_REQUEST' ) && REST_REQUEST );
    }

    public function is_ajax_request()
    {
        return wp_doing_ajax();
    }

    public function is_server_request()
    {
        return !$this->is_rest_request() && !$this->is_ajax_request();
    }
}
