<?php
/**
 * VideoPopFunnels Wordpress Plugin
 *
 * @package   VideoPopFunnels
 * @author    ReallySuccessful Ltd. <support@reallysuccessful.com>
 * @license   GPL-2.0+
 * @link      http://reallysuccessfulsupport.com/
 * @copyright 2015 ReallySuccessful Ltd.
 */


class VideoPopFunnels {


	const VERSION = '1.0.0';

	protected $plugin_slug = 'videopopfunnels';
	protected static $instance = null;

	private function __construct() {


		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_scripts' ));

	}


	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}


	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}


	private static function single_activate() {
	}


	private static function single_deactivate() {
	}


	public function enqueue_scripts() {
		
		$show_here = true;
		global $post;
		
		$on_pages = get_option( 'videopopfunnels_on_pages' );
		$off_pages = get_option( 'videopopfunnels_off_pages' );
		
		$value = get_option( 'videopopfunnels_script' );
		
		if (isset($on_pages) && !empty($on_pages)) {
			
			$on_pages = preg_split('/\r\n|\n|\r/', $on_pages);
			
			if (is_array($on_pages)) {
				
				array_walk($on_pages, 'trim');
			
				if (!$this->in_arrayi($post->post_title,$on_pages) && !in_array($post->ID,$on_pages))
					$show_here = false;
			}
		} else if (isset($off_pages) && !empty($off_pages)) {
			
			$off_pages = preg_split('/\r\n|\n|\r/', $off_pages);
			
			if (is_array($off_pages)) {
				
				array_walk($off_pages, 'trim');
			
				if ($this->in_arrayi($post->post_title,$off_pages) || in_array($post->ID,$off_pages))
					$show_here = false;
			}
		} 
		
		if ($show_here && isset($value) && !empty($value)) {
			echo $value;
		}
		
	}
	
	private function in_arrayi($needle, $haystack) {
    	
    	return in_array(strtolower($needle), array_map('strtolower', $haystack));
	}

}
