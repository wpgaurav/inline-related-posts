<?php
/**
 * List Template
 *
 * Displays posts in a horizontal list format
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="irp-post-item irp-list">
    <article class="irp-list-item">
        <?php if ( ! empty( $post_data['thumbnail'] ) ) : ?>
            <div class="irp-list-thumbnail">
                <a href="<?php echo esc_url( $post_data['permalink'] ); ?>">
                    <img
                        src="<?php echo esc_url( $post_data['thumbnail']['url'] ); ?>"
                        alt="<?php echo esc_attr( $post_data['thumbnail']['alt'] ?: $post_data['title'] ); ?>"
                        width="<?php echo esc_attr( $post_data['thumbnail']['width'] ); ?>"
                        height="<?php echo esc_attr( $post_data['thumbnail']['height'] ); ?>"
                        loading="lazy"
                    />
                </a>
            </div>
        <?php endif; ?>

        <div class="irp-list-content">
            <?php if ( ! empty( $args['show_category'] ) && ! empty( $post_data['categories'] ) ) : ?>
                <div class="irp-list-category">
                    <a href="<?php echo esc_url( get_category_link( $post_data['categories'][0]->term_id ) ); ?>">
                        <?php echo esc_html( $post_data['categories'][0]->name ); ?>
                    </a>
                </div>
            <?php endif; ?>

            <h3 class="irp-list-title">
                <a href="<?php echo esc_url( $post_data['permalink'] ); ?>">
                    <?php echo esc_html( $post_data['title'] ); ?>
                </a>
            </h3>

            <?php if ( ! empty( $args['show_excerpt'] ) && ! empty( $post_data['excerpt'] ) ) : ?>
                <div class="irp-list-excerpt">
                    <p><?php echo esc_html( $post_data['excerpt'] ); ?></p>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $args['show_date'] ) || ! empty( $args['show_author'] ) ) : ?>
                <div class="irp-list-meta">
                    <?php if ( ! empty( $args['show_author'] ) ) : ?>
                        <span class="irp-list-author">
                            <?php echo esc_html__( 'By', 'inline-related-posts' ); ?> <?php echo esc_html( $post_data['author'] ); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( ! empty( $args['show_date'] ) ) : ?>
                        <span class="irp-list-date">
                            <?php echo esc_html( $post_data['date'] ); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </article>
</div>
