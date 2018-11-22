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

class ClassicPressPetitionsDashboard {

	public function start() {
		add_action( 'wp_dashboard_setup', array( $this, 'cp_add_dashboard_widgets' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'remove_wordpress_events' ) );
	}

	/**
	 * Add a widget to the dashboard.
	 */
	public function cp_add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'cp_petitions_dashboard_widget', // Widget slug.
			'ClassicPress Petitions', // Title.
			array( $this, 'cp_petitions_dashboard_widget_cb' ) // Display function.
		);

	}

	/**
	 * Callback function to output the contents of our Dashboard Widget.
	 */
	public function cp_petitions_dashboard_widget_cb() {
		$string = file_get_contents("https://petitions.classicpress.net/api/v1/posts?f=most-wanted");
		$json = json_decode($string, true);

		foreach( $json as $key => $value ) {
		?>
			<ul class="cp_petitions">
				<li><a href="https://petitions.classicpress.net/posts/<?php echo $value['number'] . '/' . $value['slug']; ?>" target="_blank"><?php echo $value['title']; ?></a> by <?php echo $value['user']['name']; ?><br >
				<!-- 	<?php echo $value['description']; ?> -->
					<strong>Up Votes:</strong> <?php echo $value['votesCount']; ?>
					<strong>Status:</strong> <?php echo $value['status']; ?>
					<strong>Created:</strong> <?php echo date('d-M-Y',strtotime($value['createdAt'])); ?>
				</li>
			</ul>
		<?php
		}
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
return $run->start();
