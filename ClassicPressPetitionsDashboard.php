<?php

/**
 * Plugin Name:       ClassicPress Petitions Dashboard Widget
 * Plugin URI:        https://omukiguy.com
 * Description:       Find the latest petitions for ClassicPress growth.
 * Version:			  1.0.0
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
		public $petitions_url;
		public $text_domain;
		
		public function __construct() {

			$this->api_url = 'https://classicpress.fider.io/api/v1/posts?view=most-wanted';
			$this->petitions_url = 'https://petitions.classicpress.net';
			$this->text_domain = 'cp_requests';

		}

		/**
		 * Initialize the class when called.
		 */
		public function cp_start_plugin() {
			add_action( 'wp_dashboard_setup', array( $this, 'cp_add_dashboard_widgets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'cp_dashboard_scripts_enqueue' ) );
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
			$string = file_get_contents( $this->api_url );
			$json = json_decode($string, true);
			
			echo '<ul class="cp_petitions">';

			$i = 0;
			foreach( $json as $key => $value ) { 
				//Limit the shown petitions to 10 only
				if ( $i++ > 10 ) break;
			?>
					<li>
						<a target="_blank"
							href="<?php echo esc_url( $this->petitions_url . '/posts/' . $value['number'] . '/' . $value['slug'] ) . '">' . esc_attr__( $value['title'], $this->text_domain ) . ' ' . '<span class="screen-reader-text">' . esc_attr__( '(opens in a new window)', $this->text_domain ) . '</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>' . ' ' . esc_attr__( 'by', $this->text_domain ) . ' ' . ucwords(  esc_attr( $value['user']['name'] ) ); ?>
						<table>
							<tr>
								<td><strong>Up Votes:</strong> <span class="votes-count"><?php echo esc_attr( $value['votesCount'] ); ?><span></td>
								<td><strong>Status:</strong> <?php echo esc_attr_e( ucfirst( $value['status'] ), $this->text_domain ); ?></td>
								<td><strong>Created:</strong> <?php echo date('d-M-Y',strtotime( $value['createdAt'] ) ); ?> </td>
							</tr>
						</table>
					</li>
			<?php
			}
			
			echo '</ul>';

			echo '<div class="sub">
					<a href="' . esc_url( $this->petitions_url ) . '" target="_blank" class="cp_petitions_link">' . esc_attr__( 'Your voice counts! Make your Petition', $this->text_domain ) . '<span class="screen-reader-text">' . esc_attr__( '(opens in a new window)', $this->text_domain ) . '</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>
				</div>';
			
		}

		public function cp_dashboard_scripts_enqueue() {
			// enqueue all our scripts
			wp_enqueue_style( 'cp_requests' , plugin_dir_url( __FILE__ ) . 'assets/css/plugin.css' );
		}
	}
	
	/**
	 * Run the Class.
	 */
	$run = new ClassicPressPetitionsDashboard;
	return $run->cp_start_plugin();
}