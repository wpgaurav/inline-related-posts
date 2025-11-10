<?php
/**
 * Plugin Name: Interactive Related Posts
 * Plugin URI: https://github.com/wpgaurav/interactive-related-posts
 * Description: Display related posts interactively using shortcode or WordPress blocks with multiple layout options. Select posts manually by ID or slug, or show recent posts automatically.
 * Version: 1.0.0
 * Author: WP Gaurav
 * Author URI: https://github.com/wpgaurav
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: interactive-related-posts
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Plugin version
 */
define( 'IRP_VERSION', '1.0.0' );

/**
 * Plugin directory path
 */
define( 'IRP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL
 */
define( 'IRP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main plugin class
 */
class Interactive_Related_Posts {

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
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        require_once IRP_PLUGIN_DIR . 'includes/class-irp-post-query.php';
        require_once IRP_PLUGIN_DIR . 'includes/class-irp-template-loader.php';
        require_once IRP_PLUGIN_DIR . 'includes/class-irp-shortcode.php';
        require_once IRP_PLUGIN_DIR . 'includes/class-irp-block.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Initialize plugin components
     */
    public function init() {
        // Initialize shortcode
        IRP_Shortcode::get_instance();

        // Initialize block
        IRP_Block::get_instance();
    }

    /**
     * Enqueue frontend styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'interactive-related-posts',
            IRP_PLUGIN_URL . 'assets/css/styles.css',
            array(),
            IRP_VERSION,
            'all'
        );
    }
}

/**
 * Initialize the plugin
 */
function interactive_related_posts() {
    return Interactive_Related_Posts::get_instance();
}

// Kick off the plugin
interactive_related_posts();
