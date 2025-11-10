<?php
/**
 * Shortcode Handler
 *
 * Handles the [inline_related_posts] shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IRP_Shortcode {

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_shortcode( 'inline_related_posts', array( $this, 'render_shortcode' ) );
        add_shortcode( 'irp', array( $this, 'render_shortcode' ) ); // Short alias
    }

    /**
     * Render shortcode
     *
     * Usage examples:
     * [inline_related_posts]
     * [inline_related_posts ids="1,2,3"]
     * [inline_related_posts slugs="post-slug-1,post-slug-2"]
     * [inline_related_posts posts="8" columns="4"]
     * [inline_related_posts layout="card" show_excerpt="true"]
     * [inline_related_posts layout="thumbnail" show_date="false"]
     * [irp ids="1,2,3" layout="list"]
     *
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function render_shortcode( $atts ) {
        // Parse shortcode attributes
        $atts = shortcode_atts(
            array(
                // Post selection
                'ids'           => '',
                'slugs'         => '',
                'posts'         => 6,
                'columns'       => 3,
                'post_type'     => 'post',
                'orderby'       => 'date',
                'order'         => 'DESC',
                'category'      => '',
                'tag'           => '',
                'exclude'       => '',

                // Display options
                'layout'        => 'grid',
                'show_excerpt'  => true,
                'show_date'     => true,
                'show_author'   => false,
                'show_category' => true,
                'excerpt_length' => 120,
                'image_size'    => 'medium',
                'class'         => '',
            ),
            $atts,
            'inline_related_posts'
        );

        // Sanitize and prepare attributes
        $query_args = array(
            'ids'         => sanitize_text_field( $atts['ids'] ),
            'slugs'       => sanitize_text_field( $atts['slugs'] ),
            'posts'       => intval( $atts['posts'] ),
            'columns'     => intval( $atts['columns'] ),
            'post_type'   => sanitize_text_field( $atts['post_type'] ),
            'orderby'     => sanitize_text_field( $atts['orderby'] ),
            'order'       => sanitize_text_field( $atts['order'] ),
            'category'    => sanitize_text_field( $atts['category'] ),
            'tag'         => sanitize_text_field( $atts['tag'] ),
            'exclude'     => sanitize_text_field( $atts['exclude'] ),
        );

        $display_args = array(
            'layout'        => IRP_Template_Loader::sanitize_layout( $atts['layout'] ),
            'columns'       => intval( $atts['columns'] ),
            'show_excerpt'  => filter_var( $atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN ),
            'show_date'     => filter_var( $atts['show_date'], FILTER_VALIDATE_BOOLEAN ),
            'show_author'   => filter_var( $atts['show_author'], FILTER_VALIDATE_BOOLEAN ),
            'show_category' => filter_var( $atts['show_category'], FILTER_VALIDATE_BOOLEAN ),
            'excerpt_length' => intval( $atts['excerpt_length'] ),
            'image_size'    => sanitize_text_field( $atts['image_size'] ),
            'class'         => sanitize_html_class( $atts['class'] ),
        );

        // Get posts
        $posts = IRP_Post_Query::get_posts( $query_args );

        // Render output
        $output = IRP_Template_Loader::render( $posts, $display_args );

        return $output;
    }

    /**
     * Get shortcode usage help
     *
     * @return string Help text
     */
    public static function get_help_text() {
        return __(
            'Usage: [inline_related_posts] or [irp]

Attributes:
- ids: Comma-separated post IDs (e.g., ids="1,2,3")
- slugs: Comma-separated post slugs (e.g., slugs="post-one,post-two")
- posts: Number of posts to display (default: 6)
- columns: Number of columns (default: 3)
- layout: Layout style - grid, card, list, thumbnail, link-only, minimal (default: grid)
- show_excerpt: Show excerpt (true/false, default: true)
- show_date: Show date (true/false, default: true)
- show_author: Show author (true/false, default: false)
- show_category: Show category (true/false, default: true)
- category: Filter by category slug
- tag: Filter by tag slug
- exclude: Comma-separated post IDs to exclude
- class: Additional CSS class

Examples:
[inline_related_posts]
[inline_related_posts ids="1,2,3"]
[inline_related_posts layout="card" posts="8" columns="4"]
[irp slugs="post-one,post-two" layout="list"]',
            'inline-related-posts'
        );
    }
}
