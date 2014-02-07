<?php
/**
 * reply templates
 *
 * @package   axiom
 * @author    averta
 * @license   GPL-2.0+
 * @copyright 2014 
 */

class ReplyTemplate {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.1';

	/**
	 * Unique identifier for plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'reply-template';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load public-facing style sheet and JavaScript.
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Add new post type for reply-template
		add_action( 'init', array( $this, 'reply_template_post_type_init' ) );

		// add a drop down list before reply form for selecting reply templates
		add_action( 'bbp_theme_before_reply_form_content', array( $this, 'axiom_plugin_rt_before_reply_form_content' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( 'selectize', plugins_url( 'assets/css/selectize.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( 'selectize.default', plugins_url( 'assets/css/selectize.default.css', __FILE__ ), array(), self::VERSION );
	}


	/**
	 * Register and enqueue public-specific JavaScript.
	 *
	 * @since     1.0.0
	 */
	public function enqueue_scripts() {

		if( current_user_can( 'moderate' ) ) {
	 		
	 		wp_enqueue_script( 'selectize', 
	 		                  	plugins_url( 'assets/js/selectize.min.js', __FILE__ ), 
	 		                  	array( 'jquery' ), 
	 		                  	self::VERSION, 
	 		                  	true );
		}

		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
	}


	/**
	 * Adds new post type for reply template
	 * note : this post type is not publicly available
	 * @since 	 1.0.0 
	 */
	public function reply_template_post_type_init() {

	    $labels = array(
	        'name'              => __('Reply Templates'		, 'reply-template'),
	        'singular_name'     => __('Reply Template'		, 'reply-template'),
	        'add_new'           => __('Add New'				, 'reply-template'),
	        'all_items'         => __('All Templates'		, 'reply-template'),
	        'add_new_item'      => __('Add New Reply Template', 'reply-template'),
	        'edit_item'         => __('Edit Reply Template'	, 'reply-template'),
	        'new_item'          => __('New Reply Template'	, 'reply-template'),
	        'view_item'         => __('View Reply Templates', 'reply-template'),
	        'search_items'      => __('Search Reply Templates', 'reply-template'),
	        'not_found'         => __('No Reply Template found', 'reply-template'),
	        'not_found_in_trash'=> __('No Reply Template Found in Trash', 'reply-template'), 
	        'parent_item_colon' => ''
	    );

	    $rewrite = array(	'slug' 		=> apply_filters('axiom_plugin_reply_template_structure', false),
	    					'with_front'=> false);
	      
	    $args = array(
	        'labels'            => $labels,
	        'public'            => true,
	        'show_ui'           => true, 
	        'query_var'         => true,
	        'rewrite'           => false,
	        'capability_type'   => 'post',
	        'hierarchical'      => false,
	        'exclude_from_search' => true,
			'publicly_queryable'  => true,
	        'menu_position'     => 34,
	        'supports'          => array('title','editor','excerpt', 'page-attributes'),
	        'has_archive'       => apply_filters('axiom_plugin_reply-template_archive_structure', 'log/all')
	    ); 

	    register_post_type( "reply-template", $args);

		 
	    // labels for reply template category
	    $reply_category_labels = array(
	        'name'              => __( 'Reply Categories'			, 'reply-template' ),
	        'singular_name'     => __( 'Reply Category'				, 'reply-template' ),
	        'search_items'      => __( 'Search in Reply Categories'	, 'reply-template'),
	        'all_items'         => __( 'All Reply Categories'		, 'reply-template'),
	        'most_used_items'   => null,
	        'parent_item'       => null,
	        'parent_item_colon' => null,
	        'edit_item'         => __( 'Edit Reply Category'		, 'reply-template'), 
	        'update_item'       => __( 'Update Reply Category'		, 'reply-template'),
	        'add_new_item'      => __( 'Add New Reply Category'		, 'reply-template'),
	        'new_item_name'     => __( 'New Reply Category' 		, 'reply-template'),
	        'menu_name'         => __( 'Categories' 				, 'reply-template'),
	    );
	    
	    register_taxonomy('reply-template-cat', array('reply-template'), array(
	        'hierarchical'      => true,
	        'labels'            => $reply_category_labels,
	        'singular_name'     => 'Reply Category',
	        'show_ui'           => true,
	        'query_var'         => true,
	        'rewrite'           => array('slug' => 'reply-cat' )
	    ));

	}


	public function axiom_plugin_rt_before_reply_form_content() {

		// just display reply template list to bbpress moderators
		if( ! current_user_can( 'moderate' ) ) {
			return;
		}

		// get reply template categories
		$cats = get_terms('reply-template-cat');
		
		// create markup for drop down list
		$dropdown  = "<select id='axi-replay-keys' >";
		$dropdown .= "<option value='' >" . __('-- SELECT --', 'repy-template') . "</option>";

		// create markup for templates list
		$reply_list  = "<ul id='axi-replay-templates' >";

		foreach ($cats as $key => $cat) {
			
			// group reply templates with same category
			$dropdown .= "<optgroup label='$cat->name' >";

		    $tax_args = array('taxonomy' => 'reply-template-cat', 'terms' => $cat->slug, 'field' => 'slug' );
		    
		    // create wp_query to get all logs
		    $args = array(
		      'post_type'			=> 'reply-template',
		      'orderby'				=> "menu_order date",
		      'post_status'			=> 'publish',
		      'posts_per_page'		=> -1,
		      'ignore_sticky_posts'	=> 1,
		      'paged'				=> 1,
		      'tax_query'			=> array( $tax_args )
		    );


		    // The Query
		    $rt_query = null;
			$rt_query = new WP_Query( $args );

			// loop through all reply-templates
			if ( $rt_query->have_posts() ) {
				while ( $rt_query->have_posts() ) {
					$rt_query->the_post();

					$title 	   = get_the_title($rt_query->post->ID);
					$post_id   = $rt_query->post->ID;
					$dropdown .= "<option value='$post_id'>$title</option>";

					$content   = get_the_content($rt_query->post->ID);
					$reply_list .= "<li data-reply-id='$post_id' >$content</li>"; 
				}
			}

			$dropdown .= "</optgroup>";
		}
		
		// Restore original Post Data
		wp_reset_postdata();


		$reply_list .= "</ul>"; 
		$dropdown   .= "</select>";

		echo $reply_list;
		echo $dropdown;
	}

	/**
	 * On plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		update_option( 'axiom_plugin_reply_template_flushed', 0 );
	}

	/**
	 * On plugin deactivation.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

}
