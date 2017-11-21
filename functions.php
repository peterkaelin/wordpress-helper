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


if ( ! function_exists( 'websamurai_setup' ) ) :
    /**
     * Set up theme defaults and register support for various WordPress features.
     */
    function websamurai_setup() {
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
        add_theme_support( 'custom-background', apply_filters( 'websamurai_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        /**
         * Make theme available for translation
         * Translations can be filed in the /languages/ directory (e.g. de_DE.po and de_DE.mo)
         */
        load_theme_textdomain( 'websamurai', get_template_directory() . '/languages' );

        /**
         * Define WP nav menus
         */
        register_nav_menus( array(
            'primary'  => __( 'Main menu', 'websamurai' ),
        ) );

        /**
         * Add additional image sizes
         */
        add_image_size( 'header', 1680, 600, true ); // true: crop it

    }
endif; // websamurai_setup
add_action( 'after_setup_theme', 'websamurai_setup' );


/**
 * Register widgetized area and update sidebar with default widgets.
 */
function websamurai_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'websamurai' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'websamurai' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer', 'websamurai' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here.', 'websamurai' ),
        'before_widget' => '<section id="%1$s" class="widget col-md-4 col-sm-6 %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'websamurai_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function websamurai_scripts() {
    wp_enqueue_style( 'websamurai-style', get_stylesheet_uri(), array(), '1.0' );

    wp_enqueue_script('bootstrapjs', get_template_directory_uri().'/includes/resources/bootstrap/js/bootstrap.min.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'websamurai_scripts' );


/**
 * Register post types
 *
 */
function websamurai_init() {

    // Register custom post type: Portfolio
    $labels = array(
        'name'               => esc_html_x( 'Portfolio', 'post type general name', 'websamurai' ),
        'singular_name'      => esc_html_x( 'Abstract', 'post type singular name', 'websamurai' ),
        'menu_name'          => esc_html__( 'Portfolio', 'websamurai' ),
        'name_admin_bar'     => esc_html__( 'Abstract', 'websamurai' ),
        'add_new'            => esc_html__( 'Add New', 'websamurai' ),
        'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'websamurai' ), esc_html__( 'Abstract', 'websamurai' ) ),
        'new_item'           => sprintf( esc_html__( 'New %s', 'websamurai' ), esc_html__( 'Abstract', 'websamurai' ) ),
        'edit_item'          => sprintf( esc_html__( 'Edit %s', 'websamurai' ), esc_html__( 'Abstract', 'websamurai' ) ),
        'view_item'          => sprintf( esc_html__( 'View %s', 'websamurai' ), esc_html__( 'Abstract', 'websamurai' ) ),
        'all_items'          => sprintf( esc_html__( 'All %s', 'websamurai' ), esc_html__( 'Portfolio', 'websamurai' ) ),
        'search_items'       => sprintf( esc_html__( 'Search %s', 'websamurai' ), esc_html__( 'Portfolio', 'websamurai' ) ),
        'parent_item_colon'  => sprintf( esc_html__( 'Parent %s:', 'websamurai' ), esc_html__( 'Portfolio', 'websamurai' ) ),
        'not_found'          => sprintf( esc_html__( 'No %s found.', 'websamurai' ), esc_html__( 'Abstract', 'websamurai' ) ),
        'not_found_in_trash' => sprintf( esc_html__( 'No %s found in Trash.', 'websamurai' ), esc_html__( 'Abstract', 'websamurai' ) )
    );
    $args = array(
        'labels'             => $labels,
        'description'        => esc_html__( 'Description', 'websamurai' ),
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
        'name'                       => _x( 'Categories', 'portfolio category general name', 'websamurai' ),
        'singular_name'              => _x( 'Category', 'portfolio category singular name', 'websamurai' ),
        'search_items'               => sprintf( __( 'Search %s', 'websamurai' ), __( 'Categories', 'websamurai' ) ),
        'popular_items'              => sprintf( __( 'Popular %s', 'websamurai' ), __( 'Categories', 'websamurai' ) ),
        'all_items'                  => sprintf( __( 'All %s', 'websamurai' ), __( 'Categories', 'websamurai' ) ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => sprintf( __( 'Edit %s', 'websamurai' ), __( 'Category', 'websamurai' ) ),
        'update_item'                => sprintf( __( 'Update %s', 'websamurai' ), __( 'Category', 'websamurai' ) ),
        'add_new_item'               => sprintf( __( 'Add New %s', 'websamurai' ), __( 'Category', 'websamurai' ) ),
        'new_item_name'              => sprintf( __( 'New %s Name', 'websamurai' ), __( 'Category', 'websamurai' ) ),
        'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'websamurai' ), __( 'Categories', 'websamurai' ) ),
        'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'websamurai' ), __( 'Categories', 'websamurai' ) ),
        'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'websamurai' ), __( 'Categories', 'websamurai' ) ),
        'not_found'                  => sprintf( __( 'No %s found.', 'websamurai' ), __( 'Categories', 'websamurai' ) ),
        'menu_name'                  => __( 'Categories', 'websamurai' ),
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
add_action( 'init', 'websamurai_init' );


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
function websamurai_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'  ) );
}
add_action( 'widgets_init', 'websamurai_remove_recent_comments_style' );


/**
 * Add and remove Admin menu pages.
 */
function websamurai_add_remove_menu_pages() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'websamurai_add_remove_menu_pages' );


/**
 * Change login logo.
 */
function websamurai_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/websamurai.png);
            width: 100%;
            height: 207px;
            background-size: contain;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'websamurai_login_logo' );


/**
 * Hide the wordpress logo in admin menu
 *
 * @param mixed $wp_admin_bar
 */
function websamurai_remove_wp_logo( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'wp-logo' );
}
add_action( 'admin_bar_menu', 'websamurai_remove_wp_logo', 999 );


/**
 * Fix for Yoimage image cropping
 * because Yoimage save button jumps around while clicking it
 */
function websamurai_admin_css_yooimage_fix() {
    echo '<style>
        .attachment-details {
            overflow-y: hidden;
        }
    </style>';
}
add_action( 'admin_head', 'websamurai_admin_css_yooimage_fix' );