<?php
/**
 * Card Template
 *
 * Displays posts as cards with thumbnail, title, excerpt, and meta
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="irp-post-item irp-card">
    <article class="irp-card-inner">
        <?php if ( ! empty( $post_data['thumbnail'] ) ) : ?>
            <div class="irp-card-thumbnail">
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

        <div class="irp-card-body">
            <?php if ( ! empty( $args['show_category'] ) && ! empty( $post_data['categories'] ) ) : ?>
                <div class="irp-card-category">
                    <?php
                    $category = $post_data['categories'][0];
                    ?>
                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="irp-category-link">
                        <?php echo esc_html( $category->name ); ?>
                    </a>
                </div>
            <?php endif; ?>

            <h3 class="irp-card-title">
                <a href="<?php echo esc_url( $post_data['permalink'] ); ?>">
                    <?php echo esc_html( $post_data['title'] ); ?>
                </a>
            </h3>

            <?php if ( ! empty( $args['show_excerpt'] ) && ! empty( $post_data['excerpt'] ) ) : ?>
                <div class="irp-card-excerpt">
                    <p><?php echo esc_html( $post_data['excerpt'] ); ?></p>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $args['show_date'] ) || ! empty( $args['show_author'] ) ) : ?>
                <div class="irp-card-meta">
                    <?php if ( ! empty( $args['show_author'] ) ) : ?>
                        <span class="irp-card-author">
                            <?php echo esc_html( $post_data['author'] ); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( ! empty( $args['show_date'] ) ) : ?>
                        <span class="irp-card-date">
                            <?php echo esc_html( $post_data['date'] ); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="irp-card-footer">
                <a href="<?php echo esc_url( $post_data['permalink'] ); ?>" class="irp-read-more">
                    <?php esc_html_e( 'Read More', 'inline-related-posts' ); ?>
                    <span class="irp-arrow">→</span>
                </a>
            </div>
        </div>
    </article>
</div>
