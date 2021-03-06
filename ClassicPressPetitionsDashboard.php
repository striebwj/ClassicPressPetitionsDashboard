<?php

/**
 * Plugin Name:       ClassicPress Petitions Dashboard Widget
 * Plugin URI:        https://omukiguy.com
 * Description:       Find the latest petitions for ClassicPress growth.
 * Version:			  0.1.4
 * Author:            Laurence Bahiirwa
 * Author URI:        https://omukiguy.com
 * Requires PHP:      5.6
 * Text Domain:       cp_requests
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // End if().

if ( ! class_exists( 'ClassicPressPetitionsDashboard' ) ) {

	class ClassicPressPetitionsDashboard {

		/**
		 * Repeated Variables
		 */
		public $api_url;
		public $text_domain;

		public function __construct() {

			$this->api_url = 'https://api-v1.classicpress.net/features/1.0/';
			$this->text_domain = 'cp_requests';

		}

		/**
		 * Initialize the class when called.
		 */
		public function cp_start_plugin() {

			add_action( 'wp_dashboard_setup', array( $this, 'cp_add_dashboard_widgets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'cp_dashboard_scripts_enqueue' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'remove_wordpress_events' ) );

		}

		/**
		 * Add a widget to the dashboard.
		 */
		public function cp_add_dashboard_widgets() {

			wp_add_dashboard_widget(
				'cp_petitions_dashboard_widget', // Widget slug.
				esc_attr__( 'ClassicPress Petitions', $this->text_domain ), // Title.
				array( $this, 'cp_petitions_dashboard_widget_cb' ) // Display function.
			);

		}

		/**
		 * Callback function to output the contents of our Dashboard Widget.
		 */
		public function cp_petitions_dashboard_widget_cb() {

			/**
			 * Query API for JSON data -> decode results to php
			 *
			 * @return array
			 */
			$response = wp_remote_get( $this->api_url );

			if ( ! is_array( $response ) ) {
				return;
			}

			if ( is_array( $response ) ) {
				$body = $response['body']; // use the content
			}

			$json = json_decode( $body, true );

			$most_wanted = $json['most-wanted']; // get most wanted (upvoted)
			$recent = $json['recent']; // get recent
			$trending = $json['trending']; // get trending

			// TODO: Dropdown to see most wanted, trending, and recent
			echo '<div class="sub">
					<a href="' . esc_url( $json['link'] ) . '" target="_blank" class="cp_petitions_link">' . esc_attr__( 'Your voice counts, create and vote on petitions.', $this->text_domain ) . '<span class="screen-reader-text">' . esc_attr__( '(opens in a new window)', $this->text_domain ) . '</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>
				</div>';

			echo '<br>';

			$list = array( 'trending', 'recent', 'most-wanted' );

			//Tab Navigation head
			echo '<div class="tab">';

			/** 
			 * TODO: Get the first button to colorize like tab. Make tab navigation more obvious.
			 */
			foreach ( $list as $list_item ) {
					echo '<button class="tablinks '.$list_item.'"  onclick="showTable(event, \'' . $list_item . '\')">' . ucwords( str_replace("-"," ", $list_item )) . '</button>';
			}

			echo '</div>';
			
			//Tab Navigation body loop
			foreach ( $list as $list_item ) {
				echo '
				<div id="' . $list_item . '" class="tabcontent">
				<table class="cp_petitions">
					<col width="10%">
					<col width="90%">
					<thead>
						<tr>
							<td>' . esc_attr__( 'Votes', $this->text_domain ) . '</td>
							<td>' . esc_attr__( 'Petitions', $this->text_domain ) . '</td>
						</tr>
					</thead>
				';

				if ( $list_item == 'trending' ) {
					foreach( $trending['data'] as $key => $value ) {

						$votesCount = $value['votesCount'];
						$link = $value['link'];
						$title = $value['title'];
						$author = $value['createdBy'];
						$status = $value['status'];
						$createdTime = $value['createdAt'];
						$text_domain = $this->text_domain;

						ClassicPressPetitionsDashboard::table_body( $votesCount, $link, $title, $author, $status, $createdTime, $text_domain );

					}
				}

				if ( $list_item == 'recent' ) {
					foreach( $recent['data'] as $key => $value ) {

						$votesCount = $value['votesCount'];
						$link = $value['link'];
						$title = $value['title'];
						$author = $value['createdBy'];
						$status = $value['status'];
						$createdTime = $value['createdAt'];
						$text_domain = $this->text_domain;

						ClassicPressPetitionsDashboard::table_body( $votesCount, $link, $title, $author, $status, $createdTime, $text_domain );

					}
				}

				if ( $list_item == 'most-wanted' ) {
					foreach( $most_wanted['data'] as $key => $value ) {

						$votesCount = $value['votesCount'];
						$link = $value['link'];
						$title = $value['title'];
						$author = $value['createdBy'];
						$status = $value['status'];
						$createdTime = $value['createdAt'];
						$text_domain = $this->text_domain;

						ClassicPressPetitionsDashboard::table_body( $votesCount, $link, $title, $author, $status, $createdTime, $text_domain );

					}
				}
				echo '</table></div>';	
			}							
		}


		/**
		 * Enqueue all our scripts
		 */
		public function cp_dashboard_scripts_enqueue() {
			wp_enqueue_style( 'cp_requests' , plugin_dir_url( __FILE__ ) . 'assets/css/plugin.css' );
			wp_enqueue_script( 'cp_tabs', plugin_dir_url( __FILE__ ) . 'assets/js/tabs.js');
		}

		/**
		 * Remove WordPress events.
		 */
		public function remove_wordpress_events() {
			global $wp_meta_boxes;
			if ( ! isset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] ) ) {
				return;
			}
			if ( ! is_array( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] ) ) {
				return;
			}
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		}

		/**
		 * Table body Loop output
		 */
		private static function table_body( $votesCount, $link, $title, $author, $status, $createdTime, $text_domain ) {
		?>
			<tr>
				<td class="votes-count"><?php echo esc_attr( $votesCount ); ?></td>

				<td class="petition">
					<a target="_blank" href="<?php echo esc_url( $link ) ?>"><strong><?php echo esc_attr__( $title, $text_domain )?><span class="screen-reader-text"><?php echo esc_attr__( '(opens in a new window)', $text_domain ); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></strong></a>
					<?php
						esc_attr__( 'by', $text_domain ) . ' ' . ucwords(  esc_attr( $author ) );

						if ( $status == "open" ){
							echo esc_attr__( ' - ', $text_domain ) . ' ' . human_time_diff( strtotime( $createdTime ), current_time('timestamp') ) . ' ' . esc_attr__( 'ago', $text_domain );
						} 
						elseif ( $status == "planned" ){
							echo ' - ' . '<span class="planned">' . esc_attr__( ucfirst( $status ), $text_domain ) . '</span>';
						} 
						else{
							echo ' - ' . '<span class="started">' . esc_attr__( ucfirst( $status ), $text_domain ) . '</span>';
						}
					?>
				</td>
			</tr>
		<?php
		}

	}

	$run = new ClassicPressPetitionsDashboard;
	return $run->cp_start_plugin();
}
