/**
 * Register all shortcodes
 *
 * @return null
 */
function register_shortcodes() {
    add_shortcode( 'avery', 'shortcode_avery' );
    add_shortcode( 'averyterms', 'shortcode_averyterms' );
}
add_action( 'init', 'register_shortcodes' );

/**
 * List by provider Shortcode Callback
 * 
 * @param Array $atts
 *
 * @return string
 */
function shortcode_avery( $atts ) {
    
    ob_start();

    global $wp_query,
        $post;

    $atts = shortcode_atts( array(
        'cat' => ''
    ), $atts );

    $loop = new WP_Query( array(
        'posts_per_page'    => 500,
        'post_type'         => 'avery_template',
        'orderby'           => 'title',
        'order'             => 'ASC',
        'tax_query'         => array( array(
            'taxonomy'  => 'avery_cprovider',
            'field'     => 'slug',
            'terms'     => array( sanitize_title( $atts['cat'] ) )
        ) )
    ) );

    if( ! $loop->have_posts() ) {
        return false;
    }

    while( $loop->have_posts() ) {
        $loop->the_post();
        echo the_title('<h2 class="h5 entry-title mt-1" style="margin-bottom:-30px;"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        
    }

    wp_reset_postdata();
    return ob_get_clean();
}

/**
 * Fuul list Shortcode Callback
 * 
 * @param Array $atts
 *
 * @return string
 */
function shortcode_averyterms( $atts ) {
    
    ob_start();

    $terms = get_terms([
        'taxonomy' => 'avery_cprovider',
        'hide_empty' => true,
    ]);

    echo "<div class='list-container'>";
    foreach( $terms as $term ){
        
        global $wp_query,
        $post;

        echo "<div class='list-box'>";
        $term_slug = $term->slug;
        echo ucfirst($term_slug);
        echo '<br>';

        $atts = shortcode_atts( array(
            'cat' => ''
        ), $atts );

        $loop = new WP_Query( array(
            'posts_per_page'    => 500,
            'post_type'         => 'avery_template',
            'orderby'           => 'title',
            'order'             => 'ASC',
            'tax_query'         => array( array(
                'taxonomy'  => 'avery_cprovider',
                'field'     => 'slug',
                'terms'     => array( $term_slug )
            ) )
        ) );

        if( ! $loop->have_posts() ) {
            return false;
        }

        while( $loop->have_posts() ) {
            $loop->the_post();

            echo the_title('<a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a><br>');
            
        }
        
        echo "</div>";

    wp_reset_postdata();

    }

    echo "</div>";

    return ob_get_clean();

}
