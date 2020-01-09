<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://delay-delo.com
 * @since      1.0.0
 *
 * @package    Multiblock_Page
 * @subpackage Multiblock_Page/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Multiblock_Page
 * @subpackage Multiblock_Page/includes
 * @author     Алексей Ртищев <alexmonkwork@gmail.com>
 */
class Multiblock_Page_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'multiblock-page',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
