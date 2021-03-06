<?php
/**
 * Gutenberg theme support.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_gutenberg_frontend_styles' );
/**
 * Enqueues Gutenberg front-end styles.
 *
 * @since 2.7.0
 */
function genesis_sample_enqueue_gutenberg_frontend_styles() {

	$child_theme_slug = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'genesis-sample';

	wp_enqueue_style(
		'genesis-sample-gutenberg',
		get_stylesheet_directory_uri() . '/lib/gutenberg/front-end.css',
		array( $child_theme_slug ),
		CHILD_THEME_VERSION
	);

}

add_action( 'enqueue_block_editor_assets', 'genesis_sample_block_editor_styles' );
/**
 * Enqueues Gutenberg admin editor fonts and styles.
 *
 * @since 2.7.0
 */
function genesis_sample_block_editor_styles() {

	wp_enqueue_style(
		'genesis-sample-gutenberg-fonts',
		'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700',
		array(),
		CHILD_THEME_VERSION
	);

}

// Add support for editor styles.
add_theme_support( 'editor-styles' );

// Enqueue editor styles.
add_editor_style( '/lib/gutenberg/style-editor.css' );

// Adds support for block alignments.
add_theme_support( 'align-wide' );

// Make media embeds responsive.
add_theme_support( 'responsive-embeds' );

// Adds support for editor font sizes.
add_theme_support(
	'editor-font-sizes',
	array(
		array(
			'name'      => __( 'Small', 'genesis-sample' ),
			'shortName' => __( 'S', 'genesis-sample' ),
			'size'      => 12,
			'slug'      => 'small',
		),
		array(
			'name'      => __( 'Normal', 'genesis-sample' ),
			'shortName' => __( 'M', 'genesis-sample' ),
			'size'      => 16,
			'slug'      => 'normal',
		),
		array(
			'name'      => __( 'Large', 'genesis-sample' ),
			'shortName' => __( 'L', 'genesis-sample' ),
			'size'      => 20,
			'slug'      => 'large',
		),
		array(
			'name'      => __( 'Larger', 'genesis-sample' ),
			'shortName' => __( 'XL', 'genesis-sample' ),
			'size'      => 24,
			'slug'      => 'larger',
		),
	)
);

// Adds support for editor color palette.
add_theme_support(
	'editor-color-palette',
	array(
		array(
			'name'  => __( 'White', 'genesis-sample' ),
			'slug'  => 'white',
			'color' => '#fff',
		),
		array(
			'name'  => __( 'Light gray', 'genesis-sample' ),
			'slug'  => 'light-gray',
			'color' => '#fafafa',
		),
		array(
			'name'  => __( 'Medium gray', 'genesis-sample' ),
			'slug'  => 'medium-gray',
			'color' => '#999',
		),
		array(
			'name'  => __( 'Dark gray', 'genesis-sample' ),
			'slug'  => 'dark-gray',
			'color' => '#333',
		),

		array(
			'name'  => __( 'Blue', 'genesis-sample' ),
			'slug'  => 'blue',
			'color' => '#009ad2',
		),
		array(
			'name'  => __( 'Dark green', 'genesis-sample' ),
			'slug'  => 'dark-green',
			'color' => '#76893e',
		),
		array(
			'name'  => __( 'Light green', 'genesis-sample' ),
			'slug'  => 'light-green',
			'color' => '#add04e',
		),
		array(
			'name'  => __( 'Orange', 'genesis-sample' ),
			'slug'  => 'orange',
			'color' => '#fbc52c',
		),
		array(
			'name'  => __( 'Red', 'genesis-sample' ),
			'slug'  => 'red',
			'color' => '#b92e32',
		),
	)
);

add_action( 'after_setup_theme', 'genesis_sample_content_width', 0 );
/**
 * Set content width to match the “wide” Gutenberg block width.
 */
function genesis_sample_content_width() {

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- See https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/924
	$GLOBALS['content_width'] = apply_filters( 'genesis_sample_content_width', 1062 );

}
