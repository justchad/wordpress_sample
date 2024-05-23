<?php

/**
 * Define Utility functions.
 *
 * @since      1.0.0
 * @package    LL_ANALYTICS
 * @subpackage LL_ANALYTICS/includes
 * @author     Chad McElwain
 */
class LL_ANALYTICS_UTILITIES {

  public function __construct() {
    $this->environment     = get_field( 'global_environment', 'option' );
  }

  public function set_global_page_id() {
    global $post;

    if( isset( $post->ID ) ){
        $this->page_id = $post->ID;
    }

    if ( is_home() ) {
      $this->page_id = get_option( 'page_for_posts' );
    }

    /*
     * Allow additonal checks to be added against $this->page_id
     */

    if ( property_exists( 'apply_filters', 'll_analytics_set_global_page_id' ) ) {
        $this->page_id = apply_filters('ll_analytics_set_global_page_id', $this->page_id);
    }
  }

  public function output_analytics( $area ) {
    $output = '';
    if ( $this->environment == 'development' )
      return;

    switch ( $area ) {
      case 'head':
        add_action( 'wp_head', function() {
          echo $this->process_scripts( 'll_head_scripts' );
        }, 10 );
        break;
      case 'beginning_body':
        add_action( 'get_header', function() {
          echo $this->process_scripts( 'll_beginning_body_scripts' );
        }, 99 );
        break;
      case 'end_body':
        add_action( 'wp_footer', function() {
          echo $this->process_scripts( 'll_end_body_scripts' );
        }, 99 );
      default:
        break;
    }
  }

  public function process_scripts( $area ) {
    $scripts = get_field( $area, 'option' );
    if ( !is_array( $scripts ) )
      return;

      $output = null;

    foreach ( $scripts as $key => $script ) {
      if ( !is_array( $script ) || empty( $script['scripts_snippets'] ) )
        continue;

      if ( !$script['specific_page'] || ( is_array( $script['specific_page'] ) && in_array($this->page_id, $script['specific_page'] ) ) ) {
        $output .= $script['scripts_snippets'];
      }
    }

    return $output;
  }

  public function output_custom_events() {
    if ( $this->environment == 'development' )
      return;

    $custom_events = get_field( 'll_analytics_events', 'option' );
    ?>
    <script type="text/javascript" id="ll-analytics-custom-ga-events">
      if ( typeof gtag === 'function' ) {const telLinks = document.querySelectorAll( '[href^="tel:"]' );if (telLinks ) {for( let i = 0; i<telLinks.length; i++ ) {telLinks[i].addEventListener( 'click', function(event ) {gtag( 'event', 'click', {'event_category': 'phone','event_label': 'Clicked Phone Number','value': this.attributes.href.value} );} );}}
        <?php if ( $custom_events && is_array( $custom_events ) ) : ?>
          let eventTarget;
          <?php foreach( $custom_events as $event ) : ?>
            eventTarget = document.querySelectorAll( '<?php echo $event['target']; ?>' );if ( eventTarget ) {for( let i = 0; i<eventTarget.length; i++ ) {eventTarget[i].addEventListener( 'click', function( event ) {gtag( 'event', '<?php echo $event['action']; ?>', {'event_category': '<?php echo $event['category']; ?>','event_label': '<?php echo $event['label']; ?>','value': '<?php echo $event['value']; ?>'} );});}}
          <?php endforeach; ?>
        <?php endif; ?>
      }
    </script>
    <?php
    return;
  }

}
