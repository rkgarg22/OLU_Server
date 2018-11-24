<?php

date_default_timezone_set("America/Bogota");
/**
 * Twenty Seventeen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 */

/**
 * Twenty Seventeen only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function twentyseventeen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentyseventeen
	 * If you're building a theme based on Twenty Seventeen, use a find and replace
	 * to change 'twentyseventeen' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyseventeen' );

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

	add_image_size( 'twentyseventeen-featured-image', 2000, 1200, true );

	add_image_size( 'twentyseventeen-thumbnail-avatar', 100, 100, true );

	// Set the default content width.
	$GLOBALS['content_width'] = 525;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'twentyseventeen' ),
		'social' => __( 'Social Links Menu', 'twentyseventeen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css', twentyseventeen_fonts_url() ) );

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets' => array(
			// Place three core-defined widgets in the sidebar area.
			'sidebar-1' => array(
				'text_business_info',
				'search',
				'text_about',
			),

			// Add the core-defined business info widget to the footer 1 area.
			'sidebar-2' => array(
				'text_business_info',
			),

			// Put two core-defined widgets in the footer 2 area.
			'sidebar-3' => array(
				'text_about',
				'search',
			),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array(
			'home',
			'about' => array(
				'thumbnail' => '{{image-sandwich}}',
			),
			'contact' => array(
				'thumbnail' => '{{image-espresso}}',
			),
			'blog' => array(
				'thumbnail' => '{{image-coffee}}',
			),
			'homepage-section' => array(
				'thumbnail' => '{{image-espresso}}',
			),
		),

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
			'image-espresso' => array(
				'post_title' => _x( 'Espresso', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/espresso.jpg', // URL relative to the template directory.
			),
			'image-sandwich' => array(
				'post_title' => _x( 'Sandwich', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/sandwich.jpg',
			),
			'image-coffee' => array(
				'post_title' => _x( 'Coffee', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/coffee.jpg',
			),
		),

		// Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set the front page section theme mods to the IDs of the core-registered pages.
		'theme_mods' => array(
			'panel_1' => '{{homepage-section}}',
			'panel_2' => '{{about}}',
			'panel_3' => '{{blog}}',
			'panel_4' => '{{contact}}',
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'top' => array(
				'name' => __( 'Top Menu', 'twentyseventeen' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),

			// Assign a menu to the "social" location.
			'social' => array(
				'name' => __( 'Social Links Menu', 'twentyseventeen' ),
				'items' => array(
					'link_yelp',
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),
	);

	/**
	 * Filters Twenty Seventeen array of starter content.
	 *
	 * @since Twenty Seventeen 1.1
	 *
	 * @param array $starter_content Array of starter content.
	 */
	$starter_content = apply_filters( 'twentyseventeen_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
}
add_action( 'after_setup_theme', 'twentyseventeen_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function twentyseventeen_content_width() {

	$content_width = $GLOBALS['content_width'];

	// Get layout.
	$page_layout = get_theme_mod( 'page_layout' );

	// Check if layout is one column.
	if ( 'one-column' === $page_layout ) {
		if ( twentyseventeen_is_frontpage() ) {
			$content_width = 644;
		} elseif ( is_page() ) {
			$content_width = 740;
		}
	}

	// Check if is single post and there is no sidebar.
	if ( is_single() && ! is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 740;
	}

	/**
	 * Filter Twenty Seventeen content width of the theme.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'twentyseventeen_content_width', $content_width );
}
add_action( 'template_redirect', 'twentyseventeen_content_width', 0 );

/**
 * Register custom fonts.
 */
function twentyseventeen_fonts_url() {
	$fonts_url = '';

	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Libre Franklin, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$libre_franklin = _x( 'on', 'Libre Franklin font: on or off', 'twentyseventeen' );

	if ( 'off' !== $libre_franklin ) {
		$font_families = array();

		$font_families[] = 'Libre Franklin:300,300i,400,400i,600,600i,800,800i';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function twentyseventeen_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'twentyseventeen-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'twentyseventeen_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function twentyseventeen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'twentyseventeen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 1', 'twentyseventeen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'twentyseventeen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentyseventeen_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $link Link to single post/page.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function twentyseventeen_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentyseventeen_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Seventeen 1.0
 */
function twentyseventeen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentyseventeen_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function twentyseventeen_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'twentyseventeen_pingback_header' );

/**
 * Display custom color CSS.
 */
function twentyseventeen_colors_css_wrap() {
	if ( 'custom' !== get_theme_mod( 'colorscheme' ) && ! is_customize_preview() ) {
		return;
	}

	require_once( get_parent_theme_file_path( '/inc/color-patterns.php' ) );
	$hue = absint( get_theme_mod( 'colorscheme_hue', 250 ) );
?>
	<style type="text/css" id="custom-theme-colors" <?php if ( is_customize_preview() ) { echo 'data-hue="' . $hue . '"'; } ?>>
		<?php echo twentyseventeen_custom_colors_css(); ?>
	</style>
<?php }
add_action( 'wp_head', 'twentyseventeen_colors_css_wrap' );

/**
 * Enqueue scripts and styles.
 */
function twentyseventeen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentyseventeen-fonts', twentyseventeen_fonts_url(), array(), null );

	// Theme stylesheet.
	wp_enqueue_style( 'twentyseventeen-style', get_stylesheet_uri() );

	// Load the dark colorscheme.
	if ( 'dark' === get_theme_mod( 'colorscheme', 'light' ) || is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-colors-dark', get_theme_file_uri( '/assets/css/colors-dark.css' ), array( 'twentyseventeen-style' ), '1.0' );
	}

	// Load the Internet Explorer 9 specific stylesheet, to fix display issues in the Customizer.
	if ( is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-ie9', get_theme_file_uri( '/assets/css/ie9.css' ), array( 'twentyseventeen-style' ), '1.0' );
		wp_style_add_data( 'twentyseventeen-ie9', 'conditional', 'IE 9' );
	}

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentyseventeen-ie8', get_theme_file_uri( '/assets/css/ie8.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_style_add_data( 'twentyseventeen-ie8', 'conditional', 'lt IE 9' );

	// Load the html5 shiv.
	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentyseventeen-skip-link-focus-fix', get_theme_file_uri( '/assets/js/skip-link-focus-fix.js' ), array(), '1.0', true );

	$twentyseventeen_l10n = array(
		'quote'          => twentyseventeen_get_svg( array( 'icon' => 'quote-right' ) ),
	);

	if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script( 'twentyseventeen-navigation', get_theme_file_uri( '/assets/js/navigation.js' ), array( 'jquery' ), '1.0', true );
		$twentyseventeen_l10n['expand']         = __( 'Expand child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['collapse']       = __( 'Collapse child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['icon']           = twentyseventeen_get_svg( array( 'icon' => 'angle-down', 'fallback' => true ) );
	}

	wp_enqueue_script( 'twentyseventeen-global', get_theme_file_uri( '/assets/js/global.js' ), array( 'jquery' ), '1.0', true );

	wp_enqueue_script( 'jquery-scrollto', get_theme_file_uri( '/assets/js/jquery.scrollTo.js' ), array( 'jquery' ), '2.1.2', true );

	wp_localize_script( 'twentyseventeen-skip-link-focus-fix', 'twentyseventeenScreenReaderText', $twentyseventeen_l10n );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'twentyseventeen_scripts' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentyseventeen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 740 <= $width ) {
		$sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || is_archive() || is_search() || is_home() || is_page() ) {
		if ( ! ( is_page() && 'one-column' === get_theme_mod( 'page_options' ) ) && 767 <= $width ) {
			 $sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentyseventeen_content_image_sizes_attr', 10, 2 );

/**
 * Filter the `sizes` value in the header image markup.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $html   The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array  $attr   Array of the attributes for the image tag.
 * @return string The filtered header image HTML.
 */
function twentyseventeen_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'twentyseventeen_header_image_tag', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array $attr       Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size       Registered image size or flat array of height and width dimensions.
 * @return array The filtered attributes for the image markup.
 */
function twentyseventeen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentyseventeen_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $template front-page.php.
 *
 * @return string The template to be used: blank if is_home() is true (defaults to index.php), else $template.
 */
function twentyseventeen_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template',  'twentyseventeen_front_page_template' );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Twenty Seventeen 1.4
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function twentyseventeen_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentyseventeen_widget_tag_cloud_args' );

/**
 * Implement the Custom Header feature.
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path( '/inc/template-functions.php' );

/**
 * Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );
/**
 * Implement the User Roles
 */
require get_parent_theme_file_path('/inc/userroles.php');
/**
 * Implement the User Roles
 */
require get_parent_theme_file_path('/inc/UserModule.php');
/**
 * Implement the misclenious Functions
 */
require get_parent_theme_file_path('/inc/misc.php');


/////Firebase Section////////
function sendMessage($target, $title, $message)
{
	/* echo $target.$title.$message;
	echo "<br>"; */
	$url = 'https://fcm.googleapis.com/fcm/send';
	$server_key = "AAAABsmb2Us:APA91bGBmOnvMS65RUWPWNlY9tgSqS8LJJxsM4cXoq-7Gt13x4YAAhuNv6Yl-3asrKw58LWrACF87O7832zjFVx-twOvjdnwKp907yG_1WhdwLPuI7XciyYcCg-3FhpDZA6-JMhcHdSV";
	$fields = array(
		'to' => $target,
		"content_available" => true,
		"priority" => "high",
		'notification' => array(
			"sound" => "default",
			"badge" => "12",
			'title' => "$title",
			'body' => "$message",
		)
	);
	$headers = array(
		'Content-Type:application/json',
		'Authorization:key=' . $server_key
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	
	if ($result === false) {
		die('FCM Send Error: ' . curl_error($ch));
	}
	curl_close($ch);
	return $result;
}
function sendMessageData($target, $title, $message , $bookingID, $firstName, $lastName, $categoryID, $categoryName, $bookingDate, $phone, $bookingTime, $endDateSet, $priceGroup ,$address , $latitude , $longitude , $userImageUrl , $bookingType , $bookingCreated , $bookingUpdated)
{
	$url = 'https://fcm.googleapis.com/fcm/send';
	$server_key = "AAAABsmb2Us:APA91bGBmOnvMS65RUWPWNlY9tgSqS8LJJxsM4cXoq-7Gt13x4YAAhuNv6Yl-3asrKw58LWrACF87O7832zjFVx-twOvjdnwKp907yG_1WhdwLPuI7XciyYcCg-3FhpDZA6-JMhcHdSV";
	$fields = array(
		'to' => $target,
		"content_available" => true,
		"priority" => "high",
		'notification' => array(
			"sound" => "default",
			"badge" => "12",
			'title' => "$title",
			'body' => "$message",
		),
		"data" => array(
			"bookingID" => (int)$bookingID,
			"firstName" => $firstName,
			"lastName" => $lastName,
			"categoryID" => (int)$categoryID,
			"categoryName" => $categoryName,
			"bookingDate" => $bookingDate,
			"address" => $address,
			"bookingStart" => $bookingTime,
			"bookingEnd" => $endDateSet,
			"bookingType" => $priceGroup,
			"bookingLatitude" => $latitude,
			"bookingLongitude" => $longitude,
			"bookingAddress" => $address,
			"userImageUrl" => $userImageUrl,
			"bookingFor"  => $bookingType,
			"bookingCreated" => $bookingCreated,
			"bookingUpdated" => $bookingUpdated
		)
	);
	// echo json_encode($fields);
/* 	$myfile = fopen(ABSPATH."fileToCheckNotification.txt", "w") or die("Unable to open file!");
	$txt = json_encode($fields);
	fwrite($myfile, $txt);
	fclose($myfile); */
	$headers = array(
		'Content-Type:application/json',
		'Authorization:key=' . $server_key
	);
	$fields = str_replace("null", '""', json_encode($fields));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	$result = curl_exec($ch);
	/* echo "<pre>";
		print_r(json_decode($result));
	echo "</pre>"; */
	if ($result === false) {
		die('FCM Send Error: ' . curl_error($ch));
	}
	curl_close($ch);
	return $result;
}
function sendMessageChat($target, $title, $message, $conversationID, $userID, $firstName , $messageText, $lastName, $userIDTo , $currentTime)
{
	$url = 'https://fcm.googleapis.com/fcm/send';
	$server_key = "AAAABsmb2Us:APA91bGBmOnvMS65RUWPWNlY9tgSqS8LJJxsM4cXoq-7Gt13x4YAAhuNv6Yl-3asrKw58LWrACF87O7832zjFVx-twOvjdnwKp907yG_1WhdwLPuI7XciyYcCg-3FhpDZA6-JMhcHdSV";
	$fields = array(
		'to' => $target,
		"content_available" => true,
		"priority" => "high",
		'notification' => array(
			"sound" => "default",
			"badge" => "12",
			'title' => "$title",
			'body' => "$message",
		),
		"data" => array(
			"conversationID" => $conversationID,
			"userID" => (int)$userID,
			"firstName" => $firstName,
			"lastName" => $lastName,
			"message" => $messageText,
			"toUserID" => (int)$userIDTo,
			"messageTime" => (int)$currentTime
		)
	);
	// echo json_encode($fields);
	$headers = array(
		'Content-Type:application/json',
		'Authorization:key=' . $server_key
	);
	$fields = str_replace("null", '""', json_encode($fields));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	$result = curl_exec($ch);
	/* echo "<pre>";
		print_r(json_decode($result));
	echo "</pre>"; */
	if ($result === false) {
		die('FCM Send Error: ' . curl_error($ch));
	}
	curl_close($ch);
	return $result;
}
/////Firebase Section////////



function getUserRating($user) {
	global $wpdb;
	$getUserRating = $wpdb->get_results("SELECT * FROM `wtw_booking_reviews` WHERE `user_id` = $user");
	if(count($getUserRating) <= 3) {
		return 4;
	} else {
		$getUserRating1 = 0;
		foreach ($getUserRating as $key => $value) {
			$getUserRating1 = $getUserRating1 + $value->rating;
		}
		return $getValue = $getUserRating1 / count($getUserRating);
	}
}

function getUserToken($userID) {
	$getUserToekn = get_user_meta($userID, "requestId", true);

	
	if(empty($getUserToekn) || $getUserToekn == "") {
		return "False";
	} else {
		$requestId = json_decode($getUserToekn);
		$getType = gettype($requestId);
		if ($getType == "integer") {
			$requestId = (array)$requestId;
		} 
		$i=0;
		foreach ($requestId as $key => $value) {
			$login = "fcec4c9fd9ea26079d9302b2424d38ea";
			$seed = date('c');
			if (function_exists('random_bytes')) {
				$nonce = bin2hex(random_bytes(16));
			} elseif (function_exists('openssl_random_pseudo_bytes')) {
				$nonce = bin2hex(openssl_random_pseudo_bytes(16));
			} else {
				$nonce = mt_rand();
			}
			$nonceBase64 = base64_encode($nonce);
			$nextmonth = date('c', strtotime(' +1 month'));
			$tranKey = base64_encode(sha1($nonce . $seed . "92EukRSJ82Vr0TUt", true));
			$authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" }}';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://test.placetopay.com/redirection/api/session/" . $value);
			curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			$result = curl_exec($ch);
			$result = json_decode($result);
			$token = 1;
			if ($result->status->status == "APPROVED") {
				return $tokenCard = $result->subscription->instrument[0]->value;
			} else {
				return "False";
			}
			$i++;
		}
		if($i == 0) {
			return "False";
		}
		
	}
	
}

function getUserWallet($userID) {
	global $wpdb;
	$initWaller = 0;
	$initBooking = 0;
	$getMyMoney = $wpdb->get_results("SELECT * FROM `wtw_add_money` WHERE `user_id` = $userID");
	$useMoney = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = 1 AND `notAttended` != 1 OR `booking_from` = $userID AND `isPaid` = 1");
	foreach ($getMyMoney as $key => $value) {
		$initWaller = $initWaller + $value->moneyAdded;
	}
	foreach ($useMoney as $key => $value) {

		unset($valueCode);
		if ($value->promocode != "") {
			$getPromoCode = $wpdb->get_results("SELECT * FROM `wtw_promocode` WHERE `name` = '$value->promocode'");
			$valueCode = 100 -  $getPromoCode[0]->discount;
		} 
		$getBookingDetails = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $value->user_id AND `category_id` = $value->category_id");

		if ($value->booking_for == "single") {
			$metaKey = "single_price";
		} elseif ($value->booking_for == "business") {
			$metaKey = "group_price";
		} elseif ($value->booking_for == "business3") {
			$metaKey = "group_price3";
		} elseif ($value->booking_for == "business4") {
			$metaKey = "group_price4";
		} else {
			$metaKey = "company_price";
		}
		if (isset($valueCode)) {
			$bookingPrice =   ($valueCode / 100) * $getBookingDetails[0]->$metaKey;
		} else {
			$bookingPrice =  $getBookingDetails[0]->$metaKey;
		}
		$initBooking = $initBooking + $bookingPrice;
	}
	 $finalAmount =  $initWaller - $initBooking;
	 if($finalAmount < 0) {
		 return 0;
	 } else {
		 return $finalAmount;
	 }
}
function getUserWalletBefore($userID,$bookingID)
{
	global $wpdb;
	$initWaller = 0;
	$initBooking = 0;
	$getBookingDet = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id` = $bookingID");
	$createc = $getBookingDet[0]->booking_action_time;
	// echo  "SELECT * FROM `wtw_add_money` WHERE `user_id` = $userID AND `created_date` < '$createc'";
	$getMyMoney = $wpdb->get_results("SELECT * FROM `wtw_add_money` WHERE `user_id` = $userID AND `created_date` < '$createc'");
	
	$useMoney = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = 1 AND `id` < $bookingID AND `notAttended` != 1 OR `booking_from` = $userID AND `isPaid` = 1 AND `id` < $bookingID");
	foreach ($getMyMoney as $key => $value) {
		$initWaller = $initWaller + $value->moneyAdded;
	}
	foreach ($useMoney as $key => $value) {
		unset($valueCode);
		if ($value->promocode != "") {
			$getPromoCode = $wpdb->get_results("SELECT * FROM `wtw_promocode` WHERE `name` = '$value->promocode'");
			$valueCode = 100 -  $getPromoCode[0]->discount;
		} 
		$getBookingDetails = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $value->user_id AND `category_id` = $value->category_id");

		if ($value->booking_for == "single") {
			$metaKey = "single_price";
		} elseif ($value->booking_for == "business") {
			$metaKey = "group_price";
		} elseif ($value->booking_for == "business3") {
			$metaKey = "group_price3";
		} elseif ($value->booking_for == "business4") {
			$metaKey = "group_price4";
		} else {
			$metaKey = "company_price";
		}
		if (isset($valueCode)) {
			$bookingPrice = ($valueCode / 100) * $getBookingDetails[0]->$metaKey;
		} else {
			$bookingPrice = $getBookingDetails[0]->$metaKey;
		}
		$initBooking = $initBooking + $bookingPrice;
	}
	$finalAmount = $initWaller - $initBooking;
	if ($finalAmount < 0) {
		return 0;
	} else {
		return $finalAmount;
	}
}



function getBookingPrice($bookingID) {
	global $wpdb;
	$useMoney = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id` = $bookingID");
	foreach ($useMoney as $key => $value) {
		if($value->promocode != "") {
			$getPromoCode = $wpdb->get_results("SELECT * FROM `wtw_promocode` WHERE `name` = '$value->promocode'");
			$valueCode = 100 - $getPromoCode[0]->discount;
		} 
		$getBookingDetails = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $value->user_id AND `category_id` = $value->category_id");

		if ($value->booking_for == "single") {
			$metaKey = "single_price";
		} elseif ($value->booking_for == "business") {
			$metaKey = "group_price";
		} elseif ($value->booking_for == "business3") {
			$metaKey = "group_price3";
		} elseif ($value->booking_for == "business4") {
			$metaKey = "group_price4";
		} else {
			$metaKey = "company_price";
		}
		if(isset($valueCode)) {
			return ($valueCode / 100) * $getBookingDetails[0]->$metaKey;
		} else {
			return $getBookingDetails[0]->$metaKey;
		}
	}
}

function getBookingPriceTrainer($bookingID) {
	global $wpdb;
	$useMoney = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id` = $bookingID");
	foreach ($useMoney as $key => $value) {
		if ($value->promocode != "") {
			$getPromoCode = $wpdb->get_results("SELECT * FROM `wtw_promocode` WHERE `name` = '$value->promocode'");
			$valueCode = 100 - $getPromoCode[0]->discount;
		}
		
		$getBookingDetails = $wpdb->get_results("SELECT * FROM `wtw_booking_price` WHERE `booking_id` = $bookingID");


		if (isset($valueCode)) {
			return ($valueCode / 100) * $getBookingDetails[0]->booking_price;
		} else {
			return $getBookingDetails[0]->booking_price;
		}
	}
}

function collectAPI($userID , $price , $token, $payer) {
	global $wpdb;
	$token = $token;
	$login = "fcec4c9fd9ea26079d9302b2424d38ea";
	$seed = date('c');

	$generateMyRefNumber = generateMyRefNumber();
	if (function_exists('random_bytes')) {
		$nonce = bin2hex(random_bytes(16));
	} elseif (function_exists('openssl_random_pseudo_bytes')) {
		$nonce = bin2hex(openssl_random_pseudo_bytes(16));
	} else {
		$nonce = mt_rand();
	}
	$nonceBase64 = base64_encode($nonce);
	$nextmonth = date('c', strtotime(' +1 month'));
	$tranKey = base64_encode(sha1($nonce . $seed . "92EukRSJ82Vr0TUt", true));

	$collectData = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" },  "instrument": { "token": { "token": "' . $token . '" } } , "payer" : ' . json_encode($payer) . ' , "payment": { "reference": "'. $generateMyRefNumber .'", "description": "Pago básico de prueba", "amount": { "currency": "USD", "total": "' . $price . '" } }}';
	$ch = curl_init();
	$agents = array(
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
		'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
		'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'

	);
	curl_setopt($ch, CURLOPT_USERAGENT, $agents[array_rand($agents)]);
	curl_setopt($ch, CURLOPT_URL, "https://test.placetopay.com/redirection/api/collect/");
	curl_setopt($ch, CURLOPT_POST, count(json_decode($collectData)));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $collectData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	$result = curl_exec($ch);
	$result = json_decode($result);
	curl_close($ch);
	$wpdb->insert('wtw_add_money', array(
		'user_id' => $userID,
		'txn_id' => $result->requestId,
		'moneyPlan' => "custom",
		'moneyValue' => $price,
		'moneyAdded' => $price,
		'created_date' => date("Y-m-d H:i:s"),
		'ref_num' => $generateMyRefNumber
	));
	return $result->requestId;
}

function getPaymerDetails($userID) {
	$getUserToekn = get_user_meta($userID, "requestId", true);
	$requestId = json_decode($getUserToekn);
	$getType = gettype($requestId);
	if ($getType == "integer") {
		$requestId = (array)$requestId;
	} 
	foreach ($requestId as $key => $value) {
		if ($value == "") {
			return "False";
		} else {
			$login = "fcec4c9fd9ea26079d9302b2424d38ea";
			$seed = date('c');
			if (function_exists('random_bytes')) {
				$nonce = bin2hex(random_bytes(16));
			} elseif (function_exists('openssl_random_pseudo_bytes')) {
				$nonce = bin2hex(openssl_random_pseudo_bytes(16));
			} else {
				$nonce = mt_rand();
			}
			$nonceBase64 = base64_encode($nonce);
			$nextmonth = date('c', strtotime(' +1 month'));
			$tranKey = base64_encode(sha1($nonce . $seed . "92EukRSJ82Vr0TUt", true));
			$authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" }}';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://test.placetopay.com/redirection/api/session/" . $value);
			curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			$result = curl_exec($ch);
			$result = json_decode($result);
			$token = 1;
			if ($result->status->status == "APPROVED") {
				return $result->request->payer;
				break;
			} else {
				return "False";
			}
		}
	}
}
function getPaymerDetailsTest($userID) {
	$getUserToekn = get_user_meta($userID, "requestId", true);

	$requestId = json_decode($getUserToekn);
	$getType = gettype($requestId);
	if ($getType == "integer") {
		$requestId = (array)$requestId;
	} 
	foreach ($requestId as $key => $value) {
		if ($value == "") {
			return "False";
		} else {
			$login = "fcec4c9fd9ea26079d9302b2424d38ea";
			$seed = date('c');
			if (function_exists('random_bytes')) {
				$nonce = bin2hex(random_bytes(16));
			} elseif (function_exists('openssl_random_pseudo_bytes')) {
				$nonce = bin2hex(openssl_random_pseudo_bytes(16));
			} else {
				$nonce = mt_rand();
			}
			$nonceBase64 = base64_encode($nonce);
			$nextmonth = date('c', strtotime(' +1 month'));
			$tranKey = base64_encode(sha1($nonce . $seed . "92EukRSJ82Vr0TUt", true));
			$authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" }}';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://test.placetopay.com/redirection/api/session/" . $value);
			curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			$result = curl_exec($ch);
			$result = json_decode($result);
			$token = 1;
			if ($result->status->status == "APPROVED") {
				return $result;
			} else {
				return "False";
			}
		}
	}
	
}

function getMyExpiredBooking($userID) {
	global $wpdb;

	$user = get_user_by('ID', $userID);
	date_default_timezone_set("America/Bogota");
	$currentDate = date("Y-m-d");
	$currentDateTime = date("Y-m-d H:i:s");
	$currentTime = date("H:i:s");
	if ($user->roles[0] == "contributor") {
		$getMyData = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_date` < '" . $currentDate ."' AND `user_id` = $userID AND `status` = 3");

	} else {
		$getMyData = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_date` < '" . $currentDate . "' AND `booking_from` = $userID AND `status` = 3");

	}
	foreach ($getMyData as $key => $value) {
		if(strtotime($value->booking_end) < strtotime($value->booking_start)) {
			$id = $value->id;
			$currentDate1 = date("Y-m-d" , strtotime('+1 day', strtotime($currentDate)));
			if ($user->roles[0] == "contributor") {

				$data = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_date` < '" . $currentDate1 . "' AND `user_id` = $userID AND `status` = 3 AND `id` = $id");

			} else {
				$data = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_date` <= '" . $currentDate1 . "' AND `booking_from` = $userID AND `status` = 3 AND `id` = $id");

			}
			if(!empty($data)) {
				$wpdb->query("UPDATE `wtw_booking` SET `status` = 1, `booking_action_time` = '$currentDateTime' , `notAttended` = 1 WHERE `id` = $value->id");
				$wpdb->insert('wtw_booking_price', array(
					'booking_id' => $value->id,
					'booking_price' => 0,
					'booking_paid' => 0
				));
			}
			
		} else {
			$wpdb->query("UPDATE `wtw_booking` SET `status` = 1, `booking_action_time` = '$currentDateTime' WHERE `id` = $value->id");
			$wpdb->insert('wtw_booking_price', array(
				'booking_id' => $value->id,
				'booking_price' => 0,
				'booking_paid' => 0
			));
		}
		
	}

}

/* function getUserPromoCOde($userID) {
	global $wpdb;
	$getPromocode = get_user_meta($userID , "promoCode" , true);
	if(!empty($getPromocode) || !$getPromocode != "") {
		$getPromoDetails = "";
	} else {
		return false;
	}
}

function checkPromoCode($promo) {
	global $wpdb;

	date_default_timezone_set("America/Bogota");
	$currentDate = date("Y-m-d");
	$getPromoCode = $wpdb->get_results("SELECT * FROM `wtw_promocode` WHERE `name` = '$promo'");
	if (empty($getPromoCode)) {
		$json = array("success" => 0, "result" => 0, "error" => "Promo Code Inválido");
	} else {
		$couponStart = $getPromoCode[0]->start_data;
		$couponEnd = $getPromoCode[0]->end_date;
		if ($currentDate >= date("Y-m-d", strtotime($couponStart))) {
			update_user_meta($userID, "promoCode", $promoCode);
			$json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
		} else {
			$json = array("success" => 0, "result" => 0, "error" => "Promo Code Inválido");
		}
	}
} */

function getMyAgendaAvailable($userID , $agenda_datee , $agenda_start_time , $agenda_end_time) {
	global $wpdb;
	$status = "True";
	if (strtotime($agenda_start_time) > strtotime($agenda_end_time)) {
		$agendaBookingend_date = date("Y-m-d", strtotime('+1 day', strtotime($agenda_datee)));
	} else {
		$agendaBookingend_date = $agenda_datee;
	}
	
	$getData = $wpdb->get_results("SELECT * FROM `wtw_agenda` WHERE `user_id` = $userID");
	foreach ($getData as $key => $value) {
		if($value->agenda_date != $value->agenda_end_date) {
			if(strtotime($agenda_datee ." ". $agenda_start_time) <= strtotime($agendaBookingend_date ." ".$value->agenda_end_time)) {
				$agenda_date = date("Y-m-d", strtotime("-1 day", strtotime($agenda_datee)));
			} else {
				$agenda_date = $agenda_datee;
			}
		}
		if ($value->agenda_type == 2) {
			$datearray = array();
			$agendaDate = date("w", strtotime($value->agenda_date));
			$agendaEndDate = date("w", strtotime($value->agenda_end_date));
			if ($agendaDate == $agendaEndDate) {
				$datearray = array($agendaDate);
			} else {
				$datearray = array($agendaDate, $agendaEndDate);
			}
			$currentDate = date("w", strtotime($agenda_date));
			if (in_array($currentDate, $datearray)) {

				if (strtotime($value->agenda_start_time) > strtotime($value->agenda_end_time)) {
					$endDate = date("Y-m-d", strtotime("+1 day", strtotime($agenda_date)));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				} else {
					$endDate = date("Y-m-d", strtotime($agenda_date));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				}
				if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($startDate . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($startDate . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($endDate . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($endDate . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				
				
				

			}
		} elseif ($value->agenda_type == 3) {
			$Montharray = array();
			$agendaMonth = date("d", strtotime($value->agenda_date));
			$agendaEndMonth = date("d", strtotime($value->agenda_end_date));
			if ($agendaMonth == $agendaEndMonth) {
				$Montharray = array($agendaMonth);
			} else {
				$Montharray = array($agendaMonth, $agendaEndMonth);
			}
			$currentMonth = date("d", strtotime($agenda_date));
			if (in_array($currentMonth, $Montharray)) {
				if (strtotime($value->agenda_start_time) > strtotime($value->agenda_end_time)) {
					$endDate = date("Y-m-d", strtotime("+1 day", strtotime($agenda_date)));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				} else {
					$endDate = date("Y-m-d", strtotime($agenda_date));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				}
				if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($startDate . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($startDate . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($endDate . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($endDate . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				
			}
		} elseif ($value->agenda_type == 4) {
			$Montharray = array();
			$agendaMonth = date("m-d", strtotime($value->agenda_date));
			$agendaEndMonth = date("m-d", strtotime($value->agenda_end_date));
			if ($agendaMonth == $agendaEndMonth) {
				$Montharray = array($agendaMonth);
			} else {
				$Montharray = array($agendaMonth, $agendaEndMonth);
			}
			$currentMonth = date("m-d", strtotime($agenda_date));
			if (in_array($currentMonth, $Montharray)) {
				if (strtotime($value->agenda_start_time) > strtotime($value->agenda_end_time)) {
					$endDate = date("Y-m-d", strtotime("+1 day", strtotime($agenda_date)));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				} else {
					$endDate = date("Y-m-d", strtotime($agenda_date));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				}
				if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($startDate . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($startDate . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($endDate . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($endDate . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				
			}
		} elseif ($value->agenda_type == 1) {
			if (strtotime($value->agenda_start_time) > strtotime($value->agenda_end_time)) {
				$endDate = date("Y-m-d", strtotime("+1 day" , strtotime($agenda_date)));
				$startDate = date("Y-m-d", strtotime($agenda_date));
			} else {
				$endDate = date("Y-m-d", strtotime($agenda_date));
				$startDate = date("Y-m-d", strtotime($agenda_date));
			}
			
			if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($endDate . " " . $value->agenda_end_time))) {
				$status = "False";
			}
			if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($endDate . " " . $value->agenda_end_time))) {
				$status = "False";
			}

			if ((strtotime($startDate . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($startDate . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
				$status = "False";
			}
			if ((strtotime($endDate . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($endDate . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
				$status = "False";
			}
				
		} elseif ($value->agenda_type == 0) {
			if (date("Y-m-d", strtotime($agenda_date)) == $value->agenda_date) {
				if ((strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($value->agenda_date . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($value->agenda_end_date . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($value->agenda_date . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($value->agenda_end_date . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($value->agenda_date . " " . $value->agenda_start_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($value->agenda_date . " " . $value->agenda_start_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($value->agenda_end_date . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($value->agenda_end_date . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
			}
		}
	}
	return $status;
}
function getMyAgendaAvailableUpdate($userID , $agenda_date , $agenda_start_time , $agenda_end_time , $agendaID) {
	global $wpdb;
	$status = "True";
	
	$getData = $wpdb->get_results("SELECT * FROM `wtw_agenda` WHERE `user_id` = $userID AND `id` != $agendaID");
	foreach ($getData as $key => $value) {

		if ($value->agenda_date != $value->agenda_end_date) {
			if (strtotime($agenda_datee . " " . $agenda_start_time) <= strtotime($agendaBookingend_date . " " . $value->agenda_end_time)) {
				$agenda_date = date("Y-m-d", strtotime("-1 day", strtotime($agenda_datee)));
			} else {
				$agenda_date = $agenda_datee;
			}
		}
		if($value->agenda_type == 2) {
			$datearray = array();
			$agendaDate = date("w" , strtotime($value->agenda_date));
			$agendaEndDate = date("w" , strtotime($value->agenda_end_date));
			if($agendaDate == $agendaEndDate) {
				$datearray = array($agendaDate);
			} else {
				$datearray = array($agendaDate , $agendaEndDate);
			}
			$currentDate = date("w" , strtotime($agenda_date));
			if (in_array($currentDate, $datearray)) {

				if (strtotime($value->agenda_start_time) > strtotime($value->agenda_end_time)) {
				$endDate = date("Y-m-d", strtotime("+1 day" , strtotime($agenda_date)));
				$startDate = date("Y-m-d", strtotime($agenda_date));
			} else {
				$endDate = date("Y-m-d", strtotime($agenda_date));
				$startDate = date("Y-m-d", strtotime($agenda_date));
			}
				if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($startDate . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($startDate . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($endDate . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($endDate . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				
			}
		} elseif ($value->agenda_type == 3) {
			$Montharray = array();
			$agendaMonth = date("d", strtotime($value->agenda_date));
			$agendaEndMonth = date("d", strtotime($value->agenda_end_date));
			if ($agendaMonth == $agendaEndMonth) {
				$Montharray = array($agendaMonth);
			} else {
				$Montharray = array($agendaMonth, $agendaEndMonth);
			}
			$currentMonth = date("d", strtotime($agenda_date));
			if (in_array($currentMonth, $Montharray)) {
				if (strtotime($value->agenda_start_time) > strtotime($value->agenda_end_time)) {
					$endDate = date("Y-m-d", strtotime("+1 day", strtotime($agenda_date)));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				} else {
					$endDate = date("Y-m-d", strtotime($agenda_date));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				}
				if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($startDate . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($startDate . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($endDate . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($endDate . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				
			} 
		} elseif ($value->agenda_type == 4) {
			$Montharray = array();
			$agendaMonth = date("m-d", strtotime($value->agenda_date));
			$agendaEndMonth = date("m-d", strtotime($value->agenda_end_date));
			if ($agendaMonth == $agendaEndMonth) {
				$Montharray = array($agendaMonth);
			} else {
				$Montharray = array($agendaMonth, $agendaEndMonth);
			}
			$currentMonth = date("m-d", strtotime($agenda_date));
			if (in_array($currentMonth, $Montharray)) {
				if (strtotime($value->agenda_start_time) > strtotime($value->agenda_end_time)) {
					$endDate = date("Y-m-d", strtotime("+1 day", strtotime($agenda_date)));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				} else {
					$endDate = date("Y-m-d", strtotime($agenda_date));
					$startDate = date("Y-m-d", strtotime($agenda_date));
				}
				if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($startDate . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($startDate . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($endDate . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($endDate . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				
			} 
		} elseif ($value->agenda_type == 1) {
			if (strtotime($value->agenda_start_time) > strtotime($value->agenda_end_time)) {
				$endDate = date("Y-m-d", strtotime("+1 day", strtotime($agenda_date)));
				$startDate = date("Y-m-d", strtotime($agenda_date));
			} else {
				$endDate = date("Y-m-d", strtotime($agenda_date));
				$startDate = date("Y-m-d", strtotime($agenda_date));
			}
			if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($startDate . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($endDate . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($startDate . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($startDate . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($endDate . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($endDate . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				
		} elseif ($value->agenda_type == 0) {
			if (date("Y-m-d", strtotime($agenda_date)) == $value->agenda_date) {
				if ((strtotime($agenda_date . " " . $agenda_start_time) <= strtotime($value->agenda_date . " " . $value->agenda_start_time)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($value->agenda_end_date . " " . $value->agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($agendaBookingend_date . " " . $agenda_end_time) >= strtotime($value->agenda_date . " " . $value->agenda_start_time)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($value->agenda_end_date . " " . $value->agenda_end_time))) {
					$status = "False";
				}

				if ((strtotime($value->agenda_date . " " . $value->agenda_start_time) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($value->agenda_date . " " . $value->agenda_start_time) >= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
				if ((strtotime($value->agenda_end_date . " " . $value->agenda_end_time) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($value->agenda_end_date . " " . $value->agenda_end_time) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
					$status = "False";
				}
			}
		}
	}
	return $status;
}

function getMyBookingAvailable($userID, $agenda_date, $agenda_start_time, $agenda_end_time) { 
	global $wpdb;
	$status = "True";
	$getData = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $userID  AND `status` = 3");
	if (strtotime($agenda_start_time) > strtotime($agenda_end_time)) {
		$agendaBookingend_date = date("Y-m-d", strtotime('+1 day', strtotime($agenda_date)));
	} else {
		$agendaBookingend_date = $agenda_date;
	}
	if (strtotime($agendaBookingend_date) == strtotime($agenda_date)) {
		$MontharrayPre = array($agendaBookingend_date);
	} else {
		$MontharrayPre = array($agenda_date, $agendaBookingend_date);
	}
	foreach ($getData as $key => $value) {

		$Montharray = array();
		if (strtotime($value->booking_start) > strtotime($value->booking_end)) {
			$agenda_end_date = date("Y-m-d", strtotime('+1 day', strtotime($value->booking_date)));
		} else {
			$agenda_end_date = $value->booking_date;
		}
		if(strtotime($value->booking_date) == strtotime($agenda_end_date)) {
			$Montharray = array($agenda_end_date);
		} else {
			$Montharray = array($value->booking_date, $agenda_end_date);
		}
	
			if (in_array($agenda_date, $Montharray) || in_array($agendaBookingend_date, $Montharray)) {
				
				
			if ((strtotime($agenda_date ." ".$agenda_start_time) <= strtotime($value->booking_date . " " . $value->booking_start)) && (strtotime($agenda_date . " " . $agenda_start_time) >= strtotime($agenda_end_date. " ".$value->booking_end))) {
				$status = "False";
			}
			if ((strtotime($agendaBookingend_date ." ".$agenda_end_time) >= strtotime($value->booking_date  . " " . $value->booking_start)) && (strtotime($agendaBookingend_date . " " . $agenda_end_time) <= strtotime($agenda_end_date . " " . $value->booking_end))) {
				$status = "False";
			}

			if ((strtotime($value->booking_date . " " . $value->booking_start) <= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($value->booking_date . " " . $value->booking_start) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
				$status = "False";
			}
			if ((strtotime($agenda_end_date . " " . $value->booking_end) >= strtotime($agenda_date . " " . $agenda_start_time)) && (strtotime($agenda_end_date . " " . $value->booking_end) <= strtotime($agendaBookingend_date . " " . $agenda_end_time))) {
				$status = "False";
			}
		}
	}
	return $status;
}


function getMyAgendaDetails($userID, $agenda_date)
{
	global $wpdb;
	$status = "True";
	$getData = $wpdb->get_results("SELECT * FROM `wtw_agenda` WHERE `user_id` = $userID");
	$checkArray = array();
	foreach ($getData as $key => $value) {
		if ($value->agenda_type == 2) {
			$datearray = array();
			$agendaDate = date("w", strtotime($value->agenda_date));
			$agendaEndDate = date("w", strtotime($value->agenda_end_date));
			if ($agendaDate == $agendaEndDate) {
				$datearray = array($agendaDate);
			} else {
				$datearray = array($agendaDate, $agendaEndDate);
			}
			$currentDate = date("w", strtotime($agenda_date));
			if (in_array($currentDate, $datearray)) {
				$checkArray[] =  $value;
			}
		} elseif ($value->agenda_type == 3) {
			$Montharray = array();
			$agendaMonth = date("d", strtotime($value->agenda_date));
			$agendaEndMonth = date("d", strtotime($value->agenda_end_date));
			if ($agendaMonth == $agendaEndMonth) {
				$Montharray = array($agendaMonth);
			} else {
				$Montharray = array($agendaMonth, $agendaEndMonth);
			}
			$currentMonth = date("d", strtotime($agenda_date));
			if (in_array($currentMonth, $Montharray)) {
				$checkArray[] = $value;
			}
		} elseif ($value->agenda_type == 4) {
			$Montharray = array();
			$agendaMonth = date("m-d", strtotime($value->agenda_date));
			$agendaEndMonth = date("m-d", strtotime($value->agenda_end_date));
			if ($agendaMonth == $agendaEndMonth) {
				$Montharray = array($agendaMonth);
			} else {
				$Montharray = array($agendaMonth, $agendaEndMonth);
			}
			$currentMonth = date("m-d", strtotime($agenda_date));
			if (in_array($currentMonth, $Montharray)) {
				$checkArray[] = $value;
			}
		} elseif ($value->agenda_type == 1) {
			$checkArray[] = $value;
		} elseif($value->agenda_type == 0) {
			if(date("Y-m-d" , strtotime($agenda_date)) == $value->agenda_date) {
				$checkArray[] = $value;
			}
		}
	}
	return $checkArray;
}

function generateMyRefNumber() {

	global $wpdb;
	for ($i=0; $i < 15 ; $i++) {
		$digits = 5;
		$stringRandom = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
		$count = $wpdb->get_var("SELECT count(`id`) FROM `wtw_add_money` WHERE `ref_num` = '$stringRandom'");
		if($count == 0) {
			return $stringRandom;
			break;
		}
	}
}


