<?php
/**
 * Hebert Thomas Law Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Hebert_Thomas_Law_Theme
 */

if ( ! defined( 'HEBERT_THOMAS_LAW_THEME_VERSION' ) ) {
	/*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
	define( 'HEBERT_THOMAS_LAW_THEME_VERSION', '0.1.0' );
}

if ( ! defined( 'HEBERT_THOMAS_LAW_THEME_TYPOGRAPHY_CLASSES' ) ) {
	/*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `hebert_thomas_law_theme_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
	define(
		'HEBERT_THOMAS_LAW_THEME_TYPOGRAPHY_CLASSES',
		'prose prose-neutral max-w-none prose-a:text-primary'
	);
}

if ( ! function_exists( 'hebert_thomas_law_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function hebert_thomas_law_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Hebert Thomas Law Theme, use a find and replace
		 * to change 'hebert-thomas-law-theme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'hebert-thomas-law-theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'hebert-thomas-law-theme' ),
				'menu-2' => __( 'Footer Menu', 'hebert-thomas-law-theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );
		add_editor_style( 'style-editor-extra.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Remove support for block templates.
		remove_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', 'hebert_thomas_law_theme_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function hebert_thomas_law_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'hebert-thomas-law-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'hebert-thomas-law-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'hebert_thomas_law_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function hebert_thomas_law_theme_scripts() {
	wp_enqueue_style( 'hebert-thomas-law-theme-style', get_stylesheet_uri(), array(), HEBERT_THOMAS_LAW_THEME_VERSION );
	wp_enqueue_script( 'hebert-thomas-law-theme-script', get_template_directory_uri() . '/js/script.min.js', array(), HEBERT_THOMAS_LAW_THEME_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hebert_thomas_law_theme_scripts' );

/**
 * Enqueue the block editor script.
 */
function hebert_thomas_law_theme_enqueue_block_editor_script() {
	wp_enqueue_script(
		'hebert-thomas-law-theme-editor',
		get_template_directory_uri() . '/js/block-editor.min.js',
		array(
			'wp-blocks',
			'wp-edit-post',
		),
		HEBERT_THOMAS_LAW_THEME_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'hebert_thomas_law_theme_enqueue_block_editor_script' );

/**
 * Enqueue the script necessary to support Tailwind Typography in the block
 * editor, using an inline script to create a JavaScript array containing the
 * Tailwind Typography classes from HEBERT_THOMAS_LAW_THEME_TYPOGRAPHY_CLASSES.
 */
function hebert_thomas_law_theme_enqueue_typography_script() {
	if ( is_admin() ) {
		wp_enqueue_script(
			'hebert-thomas-law-theme-typography',
			get_template_directory_uri() . '/js/tailwind-typography-classes.min.js',
			array(
				'wp-blocks',
				'wp-edit-post',
			),
			HEBERT_THOMAS_LAW_THEME_VERSION,
			true
		);
		wp_add_inline_script( 'hebert-thomas-law-theme-typography', "tailwindTypographyClasses = '" . esc_attr( HEBERT_THOMAS_LAW_THEME_TYPOGRAPHY_CLASSES ) . "'.split(' ');", 'before' );
	}
}
add_action( 'enqueue_block_assets', 'hebert_thomas_law_theme_enqueue_typography_script' );

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function hebert_thomas_law_theme_tinymce_add_class( $settings ) {
	$settings['body_class'] = HEBERT_THOMAS_LAW_THEME_TYPOGRAPHY_CLASSES;
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'hebert_thomas_law_theme_tinymce_add_class' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
