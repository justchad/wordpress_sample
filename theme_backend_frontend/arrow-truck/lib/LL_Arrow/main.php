<?php
    // Client
    require_once( plugin_dir_path( __FILE__ ) . 'Client/ArrowApiClient.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'Client/ArrowApiInventory.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'Client/ArrowApiEmployee.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'Client/ArrowApiLead.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'Client/ArrowApiAccount.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'Client/ArrowApiLocation.php' );
    // Build
    require_once( plugin_dir_path( __FILE__ ) . 'Build/ArrowBuildEmployee.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'Build/ArrowBuildInventory.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'Build/ArrowBuildLocation.php' );
    // Class
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowFormHandler.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowLocation.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowLocationAdmin.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowSalesRep.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowSalesRepAdmin.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowFilters.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowFilterTerm.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowTruck.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowInventoryAdmin.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowEstimate.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'hooks.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowInventory.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowParse.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'ArrowLocale.php' );
    // Global
    define( 'ARROW_COUNTRY', 'USA' ); // USA or CAN
    define( 'ARROW_CANADIAN_DOLLAR_CONVERSION_RATE', 1 );
    define( 'ARROW_CANADIAN_MILEAGE_CONVERSION_RATE', 1 );
    define( 'ARROW_SEARCH_DEFAULT_POSTS_PER_PAGE', 30 );
    define( 'ARROW_INVENTORY_POST_TYPE', 'll_inventory' );
    define( 'ARROW_INVENTORY_TRANSIENT', 'inventory_transient' );
    define( 'ARROW_INVENTORY_ITEMS_PER_PAGE', 30 );
    define( 'ARROW_LOCATION_POST_TYPE', 'll_location' );
    define( 'ARROW_LOCATION_TRANSIENT', 'location_transient' );
    define( 'ARROW_EMPLOYEE_TRANSIENT', 'rep_transient' );
    define( 'ARROW_LOCATION_FALLBACK_IMAGE', get_template_directory_uri() . "/assets/img/default_LOCATION.jpg" );
    define( 'ARROW_DATA_REFRESH_FREQUENCY_IN_HOURS', 8 );
    define( 'ARROW_TRANSIENT_TTL', 28800 ); // TRANSIENT TIME TO LIVE, 8 HOURS IN SECONDS
    define( 'ARROW_VCARD_DIRECTORY', 'v-card' ); // WHERE ALL V-CARDS ARE STORED IN THE wp-content/public/uploads directory
    define( 'ARROW_FEATURED_TRUCK_DISPLAY_LIMIT', 3 );
    define( 'ARROW_EXCLUDE_TRAILER_FROM_FEATURED', true );
    define( 'ARROW_PROCESSING_DIRECTORY', get_template_directory_uri() . "/lib/LL_Arrow/Processing" );
    define( 'ARROW_PROCESSING_DIRECTORY_BASE_FILENAME', 'inventory_batch_' );

    $languages = [
        [
            'name' => 'ENGLISH'
        ],
        [
            'name' => 'ARABIC'
        ],
        [
            'name' => 'BOSNIAN'
        ],
        [
            'name' => 'BULGARIAN'
        ],
        [
            'name' => 'CROATIAN'
        ],
        [
            'name' => 'CZECH'
        ],
        [
            'name' => 'HEBREW'
        ],
        [
            'name' => 'HINDI'
        ],
        [
            'name' => 'MACEDONIAN'
        ],
        [
            'name' => 'POLISH'
        ],
        [
            'name' => 'PORTUGUESE'
        ],
        [
            'name' => 'PUNJABI'
        ],
        [
            'name' => 'RUSSIAN'
        ],
        [
            'name' => 'SERBIAN'
        ],
        [
            'name' => 'SLOVAK'
        ],
        [
            'name' => 'SPANISH'
        ],
        [
            'name' => 'TAMIL'
        ],
        [
            'name' => 'UKRAINE'
        ],
        [
            'name' => 'URDU'
        ]
    ];

    define( 'ARROW_LANGUAGE_LIST', $languages );

    define( 'ARROW_REFRESH_LANGUAGE_LIST', false );

    $roles = [
        'arrow_branch_manager',
        'arrow_multiple_branch_manager',
        'arrow_assistant_branch_manager',
        'arrow_fandi_manager',
        'arrow_buyer',
        'arrow_sales_rep',
        'arrow_lead_sales_associate',
        'arrow_retail_sales_consultant',
        'arrow_admin_assistant',
        'arrow_inventory_coordinator',
        'arrow_sales_purchasing_manager',
        'arrow_sales_manager',
        'arrow_assistant_branch_manager'
    ];
    define( 'ARROW_USER_ROLES', $roles );
    define( 'ARROW_BRANCH_MANAGER_ROLE', 'arrow_branch_manager' );
    define( 'ARROW_BRANCH_ADMIN_ROLE', 'arrow_fandi_manager' );
    define( 'ARROW_BRANCH_REP_ROLES', array_diff( ARROW_USER_ROLES, [ ARROW_BRANCH_MANAGER_ROLE, ARROW_BRANCH_ADMIN_ROLE ] ) );

    define( 'ARROW_API_ENVIRONMENT', 'DEV' ); // TESTING, LOCAL, DEV, STAGE, PRODUCTION
    define( 'ARROW_CRON_ENABLED', true );
