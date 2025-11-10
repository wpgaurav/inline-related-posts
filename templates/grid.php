<?php
/**
 * Grid Template
 *
 * Displays posts in a responsive grid layout
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="irp-post-item irp-grid">
    <article class="irp-grid-item">
        <a href="<?php echo esc_url( $post_data['permalink'] ); ?>" class="irp-grid-link">
            <?php if ( ! empty( $post_data['thumbnail'] ) ) : ?>
                <div class="irp-grid-thumbnail">
                    <img
                        src="<?php echo esc_url( $post_data['thumbnail']['url'] ); ?>"
                        alt="<?php echo esc_attr( $post_data['thumbnail']['alt'] ?: $post_data['title'] ); ?>"
                        width="<?php echo esc_attr( $post_data['thumbnail']['width'] ); ?>"
                        height="<?php echo esc_attr( $post_data['thumbnail']['height'] ); ?>"
                        loading="lazy"
                    />
                </div>
            <?php else : ?>
                <div class="irp-grid-thumbnail irp-no-thumbnail">
                    <div class="irp-thumbnail-placeholder">
                        <span class="irp-thumbnail-icon">📄</span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="irp-grid-content">
                <h3 class="irp-grid-title"><?php echo esc_html( $post_data['title'] ); ?></h3>

                <?php if ( ! empty( $args['show_excerpt'] ) && ! empty( $post_data['excerpt'] ) ) : ?>
                    <p class="irp-grid-excerpt"><?php echo esc_html( $post_data['excerpt'] ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $args['show_date'] ) || ! empty( $args['show_category'] ) ) : ?>
                    <div class="irp-grid-meta">
                        <?php if ( ! empty( $args['show_category'] ) && ! empty( $post_data['categories'] ) ) : ?>
                            <span class="irp-grid-category">
                                <?php echo esc_html( $post_data['categories'][0]->name ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( ! empty( $args['show_date'] ) ) : ?>
                            <span class="irp-grid-date"><?php echo esc_html( $post_data['date'] ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>
</div>
