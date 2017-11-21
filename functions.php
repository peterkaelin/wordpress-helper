<?php
/**
 * WordPress helper functions for functions.php
 *
 * @author  Peter Kälin <peter.kaelin@websamurai.ch>
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
    $content_width = 750; /* pixels */


if ( ! function_exists( 'origin_setup' ) ) :
    /**
     * Set up theme defaults and register support for various WordPress features.
     */
    function origin_setup() {
        /**
         * This theme styles the visual editor with editor-style.css to match the theme style.
         */
        add_editor_style();

        /**
         * Add default posts and comments RSS feed links to head
         */
        add_theme_support( 'automatic-feed-links' );

        /**
         * Enable support for Post Thumbnails on posts and pages
         */
        add_theme_support( 'post-thumbnails' );

        /**
         * Setup the WordPress core custom background feature.
         */
        add_theme_support( 'custom-background', apply_filters( 'origin_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        /**
         * Make theme available for translation
         * Translations can be filed in the /languages/ directory (e.g. de_DE.po and de_DE.mo)
         */
        load_theme_textdomain( 'origin', get_template_directory() . '/languages' );

        /**
         * Define WP nav menus
         */
        register_nav_menus( array(
            'primary'  => __( 'Main menu', 'origin' ),
        ) );

        /**
         * Add additional image sizes
         */
        add_image_size( 'header', 1680, 600, true ); // true: crop it

    }
endif; // origin_setup
add_action( 'after_setup_theme', 'origin_setup' );


/**
 * Register widgetized area and update sidebar with default widgets.
 */
function origin_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'origin' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'origin' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer', 'origin' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here.', 'origin' ),
        'before_widget' => '<section id="%1$s" class="widget col-md-4 col-sm-6 %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'origin_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function origin_scripts() {
    wp_enqueue_style( 'origin-style', get_stylesheet_uri(), array(), '1.0' );

    wp_enqueue_script('bootstrapjs', get_template_directory_uri().'/includes/resources/bootstrap/js/bootstrap.min.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'origin_scripts' );


/**
 * Register post types
 *
 */
function origin_init() {

    // Register custom post type: Portfolio
    $labels = array(
        'name'               => esc_html_x( 'Portfolio', 'post type general name', 'origin' ),
        'singular_name'      => esc_html_x( 'Abstract', 'post type singular name', 'origin' ),
        'menu_name'          => esc_html__( 'Portfolio', 'origin' ),
        'name_admin_bar'     => esc_html__( 'Abstract', 'origin' ),
        'add_new'            => esc_html__( 'Add New', 'origin' ),
        'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'origin' ), esc_html__( 'Abstract', 'origin' ) ),
        'new_item'           => sprintf( esc_html__( 'New %s', 'origin' ), esc_html__( 'Abstract', 'origin' ) ),
        'edit_item'          => sprintf( esc_html__( 'Edit %s', 'origin' ), esc_html__( 'Abstract', 'origin' ) ),
        'view_item'          => sprintf( esc_html__( 'View %s', 'origin' ), esc_html__( 'Abstract', 'origin' ) ),
        'all_items'          => sprintf( esc_html__( 'All %s', 'origin' ), esc_html__( 'Portfolio', 'origin' ) ),
        'search_items'       => sprintf( esc_html__( 'Search %s', 'origin' ), esc_html__( 'Portfolio', 'origin' ) ),
        'parent_item_colon'  => sprintf( esc_html__( 'Parent %s:', 'origin' ), esc_html__( 'Portfolio', 'origin' ) ),
        'not_found'          => sprintf( esc_html__( 'No %s found.', 'origin' ), esc_html__( 'Abstract', 'origin' ) ),
        'not_found_in_trash' => sprintf( esc_html__( 'No %s found in Trash.', 'origin' ), esc_html__( 'Abstract', 'origin' ) )
    );
    $args = array(
        'labels'             => $labels,
        'description'        => esc_html__( 'Description', 'origin' ),
        'public'             => true,
        'publicly_queryable' => true,
        'menu_icon'          => 'dashicons-category',
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'abstract' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' )
    );
    register_post_type( 'abstract', $args );

    // Regsiter Taxonomy: Portfolio categories
    $labels = array(
        'name'                       => _x( 'Categories', 'portfolio category general name', 'origin' ),
        'singular_name'              => _x( 'Category', 'portfolio category singular name', 'origin' ),
        'search_items'               => sprintf( __( 'Search %s', 'origin' ), __( 'Categories', 'origin' ) ),
        'popular_items'              => sprintf( __( 'Popular %s', 'origin' ), __( 'Categories', 'origin' ) ),
        'all_items'                  => sprintf( __( 'All %s', 'origin' ), __( 'Categories', 'origin' ) ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => sprintf( __( 'Edit %s', 'origin' ), __( 'Category', 'origin' ) ),
        'update_item'                => sprintf( __( 'Update %s', 'origin' ), __( 'Category', 'origin' ) ),
        'add_new_item'               => sprintf( __( 'Add New %s', 'origin' ), __( 'Category', 'origin' ) ),
        'new_item_name'              => sprintf( __( 'New %s Name', 'origin' ), __( 'Category', 'origin' ) ),
        'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'origin' ), __( 'Categories', 'origin' ) ),
        'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'origin' ), __( 'Categories', 'origin' ) ),
        'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'origin' ), __( 'Categories', 'origin' ) ),
        'not_found'                  => sprintf( __( 'No %s found.', 'origin' ), __( 'Categories', 'origin' ) ),
        'menu_name'                  => __( 'Categories', 'origin' ),
    );

    $args = array(
        'hierarchical'          => true,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'portfolio-category' ),
    );

    register_taxonomy( 'portfolio-category', 'portfolio', $args );
}
add_action( 'init', 'origin_init' );


/**
 * Remove Emoji scripts.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );


/**
 * Remove generator tag.
 */
remove_action('wp_head', 'wp_generator');


/**
 * Remove some other unused scripts from head.
 *
 * The feed link should be outcommented, if website uses news
 */
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link');
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link' );
remove_action( 'wp_head', 'start_post_rel_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );


/**
 * Remove recent comments style from header.
 */
function origin_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'  ) );
}
add_action( 'widgets_init', 'origin_remove_recent_comments_style' );


/**
 * Add and remove Admin menu pages.
 */
function origin_add_remove_menu_pages() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'origin_add_remove_menu_pages' );


/**
 * Change login logo.
 */
function origin_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/origin.png);
            width: 100%;
            height: 207px;
            background-size: contain;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'origin_login_logo' );


/**
 * Hide the wordpress logo in admin menu
 *
 * @param mixed $wp_admin_bar
 */
function origin_remove_wp_logo( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'wp-logo' );
}
add_action( 'admin_bar_menu', 'origin_remove_wp_logo', 999 );


/**
 * Fix for Yoimage image cropping
 * because Yoimage save button jumps around while clicking it
 */
function origin_admin_css_yooimage_fix() {
    echo '<style>
        .attachment-details {
            overflow-y: hidden;
        }
    </style>';
}
add_action( 'admin_head', 'origin_admin_css_yooimage_fix' );