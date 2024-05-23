<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more($more) {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

/**
 * Add wrapper to oEmbed content
 */
add_filter('oembed_dataparse', 'wrap_oembed_dataparse', 99, 4);
function wrap_oembed_dataparse($return, $data, $url) {
    $mod = '';
 
    if(($data->type == 'video')) {
        $mod = 'embed-responsive--video';
    }
    return '<div class="embed-responsive ' . $mod . '">' . $return . '</div>';
}
