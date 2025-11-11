<?php
/**
 * Thumbnail Template
 *
 * Displays posts with thumbnail and title
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="irp-post-item irp-thumbnail">
    <a href="<?php echo esc_url( $post_data['permalink'] ); ?>" class="irp-post-link">
        <?php if ( ! empty( $post_data['thumbnail'] ) ) : ?>
            <div class="irp-post-thumbnail">
                <img
                    src="<?php echo esc_url( $post_data['thumbnail']['url'] ); ?>"
                    alt="<?php echo esc_attr( $post_data['thumbnail']['alt'] ?: $post_data['title'] ); ?>"
                    width="<?php echo esc_attr( $post_data['thumbnail']['width'] ); ?>"
                    height="<?php echo esc_attr( $post_data['thumbnail']['height'] ); ?>"
                    loading="lazy"
                />
            </div>
        <?php else : ?>
            <div class="irp-post-thumbnail irp-no-thumbnail">
                <div class="irp-thumbnail-placeholder">
                    <span class="irp-thumbnail-icon">📄</span>
                </div>
            </div>
        <?php endif; ?>

        <div class="irp-post-content">
            <h3 class="irp-post-title"><?php echo esc_html( $post_data['title'] ); ?></h3>

            <?php if ( ! empty( $args['show_date'] ) ) : ?>
                <div class="irp-post-meta">
                    <span class="irp-post-date"><?php echo esc_html( $post_data['date'] ); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </a>
</div>
