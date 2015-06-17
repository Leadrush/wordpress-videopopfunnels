<?php
/**
 * VideoPopFunnels Wordpress Plugin
 *
 * @package   VideoPopFunnels_Admin
 * @author    ReallySuccessful Ltd. <support@reallysuccessful.com>
 * @license   GPL-2.0+
 * @link      http://reallysuccessfulsupport.com/
 * @copyright 2015 ReallySuccessful Ltd.
 */


class VideoPopFunnels_Admin {


	protected static $instance = null;
	protected $plugin_screen_hook_suffix = null;

	private function __construct() {


		$plugin = VideoPopFunnels::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		add_action( 'admin_init', array( $this, 'videopopfunnels_init' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );


		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );



	}



	public function videopopfunnels_init() {
		
		// Check for wp_footer
		$this->check_footer();
		register_setting( 'vpf-settings-group', 'videopopfunnels_script' );
		register_setting( 'vpf-settings-group', 'videopopfunnels_on_pages' );
		register_setting( 'vpf-settings-group', 'videopopfunnels_off_pages' );
	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), VideoPopFunnels::VERSION );
		}

	}


	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), VideoPopFunnels::VERSION );
		}

	}


	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_options_page(
			"VideoPopFunnels",
			"VideoPopFunnels",
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}


	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}


	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	protected function check_footer() {

		$has_footer = false;
		
		$needle = 'wp_footer()';
		$footer_file = get_template_directory() . '/footer.php';
		$footer_content = file_get_contents($footer_file);
		if (strpos($footer_content,$needle) === FALSE) {

			// check index.php file
			$index_file = get_template_directory() . '/index.php';
			$index_content = file_get_contents($index_file);
			if (strpos($index_content,$needle) !== FALSE)
				$has_footer = true;
			
		} else
			$has_footer = true;
		
		// If we found errors with the existence of wp_footer, hook into admin_notices to complain about it
		if ( ! $has_footer )
			add_action ( 'admin_notices', array($this,'test_footer_notices') );
	}

	// Output the notices
	public function test_footer_notices() {
 		
		// If we made it here it is because there were errors
		
		// only display notice on plugin settings page
		global $pagenow;
		if ( $pagenow == 'options-general.php' && isset($_GET['page']) && $_GET['page'] == $this->plugin_slug) {
				
			echo '<div class="error"><p><strong>Your active theme is not compatible with this plugin.<br>
			It does not call wp_footer().<br>
			Please contact your theme creator.</strong></p></div>';
		}
	}

}
