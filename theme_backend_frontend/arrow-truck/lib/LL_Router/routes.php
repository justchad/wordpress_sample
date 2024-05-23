<?php


add_action('init', function() {
  $router = new LL_Router('lifted_logic');
  $routes = array(
    'component_preview'                 => LL_Route::default( 'component-preview', '', get_stylesheet_directory() . '/templates/component-preview.php' ),
    'admin/employee/sync'               => LL_Route::get( '/employee/sync', '', array( 'ArrowApiEmployee', 'sync' ), 'isAdmin' ),
    'admin/employee/purge'              => LL_Route::get( '/employee/purge', '', array( 'ArrowApiEmployee', 'purge' ), 'isAdmin' ),
    'admin/location/sync'               => LL_Route::get( '/location/sync', '', array( 'ArrowApiLocation', 'sync' ), 'isAdmin' ),
    'admin/location/purge'              => LL_Route::get( '/location/purge', '', array( 'ArrowApiLocation', 'purge' ), 'isAdmin' ),
    'admin/inventory/sync'              => LL_Route::get( '/inventory/sync', '', array( 'ArrowApiInventory', 'sync' ), 'isAdmin' ),
    'admin/inventory/purge'             => LL_Route::get( '/inventory/purge', '', array( 'ArrowApiInventory', 'purge' ), 'isAdmin' ),
    'user/data'                         => LL_Route::get( '/user/data', '', array( 'LL_WP_User', 'getData' ) ),
    'user/favorites/get'                => LL_Route::get( '/user/favorites', '', array( 'LL_WP_User', 'getFavorites' ), 'isLoggedIn' ),
    'user/favorites/add'                => LL_Route::post( '/user/favorites', '', array( 'LL_WP_User', 'addFavorite' ), 'isLoggedIn' ),
    'inventory/filter'                  => LL_Route::post( '/inventory/filter', '', array( 'ArrowApiInventory', 'filter' ) ),
    'inventory/sort'                    => LL_Route::post( '/inventory/filter', '', array( 'ArrowApiInventory', 'sort' ) ),
    'inventory/paginate'                => LL_Route::post( '/inventory/paginate', '', array( 'ArrowApiInventory', 'paginate' ) ),
    'user/favorites/delete'             => LL_Route::delete( '/user/favorites', '', array( 'LL_WP_User', 'removeFavorite' ), 'isLoggedIn' ),
  );

  LL_RouteProcessor::init( $router, $routes );
}, 0 );
