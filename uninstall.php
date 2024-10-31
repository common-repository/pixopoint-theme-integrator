<?php
/*

	This file deletes all of the data stored in the database - used by uninstall script

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/


/**
 * Following line of code is needed for security purposes. Prevents outsiders from running the script.
 * @since 0.1
 */
if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();


/**
 * Deletes stuff stored in database
 * @since 0.1
 */
delete_option( 'pixopoint-theme-integrator' );


// Goodbye, I wonder why they uninstalled it?


?>
