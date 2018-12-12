<?php
function schema_lite_child_enqueue_styles() {

    $parent_style = 'schema-lite'; // This is 'shema-lite-style' for the Schema Lite theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );

	/* it is already enqueing it after the parent. 
	wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    */
}
add_action( 'wp_enqueue_scripts', 'schema_lite_child_enqueue_styles' );

function schema_lite_child_override_scripts()
{
    wp_dequeue_script( 'schema-lite-customscripts' );
    wp_enqueue_script( 'schema-lite-child-customscripts', get_stylesheet_directory_uri() . '/js/customscripts.js', false, wp_get_theme()->get('Version'));
}
add_action( 'wp_enqueue_scripts', 'schema_lite_child_override_scripts', 11 );


/* remove jquery migrate */
 function remove_jquery_migrate( $scripts ) {
 	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
 		$script = $scripts->registered['jquery'];
 
 		if ( $script->deps ) { // Check whether the script has any dependencies
 				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
 		}
 	}
 }
 
 add_action( 'wp_default_scripts', 'remove_jquery_migrate' );
 
/* Remove type tag from script and style */
add_filter('style_loader_tag', 'codeless_remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'codeless_remove_type_attr', 10, 2);
add_filter('autoptimize_html_after_minify', 'codeless_remove_type_attr', 10, 2);
function codeless_remove_type_attr($tag, $handle='')
{
    return preg_replace("/type=['\"]text\/(javascript|css)['\"]/", '', $tag);
}

/**
 * Post Layout for Archives - override from parent theme
 */
if ( ! function_exists( 'schema_lite_archive_post' ) ) {
	/**
	 * Display a post of specific layout.
	 *
	 * @param string $layout Archive Post Layout.
	 */
	function schema_lite_archive_post( $layout = '' ) {
		$schema_lite_full_posts = get_theme_mod( 'schema_lite_full_posts', '0' );
		?>
		<article class="post excerpt">
			<header>
				<h2 class="title">
					<a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h2>
				<div class="post-info">
					<span class="theauthor"><i class="schema-lite-icon icon-user"></i> <?php esc_html_e( 'By', 'schema-lite' ); ?> <?php esc_url( the_author_posts_link() ); ?></span>
					<span class="posted-on entry-date date updated"><i class="schema-lite-icon icon-calendar"></i> <?php the_time( get_option( 'date_format' ) ); ?></span>
					<span class="featured-cat"><i class="schema-lite-icon icon-tags"></i> <?php the_category( ', ' ); ?></span>
					<span class="thecomment"><i class="schema-lite-icon icon-comment"></i> <a href="<?php esc_url( comments_link() ); ?>"><?php comments_number( __( '0 Comments', 'schema-lite' ), __( '1 Comment', 'schema-lite' ), __( '% Comments', 'schema-lite' ) ); ?></a></span>
				</div>
			</header><!--.header-->
			<?php
			if ( empty( $schema_lite_full_posts ) ) :
				if ( has_post_thumbnail() ) {
					?>
					<a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title_attribute(); ?>" class="featured-thumbnail-id">
						<div class="featured-thumbnail">
							<?php the_post_thumbnail( 'schema-lite-featured', array( 'title' => '' ) ); ?>
							<?php if ( function_exists( 'wp_review_show_total' ) ) wp_review_show_total( true, 'latestPost-review-wrapper' ); ?>
						</div>
					</a>
				<?php } ?>
				<div class="post-content">
					<?php echo schema_lite_excerpt( 42 ); ?>
				</div>
				<?php
				schema_lite_readmore();
			else :
				?>
				<div class="post-content full-post">
					<?php the_content(); ?>
				</div>
				<?php
				if ( schema_lite_post_has_moretag() ) :
					schema_lite_readmore();
				endif;
			endif;
			?>
		</article>
		<?php
	}
}
