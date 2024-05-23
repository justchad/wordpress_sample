<?php
    /**
     * ArrowFilters
     */
    class ArrowFilters
    {
        public function __construct()
        {
            $taxonomies = get_transient( 'inventory_filters' );

            if ( ! $taxonomies ) {
                $taxonomies = get_object_taxonomies( 'll_inventory', 'objects' );
                set_transient( 'inventory_filters', $taxonomies );
            }

            $taxonomies = collect( $taxonomies );

            $display_options = ll_safe_decode( get_option( 'arrow_filter_display_options' ) );

            $this->fields = [];

            $taxonomies->each( function( $taxonomy ) use ( $display_options ) {

                $name = $taxonomy->name;

                if ( $taxonomy->name == 'll_inventory_invbrnid' ) {
                    $name = 'll_inventory_location';
                }

                $field = (object) [
                    'parameter' => str_replace( 'll_inventory_', '', $name ),
                    'label'     => $taxonomy->label,
                    'id'        => $name,
                    'prefix'    => '',
                    'type'      => ( isset( $display_options[ $taxonomy->name . '_display_type' ] ) ) ? sanitize_title( $display_options[ $taxonomy->name . '_display_type' ] ) : 'button',
                    'context'   => ( isset( $display_options[ $taxonomy->name . '_display_context' ] ) ) ? sanitize_title( $display_options[ $taxonomy->name . '_display_context' ] ) : 'additional',
                    'options'   => (object) collect( get_terms( [

                        'taxonomy'      => $taxonomy->name,
                        'hide_empty'    => false

                    ] ) )->map( function( $term ) {

                        $association = trim( get_field( 'association', $term ) );

                        $association_format = trim( get_field( 'association_formatted_as', $term ) );

                        return (object) [
                            'label'                 => $term->name,
                            'value'                 => LL_StringUtil::uppercase( $term->description ),
                            'slug'                  => $term->slug . '_' . $term->term_id,
                            'id'                    => $term->term_id,
                            'association'           => $association,
                            'association_format'    => $association_format
                        ];

                    } )->all()
                ];

                if ( $field->context == 'additional' ) {
                    $field->type = 'button';
                }

                $field->template = 'templates/partials/filters/' . $field->type . '.php';

                $this->fields[] = $field;

            });
        }
    }
