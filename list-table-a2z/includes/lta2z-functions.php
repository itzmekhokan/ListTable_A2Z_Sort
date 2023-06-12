<?php
/**
 * Get settings options.
 *
 * @since 1.0.0
 * 
 * @param  string $key settings field key.
 * @return string $data values.
 */
function lta2z_get_options( $key = '' ) {
    $options = (array) get_option( 'list_table_a2z_options' );
    
    if ( $key ) {
        return isset( $options[$key] ) ? $options[$key] : '';
    }

    return $options;
}
