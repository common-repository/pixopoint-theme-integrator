<?php
/*
	Registers settings, adds a new submenu in the admin panel and adds options to array
	@since 0.1

	PixoPoint Theme Integrator
	Copyright (c) 2009 Ryan Hellyer

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/


/**
 * Do not continue processing since file was called directly
 * @since 0.1.3
 */
if ( !defined( 'ABSPATH' ) )
	return;


/**
 * Adds admin menu, stores array and registers settings
 * @since 0.1.3
 */
add_action( 'admin_menu', 'show_pixopoint_theme_integrator_options' );
function show_pixopoint_theme_integrator_options() {

	// Add various options for admin page
	$page = add_options_page(
		'PixoPoint Theme Integrator',
		'PixoPoint Theme Integrator',
		'administrator',
		'pixopoint_themeintegrator_options',
		'pixopoint_theme_integrator_options'
	);
	add_action( "admin_print_scripts-$page", 'pixopoint_themeintegrator_adminhead' );	// Adds various default options into an array
	$pixopoint_setmenuoptions = array(
		'frequency' => '5000',
		'remove_1'  => 'Some text to be removed',
		'remove_1'  => 'Some more text to be removed',
	);
	// Add default options to an option in the database if it doesn't already exists
	add_option( 'pixopoint_theme_integrator_options', $pixopoint_setmenuoptions );

	// Register Settings
	register_setting( 'pixopoint-theme-integrator', 'pixopoint_theme_integrator_options', 'pixopoint_themeintegratorvalidate' );
}


/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 * @since 0.1.3
 */
function pixopoint_themeintegratorvalidate($input) {
	// Checking if numeric
	if ( !is_numeric( $input['frequency'] ) )
		$input['frequency'] = '500';
	if ( !is_numeric( $input['id'] ) )
		$input['id'] = '';
	// Run through each entry and strip all HTML
	// Damnit, sanitisation is hard since we pretty much need to let any old thing through since HTML can be, well, pretty much any sort of text string :(
	foreach ( $input as $set => $value ) {
		// $input[$set] =  wp_filter_kses( $input[$set] ); // Can't santisie it this way :(
//		if ( $input[$set] == '' )
//			unset( $input[$set] );
	}
	return $input;
}


/**
 * Stuff for between the head tags
 * @since 0.1.3
 */
function pixopoint_themeintegrator_adminhead() { ?>
<style type="text/css">
	#icon-pixopoint-theme-integrator {
		background:url(<?php echo THEME_INTEGRATION_IMAGES_URL; ?>h2_icon.png) no-repeat;
	}
	textarea {
		width:100%;
	}
</style>
<?php }


/**
 * Loads the admin page
 * @since 0.1.3
 */
function pixopoint_theme_integrator_options() {
	$pixopoint_theme_integrator_options = get_option( 'pixopoint_theme_integrator_options' );
	?>
<div class="wrap">
	<form method="post" action="options.php" id="options">
		<?php wp_nonce_field( 'update-options' ); ?>
		<?php settings_fields( 'pixopoint-theme-integrator' ); ?>
		<?php screen_icon( 'pixopoint-theme-integrator' ); ?>
		<h2><?php _e( 'PixoPoint Theme Integrator', 'pixopoint_theme_integrator_lang' ); ?></h2>

		<h3><?php _e( 'Update frequency', 'pixopoint_theme_integrator_lang' ); ?></h3>
		<p><?php _e( 'Enter the frequency (in seconds) here that you want the integrator to refresh it\'s HTML cache.', 'pixopoint_theme_integrator_lang' ); ?></p>
		<label><?php _e( 'Frequency', 'pixopoint_theme_integrator_lang' ); ?></label>
		<input name="pixopoint_theme_integrator_options[frequency]" type="text" value="<?php
			if ( is_numeric( $pixopoint_theme_integrator_options['frequency'] ) )
				echo $pixopoint_theme_integrator_options['frequency'];
		?>" />
		<h3><?php _e( 'Only update on specific post or page', 'pixopoint_theme_integrator_lang' ); ?></h3>
		<p>
			<?php _e( 'For various reasons, you may want to have the integrator fire on a specific page. This is often a good idea if you have different scripts loading on each page and don\'t want to have to remove them all via the admin panel. If this option is left blank, it will operate on <strong>all</strong> pages.', 'pixopoint_theme_integrator_lang' ); ?>
		</p>
		<label><?php _e( 'Enter a WordPress Page or Post ID here', 'pixopoint_theme_integrator_lang' ); ?></label>
		<input name="pixopoint_theme_integrator_options[id]" type="text" value="<?php
			if ( is_numeric( $pixopoint_theme_integrator_options['id'] ) )
				echo $pixopoint_theme_integrator_options['id'];
		?>" />

		<h3><?php _e( 'Chunks of text to remove', 'pixopoint_theme_integrator_lang' ); ?></h3>
		<p><?php _e( 'It is likely that there will be pieces of text in your theme which are unneeded in the software you are integrating with, you can enter those here and they will be automatically removed for you.', 'pixopoint_theme_integrator_lang' ); ?></p>
		<?php

		foreach ( $pixopoint_theme_integrator_options as $bla => $test ) {
			// If doesn't start with 'remove_' then moves on
			if ( substr( $bla, 0, 7 ) != 'remove_' )
				continue;
			// Checking the numbers are actually numeric
			if ( !is_numeric( substr( $bla, 7 ) ) )
				continue;
			// Echo'ing HTML on page
			echo '<textarea name="pixopoint_theme_integrator_options[remove_' . substr( $bla, 7 ) . ']">' . $test . '</textarea>';
			$lastnumber = substr( $bla, 7 );
		}
		echo '<textarea name="pixopoint_theme_integrator_options[remove_' . ( $lastnumber + 1 ) . ']"></textarea>';

		?>

		<h3><?php _e( 'Location of HTML', 'pixopoint_theme_integrator_lang' ); ?></h3>
		<p>
			<?php _e( 'Your HTML is available in the following folder.', 'pixopoint_theme_integrator_lang' ); ?>
			<br /><br />
			<strong>
				<?php
					$uploads_folder = wp_upload_dir();
					echo $uploads_folder['basedir'] . '/' . THEME_INTEGRATION_FOLDER;
				?>
			</strong>
		</p>
		<p>
			<?php _e( 'You can include this HTML into your other systems/software however you like, but the most common approach is to use a require statement within the theme of the other software, as follows:', 'pixopoint_theme_integrator_lang' ); ?>
		</p>
		<?php
			$counter = 1;
			while ( $counter <= $pixopoint_theme_integrator_options['no_of_chunks'] ) {
				echo "<code>&lt;?php require( '" . $uploads_folder['basedir'] . '/' . THEME_INTEGRATION_FOLDER . '/' . THEME_INTEGRATION_CHUNKNAME . $counter . ".html' ); ?&gt;</code><br /><br />";
				$counter++;
			}
		?>

		<h3><?php _e( 'Installation and Support', 'pixopoint_theme_integrator_lang' ); ?></h3>
		<p>
			<?php _e( 'Full installation instructions are available on the <a href="http://pixopoint.com/products/pixopoint-theme-integrator/">PixoPoint Theme Integrator page.</a>', 'pixopoint_theme_integrator_lang' ); ?>
		</p>
		<p>
			<?php _e( 'We do not offer support for this plugin, but welcome bug reports in our <a href="http://pixopoint.com/forum/">support forum</a>. Paid support is available as part of the <a href="http://pixopoint.com/services/premium-support/">PixoPoint Premium Support service</a>.', 'pixopoint_theme_integrator_lang' ); ?>
		</p>
		<input type="hidden" name="pixopoint_theme_integrator_options[no_of_chunks]" value="<?php echo $pixopoint_theme_integrator_options['no_of_chunks']; ?>" />
		<input type="hidden" name="action" value="update" />
		<div style="clear:both;padding-top:20px;"></div>
		<p class="submit"><input type="submit" name="Submit" value="<?php _e( 'Update Options' ) ?>" /></p>

		<div style="clear:both;padding-top:20px;"></div>
	</form>
</div>


<?php
}


