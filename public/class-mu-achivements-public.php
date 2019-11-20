<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://audilu.com
 * @since      1.0.0
 *
 * @package    Mu_Achivements
 * @subpackage Mu_Achivements/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mu_Achivements
 * @subpackage Mu_Achivements/public
 * @author     Audi Lu <khl0327@gmail.com>
 */
class Mu_Achivements_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in mu-achivements_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The mu-achivements_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'css/mu-achivements-public.css', 
			array(), 
			filemtime( (dirname( __FILE__ )) . '/css/mu-achivements-public.css' ),
			'all' 
		);

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in mu-achivements_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The mu-achivements_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'js/mu-achivements-public.js', 
			array( 'jquery' ), 
			filemtime( (dirname( __FILE__ )) . '/js/mu-achivements-public.js' ),
			false 
		);

	}

	public function register_shortcodes() {
		add_shortcode( 'my_short_code', array($this, 'my_short_code_display') );
	}

	public function my_short_code_display() {
		if (!is_admin()) {
			ob_start();
			?>
			<!-- my_short_code: some HTML tags with a little bit PHP. -->
			<?php
			/* Load client template from theme dir first, load template file of 
			 * plugin/templates/ if client template is not exist.	
			 */		
			// if ( $overridden_template = locate_template( 'my-template.php' ) ) {
			// 	load_template( $overridden_template );
			// } else {
			// 	load_template( dirname(dirname( __FILE__ )) . '/templates/my-template.php' );
			// }
			$results = ob_get_clean();
			return $results;
		}
	}

	private function get_author_arch_id($user_id = false) {
		if ( false === $user_id ) {
			$user_id = get_current_user_id();
		}

		$author_obj = get_user_by('id', $user_id);
	
		$cache = wp_cache_get( $user_id, 'mu_achids' );
		if ( false !== $cache ) {
			return $cache;
		}
	
		global $wpdb;
		$sql = "SELECT type FROM {$wpdb->prefix}mu_achivements WHERE email = '{$author_obj->user_email}' ";
		$results = $wpdb->get_results($sql);
		
		wp_cache_set( $user_id, $results, 'mu_achids' );
	
		return $results;
	}

	public function author_achicons($author_id) {
		if (!empty($author_id) && $author_id !== 0) {
			$ach_ids = $this->get_author_arch_id($author_id);
			foreach ($ach_ids as $ach) {
				$ach_id = absint($ach->type);
				if ($ach_id === 1) {
					return array(
						array(
							'id' => 1, 
							'icon' => '<span class="achivement_icon first_raiser" data-toggle="tooltip" data-placement="top" title="第一個舉手的人"><i class="fas fa-hand-paper"></i></span>', 
							'title' => '第一個舉手的人'
						)
					);
				}else{
					return array(
						array(
							'icon' => ''
						)
					);
				}
			}
		}
		return array();
	}
}
