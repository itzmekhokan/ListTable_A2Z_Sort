<?php

global $current_screen;
if ( $current_screen && isset( $current_screen->id ) ) {

    switch ( $current_screen->id ) {
        case 'users':
            class WP_A2Z_Base_List_Table extends WP_Users_List_Table { 

                /**
                 * Helper to get default query args.
                 */
                protected function get_az_default_query_args() {
                    return array();
                }

                /**
                 * Helper to create A to Z filter links with start_with params.
                 *
                 * @param string[] $args      Associative array of URL parameters for the link.
                 * @param string   $link_text Link text.
                 * @param string   $css_class Optional. Class attribute. Default empty string.
                 * @return string The formatted link string.
                 */
                protected function get_az_start_with_link( $args, $link_text, $css_class = '' ) {
                    $url = 'users.php';
                    $url = add_query_arg( $args, $url );
                    
                    $class_html   = '';
                    $aria_current = '';

                    if ( ! empty( $css_class ) ) {
                        $class_html = sprintf(
                            ' class="%s"',
                            esc_attr( $css_class )
                        );

                        if ( 'current' === $css_class ) {
                            $aria_current = ' aria-current="page"';
                        }
                    }

                    return sprintf(
                        '<a href="%s"%s%s>%s</a>',
                        esc_url( $url ),
                        $class_html,
                        $aria_current,
                        $link_text
                    );
                }
            }
            break;
        default:
            $post_type = $current_screen->post_type;
            if ( post_type_exists( $post_type ) ) {
                class WP_A2Z_Base_List_Table extends WP_Posts_List_Table { 

                    /**
                     * Helper to get default query args.
                     */
                    protected function get_az_default_query_args() {
                        $post_type = $this->screen->post_type;
                        return array(
                            'post_type'  => $post_type,
                        );
                    }
    
                    /**
                     * Helper to create A to Z filter links with start_with params.
                     *
                     * @param string[] $args      Associative array of URL parameters for the link.
                     * @param string   $link_text Link text.
                     * @param string   $css_class Optional. Class attribute. Default empty string.
                     * @return string The formatted link string.
                     */
                    protected function get_az_start_with_link( $args, $link_text, $css_class = '' ) {
                        $url = 'edit.php';
                        $url = add_query_arg( $args, $url );
    
                        $class_html   = '';
                        $aria_current = '';
    
                        if ( ! empty( $css_class ) ) {
                            $class_html = sprintf(
                                ' class="%s"',
                                esc_attr( $css_class )
                            );
    
                            if ( 'current' === $css_class ) {
                                $aria_current = ' aria-current="page"';
                            }
                        }
    
                        return sprintf(
                            '<a href="%s"%s%s>%s</a>',
                            esc_url( $url ),
                            $class_html,
                            $aria_current,
                            $link_text
                        );
                    }
                }
            }
            
            break;
    }
}
