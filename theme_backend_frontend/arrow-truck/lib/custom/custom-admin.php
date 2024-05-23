<?php
/**
 *
 * Lifted Logic custom admin
 *
 */

/**
 * Remove Dashboard Widgets
 */
function ll_remove_dashboard_meta() {
  remove_action( 'welcome_panel', 'wp_welcome_panel' ); // welcome panel
  remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_activity', 'dashboard', 'normal'); // since 3.8
  remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' ); // gravity forms
  remove_meta_box( 'tribe_dashboard_widget', 'dashboard', 'normal' ); // modern tribe events calendar
  remove_meta_box( 'mandrill_widget', 'dashboard', 'normal' ); // mandrill
  remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' ); // yoast seo
  remove_meta_box( 'wpe_dify_news_feed', 'dashboard', 'normal' ); // wp engine
}
add_action( 'admin_init', 'll_remove_dashboard_meta' );

/**
 * Dashboard Help Widget
 */
add_action('wp_dashboard_setup', 'll_dashboard_widgets');

function ll_dashboard_widgets() {
  global $wp_meta_boxes;
  add_meta_box('id', 'Support', 'll_dashboard_help', 'dashboard', 'normal', 'high');
}

add_action( 'in_admin_header','ll_in_admin_header' );
function ll_in_admin_header() {
  include_once( get_stylesheet_directory() . '/assets/img/symbol-defs.svg' );
}

function ll_dashboard_help() {
    ?>
        <h2 style="margin-bottom: 0; color: #D54E21;">Need some help?</h2>

        <p>If you have questions or concerns about the following, please let us know and we'll be happy to assist you :)</p>

        <ul style="list-style: disc; padding-left: 30px;">

            <li>Updating the site</li>

            <li>Adding / removing content</li>

        </ul>

        <hr>

        <strong>Chad McElwain</strong>

        <br />

        <a href="mailto:hellojustchad@gmail.com">
            hellojustchad@gmail.com
        </a>

        <br />

        913.413.3522
        
    <?php
}

/**
 * Hide page editor from template(s)
 */
function ll_remove_editor() {
  // if post not set, just return
  // fix when post not set, throws PHP's undefined index warning
  if ( isset($_GET['post']) ) {
    $post_id = $_GET['post'];
  } else if ( isset($_POST['post_ID']) ) {
    $post_id = $_POST['post_ID'];
  } else {
    return;
  }
  $template = get_post_meta($post_id, '_wp_page_template', true);
  if ( $template == 'templates/template-name.php' ) {
    remove_post_type_support('page', 'editor');
  }
}
add_action('init', 'll_remove_editor');

function html5_insert_image($html, $id, $caption, $title, $align, $url, $size, $alt ) {
  //Always return an image with a <figure> tag, regardless of link or caption

  //Grab the image tag
  $image_tag = get_image_tag($id, '', $title, $align, $size);

  //Let's see if this contains a link
  $linkptrn = "/<a[^>]*>/";
  $found = preg_match($linkptrn, $html, $a_elem);

  // If no link, do nothing
  if($found > 0) {
    $a_elem = $a_elem[0];
  } else {
    $a_elem = "";
  }

  // Set up the attributes for the caption <figure>
  $attributes  = (!empty($id) ? ' id="attachment_' . esc_attr($id) . '"' : '' );
  $attributes .= ' class="thumbnail wp-caption ' . 'align'.esc_attr($align) . '"';

  $output  = '<figure' . $attributes .'>';

  //add the image back in
  $output .= $a_elem;
  $output .= $image_tag;

  if($a_elem != "") {
    $output .= '</a>';
  }

  if ($caption) {
    $output .= '<figcaption class="caption wp-caption-text">'.$caption.'</figcaption>';
  }
  $output .= '</figure>';

  return $output;
}

add_filter('image_send_to_editor', 'html5_insert_image', 10, 9);
add_filter( 'disable_captions', '__return_true' );

/*
 * Change WordPress default gallery output
 * http://wpsites.org/?p=10510/
 */
add_filter('post_gallery', 'll_gallery_output', 10, 2);
function ll_gallery_output($output, $attr) {
  global $post;

  if (isset($attr['orderby'])) {
      $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
      if (!$attr['orderby'])
          unset($attr['orderby']);
  }

  extract(shortcode_atts(array(
      'order' => 'ASC',
      'orderby' => 'menu_order ID',
      'id' => $post->ID,
      'itemtag' => 'dl',
      'icontag' => 'dt',
      'captiontag' => 'dd',
      'columns' => 3,
      'size' => 'thumbnail',
      'include' => '',
      'exclude' => ''
  ), $attr));

  $id = intval($id);
  if ('RAND' == $order) $orderby = 'none';

  if (!empty($include)) {
      $include = preg_replace('/[^0-9,]+/', '', $include);
      $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

      $attachments = array();
      foreach ($_attachments as $key => $val) {
          $attachments[$val->ID] = $_attachments[$key];
      }
  }

  if (empty($attachments)) return '';

  // Here's your actual output, you may customize it to your need
  $output = "<div class=\"gallery\">\n";

  // Now you loop through each attachment
  foreach ($attachments as $id => $attachment) {
      // Fetch all data related to attachment
      $img = wp_prepare_attachment_for_js($id);

      // If you want a different size change 'large' to eg. 'medium'
      $popup = $img['sizes']['full']['url'];
      $url = $img['sizes']['medium']['url'];
      $height = $img['sizes']['medium']['height'];
      $width = $img['sizes']['medium']['width'];
      $alt = $img['alt'];

      // Store the caption
      $caption = $img['caption'];

      $output .= "<figure class=\"gallery-item\">\n";
      $output .= "<a class=\"gallery-item-popup\" href=\"{$popup}\">\n";
      $output .= "<img src=\"{$url}\" width=\"{$width}\" height=\"{$height}\" alt=\"{$alt}\" />\n";
      $output .= "</a>\n";
      $output .= "</figure>\n";
  }

  $output .= "</div>\n";
  return $output;
}

//enqueue our admin javascript/styles
function ll_admin_enqueue_scripts() {
  $screen = get_current_screen();
  wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css', '', '5.5.0', 'all');
  wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap', false );

  if ( WP_ENV === 'development' ) {
    wp_enqueue_style( 'admin-css', get_template_directory_uri().'/assets/css/admin.min.css' );
    wp_enqueue_script('admin-js', get_template_directory_uri() . '/assets/js/admin.min.js', 'jquery', '', true);
  } else {
    $get_assets = file_get_contents(get_stylesheet_directory() . '/assets/mix-manifest.json' );
    $assets     = json_decode($get_assets, true);
    wp_enqueue_style( 'admin-css', get_template_directory_uri().'/assets'.$assets['/css/admin.min.css'] );
    wp_enqueue_script('admin-js', get_template_directory_uri() . '/assets'. $assets['/js/admin.min.js'], 'jquery', '', true);
  }

  wp_localize_script( 'admin-js', 'site_info', array(
    'url' => home_url(),
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'screen' => $screen->base,
    'wpApiSettings' => array(
      'root'        => esc_url_raw( rest_url() ),
      'll'          => esc_url_raw( rest_url() ) .'ll/api/v1/',
      'nonce'       => wp_create_nonce( 'wp_rest' ),
    )
  ) );
}
add_action('admin_enqueue_scripts', 'll_admin_enqueue_scripts');

//Populate select with available gravity forms
add_filter('acf/load_field/type=select', 'll_gravity_form_type_select', 99);
function ll_gravity_form_type_select($field) {
  global $post;
  if ( !is_admin() || !class_exists( 'RGFormsModel' ) ) {
    return $field;
  }

  if ( strpos( $field['name'], 'form_id' ) !== false ) {

    //empty out choices just in case
    $field['choices'] = array();

    $forms = RGFormsModel::get_forms( null, 'title' );

    if ( $forms ) {
      foreach ($forms as $key => $form) {
        $field['choices'][$form->id] = $form->title;
      };
    }

  }
  return $field;
}

function ll_show_graphical_icon_selector( $field ) {
  if ( strpos( $field['_name'], 'svg_icon' ) !== false ) :
    $file_path = get_template_directory_uri() . '/assets/img/symbol-defs.svg';
    $icon_data = simplexml_load_string( file_get_contents( $file_path,false, stream_context_create( array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false))) ) );
    if ( $icon_data ) {
      $icon_list = $icon_data->defs;
      if ( $icon_list ) {
        $icon_list = (array) $icon_list;
        $icon_list = $icon_list['symbol'];
      }
    }
  ?>
    <div class="icon-preview" style="margin-top: 1rem; position: relative; width: 100%;">
      <?php foreach($icon_list as $key => $icon) : ?>
      <button class="icon-button" style="padding: 5px; margin: 3px; background: transparent; cursor: pointer;" data-title="<?php echo $icon->title ?>">
        <svg width="1.5rem" height="1.5rem" version="1.1" xmlns="http://www.w3.org/2000/svg" id="<?php echo $icon['id'] ?>" title="<?php echo $icon->title ?>" viewBox="<?php echo $icon['viewBox'] ?>" style="display: block;">
          <?php foreach($icon->path as $path): ?>
            <path d="<?php echo $path['d']?>" />
          <?php endforeach; ?>
        </svg>
      </button>
      <?php endforeach; ?>
    </div>
  <?php endif;
}
add_action('acf/render_field/type=select', 'll_show_graphical_icon_selector', 10);

/*
 * Populate ACF Select field with symbol-defs.svg
 */
add_filter('acf/load_field/type=button_group', 'll_icon_type_select', 99);
function ll_icon_type_select($field) {
  global $post;
  if ( !is_admin() || !function_exists('get_icon_list') ) {
    return $field;
  }

  if ( strpos( $field['name'], 'svg_icon' ) !== false ) {

    //empty out choices just in case
    $field['choices'] = array();

    $icons = get_icon_list();

    if ( $icons ) {
      foreach ($icons as $key => $icon) {
        $field['choices'][$icon] = '<svg class="icon icon-'.$icon.'" aria-hidden="true"><use xlink:href="#icon-'.$icon.'"></use></svg>';
      };
    }
  }
  return $field;
}

/*
 * Allow SVGs to be uploaded to Media Libarary
 */
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  $mimes['vcf'] = 'text/vcard';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


function ll_max_image_size_check( $file ) {
  $size = $file['size'];
  $size = $size / 1024;
  $type = $file['type'];
  $is_image = strpos( $type, 'image' ) !== false;
  $limit = 1000;
  $limit_output = '1000kb';
  $max_size = 2000;
  $img_sizes = getimagesize($file['tmp_name']);

  if ( $is_image ) {

    if ( $size > $limit ) {
      $file['error'] = 'Image files must be smaller than ' . $limit_output;
    }

    if ( $img_sizes[0] > $max_size || $img_sizes[1] > $max_size ) {
      $file['error'] = 'Image can not be wider or taller than ' . $max_size .'px';
    }
  }

  return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'll_max_image_size_check' );

add_filter( 'acf/fields/flexible_content/layout_title', function($title, $field, $layout, $i) {
  return $title . ' <span class="hidden component-key" data-key="'.sanitize_title( $title ).'"></span>';
}, 99, 4 );

/*
 * Add ll prefixed classes to body
 */
add_filter( 'admin_body_class', function( $classes ) {
  $user = wp_get_current_user();
  $classes .= ' ll-' . $user->roles[0];

  return $classes;
}, 99, 1 );


function create_ACF_meta_in_REST() {
  $postypes_to_exclude = ['acf-field-group','acf-field'];
  $extra_postypes_to_include = ['ll_promotion'];
  $post_types = array_diff(get_post_types(["_builtin" => false], 'names'),$postypes_to_exclude);

  array_push($post_types, $extra_postypes_to_include);

  foreach ($post_types as $post_type) {
    register_rest_field( $post_type, 'ACF', [
      'get_callback'    => 'expose_ACF_fields',
      'schema'          => null,
    ]
  );
  }
}

function expose_ACF_fields( $object ) {
    $ID = $object['id'];
    return get_fields($ID);
}

add_action( 'rest_api_init', 'create_ACF_meta_in_REST' );
