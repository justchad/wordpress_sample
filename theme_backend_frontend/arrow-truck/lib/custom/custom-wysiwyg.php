<?php
/**
 * Adds the custom formats button back into tinymce
 *
 * @param  array $buttons The available buttons
 * @return array          The buttons to display
 */
function ll_new_mce_button( $buttons ) {
  array_unshift( $buttons, 'styleselect' );
  // var_error_log($buttons);
  return $buttons;
}
add_filter( 'mce_buttons_2', 'll_new_mce_button' );

/**
 * adds custom formats to the formats selection
 * on the tinymce editor
 *
 * @param  array $data Tinymce data
 * @return array       Tinyce data
 */
function ll_format_tinymce( $data ) {
    $style_formats = array(
    array(
      'title'    => 'Heading Sizes',
      'items'  => array(
        array(
          'title'    => 'Heading Style One : 64px',
          'classes'  => 'hdg-1',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'Heading Style Two : 48px',
          'classes'  => 'hdg-2',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'Heading Style Three : 36px',
          'classes'  => 'hdg-3',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'Heading Style Four : 30px',
          'classes'  => 'hdg-4',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'Heading Style Five : 24px',
          'classes'  => 'hdg-5',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'Heading Style Six : 18px',
          'classes'  => 'hdg-6',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'With Background',
          'classes'  => 'with-bg inline-block',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        )
      ),
    ),
    array(
      'title'    => 'Body Text',
      'items'  => array(
        array(
          'title'    => 'Regular Paragraph : 16px',
          'classes'  => 'paragraph-default',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'Large Paragraph Text : 18px',
          'classes'  => 'paragraph-large',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'Small Paragraph Text : 14px',
          'classes'  => 'paragraph-small',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => 'Extra Small Paragraph Text : 12px',
          'classes'  => 'paragraph-xsmall',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
      ),
    ),
    array(
      'title' => 'Buttons & Links',
      'items' => array(
        array(
          'title'    => 'Standard Button',
          'classes'  => 'btn',
          'selector' => 'a',
          'wrapper'  => false,
          'attributes' => array(
            'data-arrow-btn' => 'true'
          )
        ),
        array(
          'title' => 'Button Group',
          'classes' => 'grid grid-flow-col gap-5',
          'wrapper' => true,
          'block' => 'div',
        )
      ),
    ),
    array(
      'title' => 'Weights',
      'items' => array(
        array(
          'title'    => 'Normal',
          'classes'  => 'font-normal',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
        ),
        array(
          'title'    => 'Medium',
          'classes'  => 'font-medium',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
        ),
        array(
          'title'    => 'Semibold',
          'classes'  => 'font-semibold',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
        ),
        array(
          'title'    => 'Bold',
          'classes'  => 'font-bold',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
        )
      ),
    ),
    array(
      'title' => 'Colors',
      'items' => array(
        array(
          'title'    => 'White',
          'classes'  => 'text-white',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
        ),
        array(
          'title'    => 'Primary',
          'classes'  => 'text-brand-primary',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
        ),
        array(
          'title'    => 'Light Gray',
          'classes'  => 'text-gray-200',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
        )
      ),
    ),
    array(
      'title' => 'Highlight',
      'items' => array(
        array(
          'title'    => 'White',
          'classes'  => 'text-white',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
          'inline'   => 'span'
        ),
        array(
          'title'    => 'Primary',
          'classes'  => 'text-brand-primary',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false,
          'inline'   => 'span'
        )
      ),
    ),
    array(
      'title'    => 'Spacing Top & Bottom',
      'items'  => array(
        array(
          'title'    => '0',
          'classes'  => 'my-0',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '1px',
          'classes'  => 'my-px',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '4px',
          'classes'  => 'my-1',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '8px',
          'classes'  => 'my-2',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '12px',
          'classes'  => 'my-3',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '16px',
          'classes'  => 'my-4',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '20px',
          'classes'  => 'my-5',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '24px',
          'classes'  => 'my-6',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '32px',
          'classes'  => 'my-8',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '40px',
          'classes'  => 'my-10',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '48px',
          'classes'  => 'my-12',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '64px',
          'classes'  => 'my-16',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '80px',
          'classes'  => 'my-20',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '96px',
          'classes'  => 'my-24',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '128px',
          'classes'  => 'my-32',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '160px',
          'classes'  => 'my-40',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '192px',
          'classes'  => 'my-48',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '224px',
          'classes'  => 'my-56',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '256px',
          'classes'  => 'my-64',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
      ),
    ),
    array(
      'title'    => 'Spacing Top',
      'items'  => array(
        array(
          'title'    => '0',
          'classes'  => 'mt-0',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '1px',
          'classes'  => 'mt-px',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '4px',
          'classes'  => 'mt-1',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '8px',
          'classes'  => 'mt-2',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '12px',
          'classes'  => 'mt-3',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '16px',
          'classes'  => 'mt-4',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '20px',
          'classes'  => 'mt-5',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '24px',
          'classes'  => 'mt-6',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '32px',
          'classes'  => 'mt-8',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '40px',
          'classes'  => 'mt-10',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '48px',
          'classes'  => 'mt-12',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '64px',
          'classes'  => 'mt-16',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '80px',
          'classes'  => 'mt-20',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '96px',
          'classes'  => 'mt-24',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '128px',
          'classes'  => 'mt-32',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '160px',
          'classes'  => 'mt-40',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '192px',
          'classes'  => 'mt-48',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '224px',
          'classes'  => 'mt-56',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '256px',
          'classes'  => 'mt-64',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
      ),
    ),
    array(
      'title'    => 'Spacing Bottom',
      'items'  => array(
        array(
          'title'    => '0',
          'classes'  => 'mb-0',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '1px',
          'classes'  => 'mb-px',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '4px',
          'classes'  => 'mb-1',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '8px',
          'classes'  => 'mb-2',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '12px',
          'classes'  => 'mb-3',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '16px',
          'classes'  => 'mb-4',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '20px',
          'classes'  => 'mb-5',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '24px',
          'classes'  => 'mb-6',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '32px',
          'classes'  => 'mb-8',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '40px',
          'classes'  => 'mb-10',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '48px',
          'classes'  => 'mb-12',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '64px',
          'classes'  => 'mb-16',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '80px',
          'classes'  => 'mb-20',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '96px',
          'classes'  => 'mb-24',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '128px',
          'classes'  => 'mb-32',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '160px',
          'classes'  => 'mb-40',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '192px',
          'classes'  => 'mb-48',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '224px',
          'classes'  => 'mb-56',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
        array(
          'title'    => '256px',
          'classes'  => 'mb-64',
          'selector' => 'h1, h2, h3, h4, h5, h6, p, a, span, li',
          'wrapper'  => false
        ),
      ),
    ),
  );

  $data['style_formats'] = json_encode( $style_formats );
  return $data;
}
add_filter( 'tiny_mce_before_init', 'll_format_tinymce' );

/**
 * add visual button
 * for added tinymce plugins
 */
function add_tiny_mce_plugins_button( $buttons ) {
   array_push( $buttons, 'anchor' );
   return $buttons;
}
add_filter( 'mce_buttons', 'add_tiny_mce_plugins_button' );

/**
 * Add tinymce plugins assuming they live in
 * /lib/tinymce
 */
function add_tinymce_plugins( $plugins ) {
    $plugins['anchor']        = get_template_directory_uri() . '/lib/tinymce/anchor/plugin.min.js';
    $plugins['remove_figure'] = get_template_directory_uri() . '/lib/tinymce/remove_figure/plugin.js';
    $plugins['blockcolor']    = get_template_directory_uri() . '/lib/tinymce/wysiwyg_background_color/plugin.js';
    $plugins['buttonGroup']   = get_template_directory_uri() . '/lib/tinymce/buttonGroup/plugin.js';
    return $plugins;
}
add_filter( 'mce_external_plugins', 'add_tinymce_plugins' );
