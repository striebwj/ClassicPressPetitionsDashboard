<?php

/**
 * Plugin Name:       ClassicPress Petitions Dashboard Widget
 * Plugin URI:        https://omukiguy.com
 * Description:       Find the latest petitions for ClassicPress growth.
 * Version:			  1.0.3
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

			echo <<<EOL
			<div class="tab">
			  <button class="tablinks" onclick="showTable(event, 'Trending')">Trending</button>
			  <button class="tablinks" onclick="showTable(event, 'Recent')">Recent</button>
			  <button class="tablinks" onclick="showTable(event, 'Most-Wanted')">Most Wanted</button>
			</div>


			<div id="Trending" class="tabcontent">
			<table width="100%" class="cp_petitions">
				<col width="10%">
				<col width="90%">
				<tr>
					<th>Votes</th>
					<th>Petitions</th>
				</tr>
			</div>
EOL;

		foreach( $trending['data'] as $key => $value ) {
		?>
		<tr>
			<th class="votes-count"><?php echo esc_attr( $value['votesCount'] ); ?></th>

			<th class="petition">
				<a target="_blank" href="<?php echo esc_url( $value['link'] ) ?>">
					<strong>
						<?php echo esc_attr__( $value['title'], $this->text_domain )?>
						<span class="screen-reader-text">' . esc_attr__( '(opens in a new window)', $this->text_domain ) . '</span>
						<span aria-hidden="true" class="dashicons dashicons-external"></span>
					</strong>
				</a>
				<?php
				esc_attr__( 'by', $this->text_domain ) . ' ' . ucwords(  esc_attr( $value['createdBy'] ) );

				if($value['status'] == "open"){
					echo esc_attr__( ' - ', $this->text_domain ) . ' '; echo human_time_diff(  strtotime($value['createdAt']), current_time('timestamp') ) . ' ' . esc_attr__( 'ago', $this->text_domain );
				}else{
					echo esc_attr_e( ' - ', $this->text_domain ) . esc_attr_e( ucfirst( $value['status'] ), $this->text_domain );
				}
				?>
			</th>
		</tr>
		<?php
		}

		echo '</table></div>';

		echo <<<EOL
		<div id="Recent" class="tabcontent">
		<table width="100%" class="cp_petitions">
			<col width="10%">
			<col width="90%">
			<tr>
				<th>Votes</th>
				<th>Petitions</th>
			</tr>
		</div>
EOL;

foreach( $recent['data'] as $key => $value ) {
?>
<tr>
	<th class="votes-count"><?php echo esc_attr( $value['votesCount'] ); ?></th>

	<th class="petition">
		<a target="_blank" href="<?php echo esc_url( $value['link'] ) ?>">
			<strong>
				<?php echo esc_attr__( $value['title'], $this->text_domain )?>
				<span class="screen-reader-text">' . esc_attr__( '(opens in a new window)', $this->text_domain ) . '</span>
				<span aria-hidden="true" class="dashicons dashicons-external"></span>
			</strong>
		</a>
		<?php
		esc_attr__( 'by', $this->text_domain ) . ' ' . ucwords(  esc_attr( $value['createdBy'] ) );

		if($value['status'] == "open"){
			echo esc_attr__( ' - ', $this->text_domain ) . ' '; echo human_time_diff(  strtotime($value['createdAt']), current_time('timestamp') ) . ' ' . esc_attr__( 'ago', $this->text_domain );
		}else{
			echo esc_attr_e( ' - ', $this->text_domain ) . esc_attr_e( ucfirst( $value['status'] ), $this->text_domain );
		}
		?>
	</th>
</tr>
<?php
}

echo '</table></div>';

echo <<<EOL
		<div id="Most-Wanted" class="tabcontent">
		<table width="100%" class="cp_petitions">
			<col width="10%">
			<col width="90%">
			<tr>
				<th>Votes</th>
				<th>Petitions</th>
			</tr>
		</div>
EOL;

foreach( $most_wanted['data'] as $key => $value ) {
?>
<tr>
	<th class="votes-count"><?php echo esc_attr( $value['votesCount'] ); ?></th>

	<th class="petition">
		<a target="_blank" href="<?php echo esc_url( $value['link'] ) ?>">
			<strong>
				<?php echo esc_attr__( $value['title'], $this->text_domain )?>
				<span class="screen-reader-text">' . esc_attr__( '(opens in a new window)', $this->text_domain ) . '</span>
				<span aria-hidden="true" class="dashicons dashicons-external"></span>
			</strong>
		</a>
		<?php
		esc_attr__( 'by', $this->text_domain ) . ' ' . ucwords(  esc_attr( $value['createdBy'] ) );

		if($value['status'] == "open"){
			echo esc_attr__( ' - ', $this->text_domain ) . ' '; echo human_time_diff(  strtotime($value['createdAt']), current_time('timestamp') ) . ' ' . esc_attr__( 'ago', $this->text_domain );
		}else{
			echo esc_attr_e( ' - ', $this->text_domain ) . esc_attr_e( ucfirst( $value['status'] ), $this->text_domain );
		}
		?>
	</th>
</tr>
<?php
}

echo '</table></div>';
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

	}

	$run = new ClassicPressPetitionsDashboard;
	return $run->cp_start_plugin();
}
