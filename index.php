<?php
/*

	Plugin Name: Theme Integrator
	Plugin URI: https://geek.hellyer.kiwi/theme-integrator/
	Description: A WordPress plugin which helps integrate your WordPress theme into other softwares
	Author: Ryan Hellyer
	Version: 1.0.7
	Author URI: https://geek.hellyer.kiwi/

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/


/**
 * Define some constants
 * @since 0.1
 */
define( THEME_INTEGRATION_DIR, WP_PLUGIN_DIR . '/pixopoint-theme-integrator/' );
define( THEME_INTEGRATION_URL, WP_PLUGIN_URL . '/pixopoint-theme-integrator/' );
define( THEME_INTEGRATION_IMAGES_URL, THEME_INTEGRATION_URL . 'images/' );
define( THEME_INTEGRATION_ENDCHUNK, '<!-- Theme integrator ending a chunk here -->' );
define( THEME_INTEGRATION_FOLDER, 'pixopoint-theme-integrator' );
define( THEME_INTEGRATION_CHUNKNAME, 'chunk' );


/**
 * Loading the plugin admin page or core
 * @since 0.1
 */
if ( is_admin() )
	require( 'admin_page.php' );
else
	require( 'core.php' );


