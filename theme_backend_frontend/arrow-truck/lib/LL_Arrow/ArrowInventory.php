<?php


    class ArrowInventory
    {
        public $post_type;
        public $per_page_size;


        function __construct()
        {
            $this->post_type        = ARROW_INVENTORY_POST_TYPE;
            $this->per_page_size    = ARROW_SEARCH_DEFAULT_POSTS_PER_PAGE;
        }

        public static function all()
        {
            $truck = new ArrowInventory();

            $args = [
                'fields'        => 'ids',
                'post_type'     => $truck->post_type,
                'post_status'   => 'publish',
                'numberposts'   => -1,
                'order'         => 'ASC'
            ];

            $posts = get_posts( $args );

            $trucks = array_chunk( $posts, $truck->per_page_size );

            // get_post_meta()

            return $trucks;
        }

    }
