<?php
    /**
    * Fit Image
    * -----------------------------------------------------------------------------
    *
    * Fit Image component
    * fit accepts tailwind classes for object-fit and its variants
    * https://tailwindcss.com/docs/object-fit/#app
    *
    * position accepts tailwind classes for object-position and its variants
    * https://tailwindcss.com/docs/object-position/#app
    *
    */

    $defaults = [
      'image_id'            => null,
      'thumbnail_size'      => 'large',
      'fit'                 => 'object-cover',
      'position'            => 'object-center',
      'default_asset_url'   => 'https://www.arrowtruckhost.com/images/NoImageHead2.jpg'
    ];

    $_fit       = ( ! isset( $fit ) || empty( $fit ) ) ? $defaults[ 'fit' ] : $fit ;
    $_position  = ( ! isset( $position ) || empty( $position ) ) ? $defaults[ 'position' ] : $position ;

    $component_data = ll_parse_args( $component_data, $defaults );
    $classes        = ( isset( $component_args[ 'classes' ] ) ? $component_args[ 'classes' ] : array() );
    $classes[]      = $_fit;
    $classes[]      = $_position;
    $component_id   = ( isset( $component_args[ 'id' ] ) ) ? $component_args[ 'id' ] : false;
?>

<?php if ( ! $component_data[ 'image_id' ] ) return; ?>

    <div class="fit-image <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?>>

        <?php if ( intval( $component_data[ 'image_id' ] ) ) : ?>

            <?php
                echo wp_get_attachment_image(
                    $component_data[ 'image_id' ],
                    $component_data[ 'thumbnail_size' ],
                    false,
                    [
                        'class' => $component_data[ 'fit' ] . ' ' . $_position
                    ]
                );
            ?>

         <?php else : ?>

             <img
                src="<?php echo $component_data[ 'image_id' ] ?>"
                class="<?php echo $component_data[ 'fit' ] . ' ' . $_position; ?>"
                onerror="this.onerror=null; this.src='<?php echo $component_data[ 'default_asset_url' ]; ?>';">

         <?php endif; ?>

    </div>
