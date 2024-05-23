<?php
/**
* Tabbed Tables
* -----------------------------------------------------------------------------
*
* Tabbed Tables component
*/

$defaults = [
  'tabs'    => []
];

$component_data = ll_parse_args( $component_data, $defaults );
?>

<?php
/**
 * Any additional classes to apply to the main component container.
 *
 * @var array
 * @see args['classes']
 */
$classes = ( isset( $component_args['classes'] ) ? $component_args['classes'] : array() );
/**
 * ID to apply to the main component container.
 *
 * @var array
 * @see args['id']
 */
$component_id   = ( isset( $component_args['id'] ) ? $component_args['id'] : false );
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="tabbed-tables <?php echo ll_format_bg( 'bg-white' ); ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="tabbed-tables">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full lg:w-10/12 xl:w-8/12">

        <div class="tabs-list mb-8" role="tablist" aria-label="<?php echo ( isset( $tab['tab_title'] ) ) ? $tab['tab_title'] : null; ?>">

          <div class="selector"></div>

          <?php foreach ( $component_data['tabs'] as $key => $tab ) : ?>

            <div class="tab-item">

              <a href="#content-<?php echo $key; ?>" class="tab-title" role="tab" aria-label="<?php echo $tab['tab_title']; ?>" data-toggle-target="#content-<?php echo $key; ?>" data-toggle-class="is-active" data-toggle-radio-group="tabbed-tables-<?php echo $component_id; ?>" <?php echo $key == 0 ? 'data-toggle-is-active' : ''; ?> aria-controls="content-<?php echo $key; ?>" aria-selected="<?php echo $key == 0 ? 'true' : 'false'; ?>">

                <?php echo $tab['tab_title']; ?>

              </a>

            </div> <!-- /.ll-tabbed-content__nav-item -->

          <?php endforeach; ?>

        </div> <!-- /.tabs-list -->

        <div class="tab-panels">

          <?php foreach ( $component_data['tabs'] as $key => $tab ) : ?>

            <div class="tab-content-wrapper" id="content-<?php echo $key; ?>" tabindex="0" role="tabpanel" aria-label="<?php echo $tab['tab_title']; ?>" <?php echo $key == 0 ? '' : 'hidden'; ?>>

              <h2 class="hdg-5 mb-8 text-center"><?php echo $tab['content_title'] ?: $tab['tab_title'] ?></h2>

              <div class="tab-rows">

                <?php foreach ( $tab['rows'] as $row_key => $row ) : ?>

                  <div class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 <?php echo $row_key == 0 ? '' : 'mt-2'; ?>">

                    <span class="font-bold w-7/12"><?php echo $row['label']; ?></span>

                    <span class="w-5/12"><?php echo $row['value']; ?></span>

                  </div> <!-- /.flex -->

                <?php endforeach; ?>

              </div> <!-- /.tab-rows -->

            </div> <!-- /.tab-content-wrapper -->

          <?php endforeach; ?>

        </div> <!-- /.tab-panels -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.tabbed-tables -->
