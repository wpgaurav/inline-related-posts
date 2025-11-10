<?php
/**
 * Post Query Handler
 *
 * Handles querying posts by IDs, slugs, or default parameters
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IRP_Post_Query {

    /**
     * Get posts based on parameters
     *
     * @param array $args Query arguments
     * @return array Array of WP_Post objects
     */
    public static function get_posts( $args = array() ) {
        $defaults = array(
            'ids'         => '',
            'slugs'       => '',
            'posts'       => 6,
            'columns'     => 3,
            'post_type'   => 'post',
            'orderby'     => 'date',
            'order'       => 'DESC',
            'category'    => '',
            'tag'         => '',
            'exclude'     => '',
        );

        $args = wp_parse_args( $args, $defaults );

        // Build query arguments
        $query_args = array(
            'post_type'      => $args['post_type'],
            'post_status'    => 'publish',
            'posts_per_page' => intval( $args['posts'] ),
            'orderby'        => $args['orderby'],
            'order'          => $args['order'],
            'ignore_sticky_posts' => true,
        );

        // Handle post IDs
        if ( ! empty( $args['ids'] ) ) {
            $ids = self::parse_ids( $args['ids'] );
            if ( ! empty( $ids ) ) {
                $query_args['post__in'] = $ids;
                $query_args['orderby'] = 'post__in';
            }
        }

        // Handle post slugs
        if ( ! empty( $args['slugs'] ) && empty( $query_args['post__in'] ) ) {
            $slugs = self::parse_slugs( $args['slugs'] );
            if ( ! empty( $slugs ) ) {
                $query_args['post_name__in'] = $slugs;
            }
        }

        // Handle category filter
        if ( ! empty( $args['category'] ) ) {
            $query_args['category_name'] = $args['category'];
        }

        // Handle tag filter
        if ( ! empty( $args['tag'] ) ) {
            $query_args['tag'] = $args['tag'];
        }

        // Handle exclusions
        if ( ! empty( $args['exclude'] ) ) {
            $exclude_ids = self::parse_ids( $args['exclude'] );
            if ( ! empty( $exclude_ids ) ) {
                $query_args['post__not_in'] = $exclude_ids;
            }
        }

        // Exclude current post in single post view
        if ( is_singular() ) {
            $current_id = get_the_ID();
            if ( ! isset( $query_args['post__not_in'] ) ) {
                $query_args['post__not_in'] = array();
            }
            $query_args['post__not_in'][] = $current_id;
        }

        // Execute query
        $query = new WP_Query( $query_args );

        return $query->posts;
    }

    /**
     * Parse comma-separated IDs
     *
     * @param string $ids Comma-separated post IDs
     * @return array Array of integers
     */
    private static function parse_ids( $ids ) {
        if ( empty( $ids ) ) {
            return array();
        }

        // Handle both string and array input
        if ( is_array( $ids ) ) {
            return array_map( 'intval', array_filter( $ids ) );
        }

        // Parse comma-separated string
        $ids = explode( ',', $ids );
        $ids = array_map( 'trim', $ids );
        $ids = array_map( 'intval', $ids );
        $ids = array_filter( $ids );

        return $ids;
    }

    /**
     * Parse comma-separated slugs
     *
     * @param string $slugs Comma-separated post slugs
     * @return array Array of slugs
     */
    private static function parse_slugs( $slugs ) {
        if ( empty( $slugs ) ) {
            return array();
        }

        // Handle both string and array input
        if ( is_array( $slugs ) ) {
            return array_map( 'sanitize_title', array_filter( $slugs ) );
        }

        // Parse comma-separated string
        $slugs = explode( ',', $slugs );
        $slugs = array_map( 'trim', $slugs );
        $slugs = array_map( 'sanitize_title', $slugs );
        $slugs = array_filter( $slugs );

        return $slugs;
    }

    /**
     * Get post data for display
     *
     * @param WP_Post $post Post object
     * @return array Post data array
     */
    public static function get_post_data( $post ) {
        $data = array(
            'id'           => $post->ID,
            'title'        => get_the_title( $post ),
            'permalink'    => get_permalink( $post ),
            'excerpt'      => self::get_post_excerpt( $post ),
            'date'         => get_the_date( '', $post ),
            'author'       => get_the_author_meta( 'display_name', $post->post_author ),
            'thumbnail'    => self::get_post_thumbnail( $post ),
            'categories'   => get_the_category( $post->ID ),
            'tags'         => get_the_tags( $post->ID ),
        );

        return $data;
    }

    /**
     * Get post excerpt
     *
     * @param WP_Post $post Post object
     * @param int     $length Excerpt length
     * @return string Excerpt text
     */
    private static function get_post_excerpt( $post, $length = 120 ) {
        if ( has_excerpt( $post ) ) {
            $excerpt = get_the_excerpt( $post );
        } else {
            $excerpt = $post->post_content;
        }

        $excerpt = wp_strip_all_tags( $excerpt );

        if ( strlen( $excerpt ) > $length ) {
            $excerpt = substr( $excerpt, 0, $length );
            $excerpt = substr( $excerpt, 0, strrpos( $excerpt, ' ' ) );
            $excerpt .= '...';
        }

        return $excerpt;
    }

    /**
     * Get post thumbnail data
     *
     * @param WP_Post $post Post object
     * @param string  $size Image size
     * @return array|null Thumbnail data or null
     */
    private static function get_post_thumbnail( $post, $size = 'medium' ) {
        if ( ! has_post_thumbnail( $post ) ) {
            return null;
        }

        $thumbnail_id = get_post_thumbnail_id( $post );
        $image_data = wp_get_attachment_image_src( $thumbnail_id, $size );

        if ( ! $image_data ) {
            return null;
        }

        return array(
            'url'    => $image_data[0],
            'width'  => $image_data[1],
            'height' => $image_data[2],
            'alt'    => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
        );
    }
}
