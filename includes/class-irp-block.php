<?php
/**
 * Gutenberg Block Handler
 *
 * Handles the WordPress Block for interactive related posts
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IRP_Block {

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
        add_action( 'init', array( $this, 'register_block' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
    }

    /**
     * Register the Gutenberg block
     */
    public function register_block() {
        // Check if Gutenberg is available
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        // Register block
        register_block_type(
            'interactive-related-posts/related-posts',
            array(
                'api_version'     => 2,
                'editor_script'   => 'irp-block-editor',
                'editor_style'    => 'irp-block-editor-style',
                'style'           => 'interactive-related-posts',
                'render_callback' => array( $this, 'render_block' ),
                'attributes'      => $this->get_block_attributes(),
            )
        );
    }

    /**
     * Get block attributes schema
     */
    private function get_block_attributes() {
        return array(
            'selectedPosts' => array(
                'type'    => 'array',
                'default' => array(),
                'items'   => array(
                    'type' => 'object',
                ),
            ),
            'posts' => array(
                'type'    => 'number',
                'default' => 6,
            ),
            'columns' => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'layout' => array(
                'type'    => 'string',
                'default' => 'grid',
            ),
            'showExcerpt' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showDate' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showAuthor' => array(
                'type'    => 'boolean',
                'default' => false,
            ),
            'showCategory' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'category' => array(
                'type'    => 'string',
                'default' => '',
            ),
            'tag' => array(
                'type'    => 'string',
                'default' => '',
            ),
            'orderby' => array(
                'type'    => 'string',
                'default' => 'date',
            ),
            'order' => array(
                'type'    => 'string',
                'default' => 'DESC',
            ),
        );
    }

    /**
     * Render block callback
     */
    public function render_block( $attributes ) {
        // Build query args
        $query_args = array(
            'posts'    => $attributes['posts'],
            'columns'  => $attributes['columns'],
            'orderby'  => $attributes['orderby'],
            'order'    => $attributes['order'],
            'category' => $attributes['category'],
            'tag'      => $attributes['tag'],
        );

        // Handle manually selected posts
        if ( ! empty( $attributes['selectedPosts'] ) ) {
            $post_ids = array();
            foreach ( $attributes['selectedPosts'] as $post ) {
                if ( isset( $post['id'] ) ) {
                    $post_ids[] = $post['id'];
                }
            }
            if ( ! empty( $post_ids ) ) {
                $query_args['ids'] = implode( ',', $post_ids );
            }
        }

        // Build display args
        $display_args = array(
            'layout'        => IRP_Template_Loader::sanitize_layout( $attributes['layout'] ),
            'columns'       => intval( $attributes['columns'] ),
            'show_excerpt'  => (bool) $attributes['showExcerpt'],
            'show_date'     => (bool) $attributes['showDate'],
            'show_author'   => (bool) $attributes['showAuthor'],
            'show_category' => (bool) $attributes['showCategory'],
        );

        // Get posts
        $posts = IRP_Post_Query::get_posts( $query_args );

        // Render output
        return IRP_Template_Loader::render( $posts, $display_args );
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'irp-block-editor',
            IRP_PLUGIN_URL . 'blocks/build/index.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-data', 'wp-compose' ),
            IRP_VERSION,
            true
        );

        wp_enqueue_style(
            'irp-block-editor-style',
            IRP_PLUGIN_URL . 'assets/css/editor.css',
            array( 'wp-edit-blocks' ),
            IRP_VERSION
        );

        // Pass data to JavaScript
        wp_localize_script(
            'irp-block-editor',
            'irpBlockData',
            array(
                'layouts' => IRP_Template_Loader::get_available_layouts(),
            )
        );
    }
}
