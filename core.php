<?php
/*

	This file loads the various functions used in the plugin

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/


/**
 * Loading the buffers
 * @since 0.2
 */
function pixopoint_loadbuffers() {
	// Check if we're supposed to be loading this right now
	if ( pixopoint_precheck() == 'kill' )
		return;
	add_action( 'template_redirect', 'pixopoint_start_buffer', 100 ); // Adding start buffer to before the template loading
	add_action( 'wp_footer', 'pixopoint_end_buffer' ); // Adding end buffer to hook in footer
}
add_action(	'template_redirect',	'pixopoint_loadbuffers', 10 );


/**
 * The function which loads the HTML onto the page
 * @since 0.1
 */
function pixopoint_theme_integrator( $type, $key ) {
	$pixopoint_theme_integrator_options = get_option( 'pixopoint_theme_integrator_options' );
	// Check if we're supposed to be loading this right now
	if ( pixopoint_precheck() == 'kill' )
		return;
	// Check that inputs are correct
	if ( !is_numeric( $key ) )
		return;
	if ( $type == 'start' )
		echo '<!-- Theme integrator ' . $type . ' chunk #' . $key . ' and heres the end of the integrator text -->';
	elseif ( $type == 'end' )
		echo THEME_INTEGRATION_ENDCHUNK;
	else
		return;
}

/**
 * Check if time is up and resetting it if it is
 * @since 0.1
 */
function pixopoint_precheck() {
	$pixopoint_theme_integrator_options = get_option( 'pixopoint_theme_integrator_options' );
	// Check we're on the correct page/post (if one is set)
	if ( $pixopoint_theme_integrator_options['id'] != '' ) {
		if ( is_page( $pixopoint_theme_integrator_options['id'] ) ) {}
		elseif ( is_single( $pixopoint_theme_integrator_options['id'] ) ) {}
		else
			return 'kill';
	}
	// If sufficient time hasn't passed, then return as no need to update needlessly
	if ( $pixopoint_theme_integrator_options['time'] > ( time() - $pixopoint_theme_integrator_options['frequency'] ) )
			return 'kill';
}



/**
 * Start Buffer
 * @since 0.1
 */
function pixopoint_start_buffer() {
	ob_start(); // Starts output buffering
}


/**
 * Process buffer
 * @since 0.1
 */
function pixopoint_end_buffer() {
	$pixopoint_theme_integrator_options = get_option( 'pixopoint_theme_integrator_options' );
	global $chunk;

	$end = ' and heres the end of the integrator text -->';

	$contents = ob_get_contents(); // Grabbing the data from the buffer
	ob_end_clean(); // Cleaning the buffer
	echo $contents; // Echo's the content on screen - since you just buffered it nothing has been displaying on screen till this point

	$contents = explode( '<!-- Theme integrator start chunk #', $contents );
	unset( $contents[0] );

	foreach ( $contents as $bla => $test ) {
		if ( strlen( strstr( $test, $end ) ) > 0 ) {
			$pos = stripos( $test, $end );
			$test = str_replace( $end, "" , $contents[$bla] );
			$contents[$bla] = substr( $test, $pos );

			foreach ( $pixopoint_theme_integrator_options as $stuff => $temp ) {
				// If doesn't start with 'remove_' then moves on
				if ( substr( $stuff, 0, 7 ) != 'remove_' )
					continue;
				// Checking the numbers are actually numeric
				if ( !is_numeric( substr( $stuff, 7 ) ) )
					continue;
				$contents[$bla] = str_replace( $temp, "" , $contents[$bla] );
			}
			// Removing the unwanted end of the chunk
			$test = explode ( THEME_INTEGRATION_ENDCHUNK, $contents[$bla] );
			$contents[$bla] = $test[0];
			// Storing the files in the WP uploads folder
			$uploads_folder = wp_upload_dir();
			$upload_location = $uploads_folder['basedir'] . '/' . THEME_INTEGRATION_FOLDER;
			if ( !is_dir( $upload_location ) )
				mkdir( $upload_location, 0755 );
			file_put_contents( $upload_location . '/' . THEME_INTEGRATION_CHUNKNAME . $bla . '.html', $contents[$bla] ); // Write the contents back to the file
		}
	}
	$pixopoint_theme_integrator_options['no_of_chunks'] = $bla; // Recording the number of chunks made (used in admin page to provide require statements)
	$pixopoint_theme_integrator_options['time'] = time(); // Storing the current time;
	update_option( 'pixopoint_theme_integrator_options', $pixopoint_theme_integrator_options ); // Updating the options
}


