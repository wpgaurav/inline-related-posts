<?php
/**
 * Minimal Template
 *
 * Displays posts in a clean, minimal format
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="irp-post-item irp-minimal">
    <article class="irp-minimal-item">
        <h3 class="irp-minimal-title">
            <a href="<?php echo esc_url( $post_data['permalink'] ); ?>">
                <?php echo esc_html( $post_data['title'] ); ?>
            </a>
        </h3>

        <?php if ( ! empty( $args['show_excerpt'] ) && ! empty( $post_data['excerpt'] ) ) : ?>
            <div class="irp-minimal-excerpt">
                <p><?php echo esc_html( $post_data['excerpt'] ); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $args['show_date'] ) ) : ?>
            <div class="irp-minimal-date">
                <?php echo esc_html( $post_data['date'] ); ?>
            </div>
        <?php endif; ?>
    </article>
</div>
