<?php
/**
 * Template Loader
 *
 * Handles loading and rendering different post layout templates
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IRP_Template_Loader {

    /**
     * Available layout types
     */
    private static $available_layouts = array(
        'link-only',
        'thumbnail',
        'card',
        'grid',
        'list',
        'minimal',
    );

    /**
     * Render posts with specified layout
     *
     * @param array  $posts Array of WP_Post objects
     * @param array  $args  Display arguments
     * @return string HTML output
     */
    public static function render( $posts, $args = array() ) {
        if ( empty( $posts ) ) {
            return self::render_no_posts_message();
        }

        $defaults = array(
            'layout'        => 'grid',
            'columns'       => 3,
            'show_excerpt'  => true,
            'show_date'     => true,
            'show_author'   => false,
            'show_category' => true,
            'excerpt_length' => 120,
            'image_size'    => 'medium',
            'class'         => '',
        );

        $args = wp_parse_args( $args, $defaults );

        // Validate layout
        if ( ! in_array( $args['layout'], self::$available_layouts, true ) ) {
            $args['layout'] = 'grid';
        }

        // Start output buffering
        ob_start();

        // Generate container classes
        $container_classes = self::get_container_classes( $args );

        echo '<div class="' . esc_attr( $container_classes ) . '" data-columns="' . esc_attr( $args['columns'] ) . '">';

        // Load template for each post
        foreach ( $posts as $post ) {
            $post_data = IRP_Post_Query::get_post_data( $post );
            self::load_template( $args['layout'], $post_data, $args );
        }

        echo '</div>';

        return ob_get_clean();
    }

    /**
     * Generate container CSS classes
     *
     * @param array $args Display arguments
     * @return string Space-separated class names
     */
    private static function get_container_classes( $args ) {
        $classes = array(
            'irp-posts-container',
            'irp-layout-' . $args['layout'],
            'irp-columns-' . $args['columns'],
        );

        if ( ! empty( $args['class'] ) ) {
            $classes[] = $args['class'];
        }

        return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
    }

    /**
     * Load template file
     *
     * @param string $layout    Layout name
     * @param array  $post_data Post data
     * @param array  $args      Display arguments
     */
    private static function load_template( $layout, $post_data, $args ) {
        // Allow themes to override templates
        $template_path = locate_template( array(
            'interactive-related-posts/' . $layout . '.php',
            'interactive-related-posts/templates/' . $layout . '.php',
        ) );

        // Use plugin template if theme template not found
        if ( empty( $template_path ) ) {
            $template_path = IRP_PLUGIN_DIR . 'templates/' . $layout . '.php';
        }

        // Check if template exists
        if ( ! file_exists( $template_path ) ) {
            $template_path = IRP_PLUGIN_DIR . 'templates/grid.php';
        }

        // Extract variables for template
        extract( array(
            'post_data' => $post_data,
            'args'      => $args,
        ) );

        include $template_path;
    }

    /**
     * Render no posts message
     *
     * @return string HTML output
     */
    private static function render_no_posts_message() {
        return '<div class="irp-no-posts">' . esc_html__( 'No posts found.', 'interactive-related-posts' ) . '</div>';
    }

    /**
     * Get available layouts
     *
     * @return array Available layout names
     */
    public static function get_available_layouts() {
        return apply_filters( 'irp_available_layouts', self::$available_layouts );
    }

    /**
     * Sanitize layout value
     *
     * @param string $layout Layout name
     * @return string Sanitized layout name
     */
    public static function sanitize_layout( $layout ) {
        $available = self::get_available_layouts();

        if ( in_array( $layout, $available, true ) ) {
            return $layout;
        }

        return 'grid';
    }
}
