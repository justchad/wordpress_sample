<?php
    $industry = '';

    if ( $_GET && isset( $_GET[ 'industry' ] ) ) {
        $iget = $_GET['industry'];

        switch ($iget) {
            case 1:
                $iget = ' > Over-the-Road ';
                break;
            case 2:
                $iget = ' > Regional ';
                break;
            case 3:
                $iget = ' > Local ';
                break;
            case 4:
                $iget = ' > Moving and Storage ';
                break;
            case 5:
                $iget = ' > Construction ';
                break;
            case 6:
                $iget = ' > Agriculture ';
                break;
            default:
                $iget = '';
        }

        $industry = $iget;
    }

    if ( $_GET && isset( $_GET[ 'stock-num' ] ) ) {
        // unset( $_GET['stock-num'] );
    }

    $hasGet = false;

    if ( $_GET ) {
        $hasGet = true;
        $protocol = ( ( ! empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] != 'off' ) || $_SERVER[ 'SERVER_PORT' ] == 443 ) ? "https://" : "http://";
        $url = $protocol . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
        $queryStr = parse_url( $url, PHP_URL_QUERY );
        parse_str( $queryStr, $queryParams );
        $search_params = $queryParams;
    }else{
        $search_params = array();
    }

    if ( ! empty( $search_params ) ) {
        // $trucks = ArrowApiInventory::search( $search_params );
    } else {
        // $trucks = ArrowApiInventory::getAll();
    }

    $filters = new ArrowFilters;


    if ( isset( $_GET[ 'fleet' ] ) ) {
        $invFleet = $_GET[ 'fleet' ];
        $goForPromo = true;
    } else {
        $invFleet = '';
        $goForPromo = false;
    }

    // We need a quick solution for searching for promotions here.

    $promo_fleet_array = array();
    $promo_fleet_text = null;

    if ( $goForPromo == true ) {
        $promo_args = array(
        	'numberposts'	=> -1,
            'fields'        => 'ids',
        	'post_type'		=> 'll_promotion',
        	'meta_query'	=> array(
        		'relation'		=> 'AND',
        		array(
        			'key'		=> 'promotion_fleet_code',
        			'value'		=> $_GET[ 'fleet' ],
        			'compare'	=> '='
        		),
        		array(
        			'key'		=> 'promotion_enable_fleet_code',
        			'value'		=> '1',
        			'compare'	=> '='
        		)
        	)
        );

        // query
        $promo_query = new WP_Query( $promo_args );

        $promo_enabled = false;

        $promo_fleet_array = explode( ",", $_GET[ 'fleet' ] );

        if ( sizeof( $promo_query->posts ) >= 1 ) {
            $promo_enabled = true;
            $post_id = $promo_query->posts;
            $promo_found = get_post( $post_id[0] );
            $promo_fleet_text = $promo_found->post_title;
        }
    }

    // SEE( $filters );
?>

<?php while ( have_posts() ) : the_post(); ?>

    <div class="search-inv-page">
        <div class="flex flex-wrap">
            <div class="flex-initial w-full lg:w-1/4 single-inv-filters">
                <form action="#" id="inventory-filter">
                    <div id="search-inventory-form-1" class="input-area flex justify-center py-8 w-full p-4 md:p-8 single-inv-filters-stocknumber hidden lg:block">
                        <div class="single-inv-filters-stocknumber-wrapper">
                            <input type="text" class="b order rounded border-gray-200 py-1 px-3 w-full mx-6 " name="stock-num" id="stock-num" placeholder="Stock No.">
                            <button id="stock-num-button" class="btn single-inv-filters-stocknumber-button">
                                Find
                            </button>
                        </div>
                        <div id="stock-number-alert" class="hidden" style="padding: 0.5rem 1rem;color: #C72027;">
                        </div>
                    </div>
                    <div class="search-inventory-header pt-4">
                        <div class="container">
                            <div id="search-inventory-form-2" class="search-inventory-form hidden lg:block">
                                <div class="flex items-center justify-between flex-wrap">
                                    <div class="flex-1 mr-4">
                                        <h1 class="hdg-3 text-lg lg:text-3xl">
                                            Search Inventory
                                        </h1>
                                    </div>
                                    <div class="w-full relative current-filters-wrapper">
                                        <div class="current-filters-toggle-wrapper">
                                            <span
                                                data-toggletextopen="Hide"
                                                data-toggletextclosed="Open Filters"
                                                data-toggledefault="show"
                                                id="current-filters-toggle"
                                                class="current-filters-toggle">
                                                Hide
                                            </span>
                                        </div>
                                        <div id="top-o-the-filter" class="current-looking-at">
                                            <div class="current-looking-at-top-header">
                                                <h2>
                                                    currently viewing:
                                                </h2>
                                                <span id="filter-count" class="filters pointer-events-none">
                                                    <span class="count pointer-events-none">
                                                        0
                                                    </span>
                                                </span>
                                                <a id="clear-filters" class="clear-filter-control" href="<?php echo get_permalink() ?>">
                                                    Clear Filters
                                                </a>
                                            </div> <!-- /.current-looking-at-top-header -->
                                            <div class="current-looking-at-price looking-at-wrapper">
                                                <div id="la-minimum-price" class="looking-at-minimum-price looking-at-each-item">
                                                    <span class="looking-at-header">
                                                        Minimum Price
                                                    </span>
                                                    <span id="la-minimum-price-text" class="looking-at-value">
                                                        $1
                                                    </span>
                                                </div> <!-- /.looking-at-minimum-price -->
                                                <div id="la-maximum-price" class="looking-at-maximum-price looking-at-each-item">
                                                    <span class="looking-at-header">
                                                        Maximum Price
                                                    </span>
                                                    <span id="la-maximum-price-text" class="looking-at-value">
                                                        $1,000,000
                                                    </span>
                                                </div> <!-- /.looking-at-maximum-price -->
                                            </div> <!-- /.current-looking-at-price -->
                                            <div class="current-looking-at-mileage looking-at-wrapper">
                                                <div id="la-minimum-miles" class="looking-at-minimum-mileage looking-at-each-item">
                                                    <span class="looking-at-header">
                                                        Minimum Miles
                                                    </span>
                                                    <span id="la-minimum-miles-text" class="looking-at-value">
                                                        0
                                                    </span>
                                                </div> <!-- /.looking-at-minimum-mileage -->
                                                <div id="la-maximum-miles" class="looking-at-maximum-mileage looking-at-each-item">
                                                    <span class="looking-at-header">
                                                        Maximum Miles
                                                    </span>
                                                    <span id="la-maximum-miles-text" class="looking-at-value">
                                                        500,000
                                                    </span>
                                                </div> <!-- /.looking-at-maximum-mileage -->
                                            </div> <!-- /.current-looking-at-mileage -->
                                            <div class="current-looking-at-sort looking-at-wrapper looking-at-wrapper-last">
                                                <div id="la-sort-type" class="looking-at-sort-type looking-at-each-item">
                                                    <span class="looking-at-header">
                                                        Sorted By
                                                    </span>
                                                    <span id="la-sort-type-text" class="looking-at-value">
                                                        Year
                                                    </span>
                                                </div> <!-- /.  -->
                                                <div id="la-sort-direction" class="looking-at-sort-direction looking-at-each-item">
                                                    <span class="looking-at-header">
                                                        Sort Order
                                                    </span>
                                                    <span id="la-sort-direction-text" class="looking-at-value">
                                                        Descending
                                                    </span>
                                                </div> <!-- /.looking-at-sort-direction  -->
                                            </div> <!-- /.current-looking-at-sort  -->
                                        </div> <!-- /.current-looking-at  -->
                                        <div id="mid-o-the-filter" class="flex justify-start items-center current-filters">
                                        </div> <!-- /.flex -->
                                        <div id="mid-sep-o-the-filter" class="current-filters-seperator">
                                        </div> <!-- /.current-filters -->
                                    </div> <!-- /.current-filters-wrapper -->
                                </div> <!-- /.flex items-center justify-between flex-wrap -->
                                <div id="end-o-the-filter" class="items-center form-buttons mt-6">

                                    <?php foreach( $filters->fields as $field ) : ?>

                                        <?php if ( $field->context == 'main' ) : ?>

                                            <div class="button-area block <?php echo $field->id; ?>-button-wrapper">
                                                <button
                                                    data-dropfilter="<?php echo $field->id; ?>-dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                    aria-controls="#<?php echo $field->id; ?>-dropdown"
                                                    id=""
                                                    class="flex justify-between w-full cursor-pointer pb-1 pt-4 filterbuttonsidebar <?php echo $field->id; ?>-button">
                                                    <?php echo $field->label; ?>
                                                    <svg class="icon icon-chevron-down inline-block <?php echo $field->id; ?>-chevron">
                                                        <use xlink:href="#icon-chevron-down"></use>
                                                    </svg>
                                                </button>
                                                <div id="<?php echo $field->id; ?>-dropdown" class="search-dropdown">
                                                    <span class="arrow"></span>
                                                    <div class="search-dropdown-inner">
                                                        <?php include( locate_template( $field->template ) ); ?>
                                                    </div> <!-- /.search-dropdown-inner -->
                                                </div> <!-- /.search-dropdown -->
                                            </div> <!-- /.button-area -->

                                        <?php endif; ?>

                                    <?php endforeach; ?>

                                    <div class="button-area">
                                        <button
                                            data-toggle-group="menu-toggles"
                                            data-toggle-target="#advanced-search, #search-title-menu"
                                            data-toggle-class="is-open"
                                            data-toggle-escape aria-haspopup="true"
                                            aria-expanded="false"
                                            aria-controls="#more-filters-dropdown"
                                            class="hidden morebutton1">
                                            Show Filters
                                            <svg class="icon icon-chevron-down inline-block">
                                                <use xlink:href="#icon-chevron-down"></use>
                                            </svg>
                                        </button>
                                        <button
                                            data-toggle-target="#more-filters-dropdown"
                                            data-toggle-class="is-open"
                                            data-toggle-group="search-form"
                                            data-toggle-escape aria-haspopup="true"
                                            aria-expanded="false"
                                            aria-controls="#more-filters-dropdown"
                                            class="morebutton2 md:flex flex justify-between w-full cursor-pointer pb-1 pt-4 filterbuttonsidebar">
                                            More
                                            <svg class="icon icon-chevron-down hidden lg:inline-block">
                                                <use xlink:href="#icon-chevron-down"></use>
                                            </svg>
                                        </button>
                                        <div id="more-filters-dropdown" class="search-dropdown is-wide">
                                            <span class="arrow"></span>
                                            <div class="search-dropdown-inner px-6 py-6">

                                                <?php foreach( $filters->fields as $field ) : ?>

                                                    <?php if ( $field->context == 'additional' && count( ( array ) $field->options ) > 0 ) : ?>

                                                        <?php include( locate_template( $field->template ) ); ?>

                                                    <?php endif; ?>

                                                <?php endforeach; ?>
                                            </div> <!-- /.search-dropdown-inner -->
                                        </div> <!-- /.search-dropdown -->
                                    </div> <!-- /.button-area -->
                                </div> <!-- /.form-buttons -->
                            </div> <!-- /.search-inventory-form -->
                            <div class="mobile-filter-controller lg:hidden">
                                <button
                                    type="button"
                                    name="show-mobile-filter"
                                    id="show-mobile-filter-button"
                                    class="">
                                    Show Filters
                                </button>
                                <button
                                    type="button"
                                    name="show-mobile-filter"
                                    id="hide-mobile-filter-button"
                                    class="hidden">
                                    Hide Filters
                                </button>
                            </div> <!-- /.mobile-filter-controller -->
                            <div class="sort-filters-wrapper">
                                <input
                                    type="hidden"
                                    name="sort_factor"
                                    value="YEAR&Sort: Year"
                                    id="sort_factor"
                                    class="filter-input sort-input">
                                <input
                                    type="hidden"
                                    name="sort_order"
                                    value="DESC&Sort: Descending"
                                    id="sort_order"
                                    class="filter-input sort-input">
                                <input
                                    type="hidden"
                                    name="invfleet"
                                    value="<?php echo $invFleet; ?>"
                                    id="invfleet"
                                    class="filter-input sort-input">
                            </div> <!-- /.sort-filters-wrapper -->
                        </div> <!-- /.container -->
                    </div> <!-- /.search-inventory-header -->
                </form>
            </div>
            <div
                data-component="filters"
                id=""
                class="search-inventory-page bg-white flex flex-wrap w-full lg:w-3/4 single-inv-results height-max-content">
                <div class="search-inventory-results w-full">
                    <div class="container">
                        <h1 class="hdg-4 md:text-4xl mb-8 search-inventory-page-header">
                            Used
                            <span class="search-inventory-page-header-text-make"></span>
                            <span class="search-inventory-page-header-text-model"></span>
                            <span class="search-inventory-page-header-text-industry">
                                <?php echo $industry; ?>
                            </span>
                            <span class="search-inventory-page-header-text-type"></span>
                            <span class="search-inventory-page-header-text-truck">
                                trucks
                            </span>
                            <span class="search-inventory-page-header-text-location"></span>
                            <span class="search-inventory-page-header-new-line">
                                for sale
                            </span>
                        </h1>
                        <div class="mt-8 flex justify-between items-center">
                            <div class="flex-0 w-1/2 lg:w-1/4 relative">
                                <button
                                    data-toggle-target="#sort-dropdown"
                                    data-toggle-class="is-open"
                                    data-toggle-group="search-form"
                                    data-toggle-outside
                                    data-toggle-escape
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    aria-controls="#sort-dropdown"
                                    id="sort-dropdown-trigger"
                                    class="flex-0 text-sm sort-toggle">
                                    Sort by
                                    <svg class="icon icon-chevron-down text-brand-primary text-base ml-1 pointer-events-none">
                                        <use xlink:href="#icon-chevron-down"></use>
                                    </svg>
                                </button>
                                <div class="sort-dropdown" id="sort-dropdown">
                                    <form action="#" method="post" id="sort-filter">
                                        <span class="arrow"></span>
                                        <ul id="inventory-sort-trigger-wrapper">
                                            <li>
                                                <button
                                                    data-orderby="location"
                                                    data-order="asc"
                                                    data-sortactive="false"
                                                    data-sortgroup="location"
                                                    title="Sort by Location Ascending"
                                                    id="sort-location-asc"
                                                    class="inventory-sort-trigger inventory-location-asc">
                                                    Location
                                                    <span>
                                                        ASC
                                                    </span>
                                                </button>
                                                <input
                                                    type="hidden"
                                                    name=""
                                                    value="0"
                                                    location="location_asc"
                                                    id="location_asc">
                                                <button
                                                    data-orderby="location"
                                                    data-order="desc"
                                                    data-sortactive="false"
                                                    data-sortgroup="location"
                                                    title="Sort by Name Descending"
                                                    id="sort-location-desc"
                                                    class="inventory-sort-trigger inventory-location-desc">
                                                    Location
                                                    <span>
                                                        DESC
                                                    </span>
                                                </button>
                                                <input
                                                    type="hidden"
                                                    name=""
                                                    value="0"
                                                    location="location_desc"
                                                    id="location_desc">
                                            </li>
                                            <li>
                                                <button
                                                    data-orderby="year"
                                                    data-order="asc"
                                                    data-sortactive="false"
                                                    data-sortgroup="year"
                                                    title="Sort by Year Ascending"
                                                    id="sort-year-asc"
                                                    class="inventory-sort-trigger inventory-year-asc">
                                                    Year
                                                    <span>
                                                        ASC
                                                    </span>
                                                </button>
                                                <input
                                                    type="hidden"
                                                    name="year_asc"
                                                    value="0"
                                                    id="year_asc">
                                                <button
                                                    data-orderby="year"
                                                    data-order="desc"
                                                    data-sortactive="false"
                                                    data-sortgroup="year"
                                                    title="Sort by Year Descending"
                                                    id="sort-year-desc"
                                                    class="inventory-sort-trigger inventory-year-desc">
                                                    Year
                                                    <span>
                                                        DESC
                                                    </span>
                                                </button>
                                                <input
                                                    type="hidden"
                                                    name="year_desc"
                                                    value="0"
                                                    id="year_desc">
                                            </li>
                                            <li class="sort-last-row">
                                                <button
                                                    data-orderby="price"
                                                    data-order="asc"
                                                    data-sortactive="false"
                                                    data-sortgroup="price"
                                                    title="Sort by Price Ascending"
                                                    id="sort-price-asc"
                                                    class="inventory-sort-trigger inventory-price-asc">
                                                    Price
                                                    <span>
                                                        ASC
                                                    </span>
                                                </button>
                                                <input
                                                    type="hidden"
                                                    name="price_asc"
                                                    value="0"
                                                    id="price_asc">
                                                <button
                                                    data-orderby="price"
                                                    data-order="desc"
                                                    data-sortactive="false"
                                                    data-sortgroup="price"
                                                    title="Sort by Price Descending"
                                                    id="sort-price-desc"
                                                    class="inventory-sort-trigger inventory-price-desc">
                                                    Price
                                                    <span>
                                                        DESC
                                                    </span>
                                                </button>
                                                <input
                                                    type="hidden"
                                                    name="price_desc"
                                                    value="0"
                                                    id="price_desc">
                                            </li>
                                            <li class="sort-last-row">
                                                <button
                                                    data-orderby="mileage"
                                                    data-order="asc"
                                                    data-sortactive="false"
                                                    data-sortgroup="mileage"
                                                    title="Sort by Mileage Ascending"
                                                    id="sort-mileage-asc"
                                                    class="inventory-sort-trigger inventory-mileage-asc">
                                                    Mileage
                                                    <span>
                                                        ASC
                                                    </span>
                                                </button>
                                                <input
                                                    type="hidden"
                                                    name="mileage_asc"
                                                    value="0"
                                                    id="mileage_asc">
                                                <button
                                                    data-orderby="mileage"
                                                    data-order="desc"
                                                    data-sortactive="false"
                                                    data-sortgroup="mileage"
                                                    title="Sort by Mileage Descending"
                                                    id="sort-mileage-desc"
                                                    class="inventory-sort-trigger inventory-mileage-desc">
                                                    Mileage
                                                    <span>
                                                        DESC
                                                    </span>
                                                </button>
                                                <input
                                                    type="hidden"
                                                    name="mileage_desc"
                                                    value="0"
                                                    id="mileage_desc">
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                            <div class="flex-0 flex justify-end items-center">
                                <button
                                    data-view="list-view"
                                    title="List View"
                                    class="mr-2 view-button">
                                    <svg class="pointer-events-none icon icon-list">
                                        <use xlink:href="#icon-list"></use>
                                    </svg>
                                </button>
                                <button
                                    data-view="grid-view"
                                    title="Grid View"
                                    class="mr-2 view-button">
                                    <svg class="pointer-events-none icon icon-grid text-2xl">
                                        <use xlink:href="#icon-grid"></use>
                                    </svg>
                                </button>
                                <p class="font-bold text-sm">
                                    Results:
                                    <span class="results-total"></span>
                                </p>
                                <span class="search-result-share-button-wrapper">

                                    <?php
                                        $user = wp_get_current_user();
                                        $repNo = '';

                                        if($user->exists()){
                                            if($user->roles[0] == 'arrow_sales_rep'){
                                                $repNo = '';
                                            }
                                        }
                                    ?>

                                    <img
                                        data-rep="<?php echo $repNo; ?>"
                                        data-url=""
                                        src="/wp-content/themes/arrow-truck/assets/img/share.png"
                                        id="search_result_share_button"/>
                                </span>
                                <span class="copy-button-confirmation hidden-important">
                                    <span class="search-result-share-button_wrapper-ok">
                                        <img
                                            data-url=""
                                            src="/wp-content/themes/arrow-truck/assets/img/checkmark.png"
                                            id="search_result_share_button_ok"/>
                                    </span>
                                    Copied to Clipboard
                                </span>
                            </div> <!-- /.flex-0 flex justify-end items-center -->
                        </div> <!-- /.mt-8 flex justify-between items-center -->
                    </div> <!-- /.container -->

                    <?php
                        ll_include_component(
                            'featured-trucks-grid',
                            [
                                'hdg'               => [
                                    'text'              => null,
                                    'tag'               => null
                                ],
                                'origin'            => 'search-inventory',
                                'mobile_slider'     => false,
                                'list'              => false,
                                'all_trucks'        => true,
                                'single'            => false,
                                'location'          => null,
                                'address'           => null,
                                'view_all'          => false,
                                'grid'              => 'search',
                                'trucks'            => [],
                                'promotion'         => false,
                                'promotion_text'    => $promo_fleet_text,
                                'promotion_array'   => $promo_fleet_array
                            ],
                            [
                                'id'                => 'trucks-list-view',
                                'classes'           => []
                            ]
                        );
                    ?>

                    <div id="spinner-wrap" class="spinner-wrap hidden" style="display:none !important;">
                        <div class="spinner">
                            <svg class="icon icon-loading" aria-hidden="true">
                                <use xlink:href="#icon-loading"></use>
                            </svg>
                        </div> <!-- /.spinner -->
                    </div> <!-- /.spinner-wrap -->
                    <div id="truck-watch" class="hidden mb-10 px-8 md:px-0 truck-watch-wrapper">

                        <?php if ( get_field( 'truck_watch_form_id', 'option' ) ) : ?>

                            <?php echo gravity_form( get_field( 'truck_watch_form_id', 'option' ), false,false, false, '', true ); ?>

                        <?php endif; ?>

                    </div> <!-- /.truck-watch-wrapper -->
                </div> <!-- /.search-inventory-results -->
                <nav id="results-pagination" class="flex items-center justify-center mb-24 mx-auto hidden">
                    <a class="filter-pagination filters-start mr-6" href="#">
                        <svg class="icon icon-angle-double-left pointer-events-none" aria-hidden="true">
                            <use xlink:href="#icon-angle-double-left"></use>
                        </svg>
                    </a>
                    <a class="filter-pagination filters-back circle-icon" href="#">
                        <svg class="icon icon-chevron-left pointer-events-none" aria-hidden="true">
                            <use xlink:href="#icon-chevron-left"></use>
                        </svg>
                    </a>
                    <span id="pagination-text" class="page font-bold text-sm mx-12"></span>
                    <a class="filter-pagination filters-next circle-icon" href="#">
                        <svg class="icon icon-chevron-right pointer-events-none" aria-hidden="true">
                            <use xlink:href="#icon-chevron-right"></use>
                        </svg>
                    </a>
                    <a class="filter-pagination filters-end ml-6" href="#">
                        <svg class="icon icon-angle-double-right pointer-events-none" aria-hidden="true">
                            <use xlink:href="#icon-angle-double-right"></use>
                        </svg>
                    </a>
                </nav>
            </div> <!-- /.search-inventory-page -->
        </div>
    </div>
<?php endwhile; ?>
