<?php

global $current_screen;

if ( $current_screen ) {
    $list_classes_by_screen = LTA2Z()->admin->core_classes_by_screen;

    if ( $list_classes_by_screen ) {
        if ( in_array( $current_screen->id, LTA2Z()->admin->enabled_screen, true ) ) {

            $screen_list_table_info = $list_classes_by_screen[ $current_screen->id ];

            $class_name = $screen_list_table_info['class_name'];
            $required   = $screen_list_table_info['required'];

            if ( ! class_exists( $class_name ) ) {
                require_once ABSPATH . 'wp-admin/includes/class-wp-' . $required . '-list-table.php';
            }

            // extends core list table with base
            include_once LTA2Z_ABSPATH . 'includes/admin/data-table/base-extends-core-table.php';
        }
    }
}
