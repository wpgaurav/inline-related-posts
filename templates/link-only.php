<?php
/**
 * Link Only Template
 *
 * Displays posts as simple links
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="irp-post-item irp-link-only">
    <a href="<?php echo esc_url( $post_data['permalink'] ); ?>" class="irp-post-link">
        <span class="irp-post-title"><?php echo esc_html( $post_data['title'] ); ?></span>
        <?php if ( ! empty( $args['show_date'] ) ) : ?>
            <span class="irp-post-date"><?php echo esc_html( $post_data['date'] ); ?></span>
        <?php endif; ?>
    </a>
</div>
