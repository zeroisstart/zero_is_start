<?php
/**
 * Patchwork Theme Options
 *
 * @package Patchwork
 * @since Patchwork 1.0
 */

/**
 * Register the form setting for our patchwork_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, patchwork_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 *
 * @since Patchwork 1.0
 */
 
function patchwork_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === patchwork_get_theme_options() )
		add_option( 'patchwork_theme_options', patchwork_get_default_theme_options() );

	register_setting(
		'patchwork_options',       // Options group, see settings_fields() call in patchwork_theme_options_render_page()
		'patchwork_theme_options', // Database option, see patchwork_get_theme_options()
		'patchwork_theme_options_validate' // The sanitization callback, see patchwork_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see patchwork_theme_options_add_page()
	);

	/* Register our individual settings fields */

	add_settings_field( 'patchwork_theme_style', __( 'Theme Style', 'patchwork' ), 'patchwork_settings_field_theme_style', 'theme_options', 'general' );
	
	add_settings_field( 'patchwork_custom_css', __( 'Custom CSS', 'vintage-camera' ), 'patchwork_settings_field_custom_css', 'theme_options', 'general' );
	
	add_settings_field(
		'patchwork_support', // Unique identifier for the field for this section
		__( 'Support Caroline Themes', 'vintage-camera' ), // Setting field label
		'patchwork_settings_field_support', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see _s_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

}
add_action( 'admin_init', 'patchwork_theme_options_init' );

/**
 * Change the capability required to save the 'patchwork_options' options group.
 *
 * @see patchwork_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see patchwork_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function patchwork_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_patchwork_options', 'patchwork_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *

 */
function patchwork_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'patchwork' ),   // Name of page
		__( 'Theme Options', 'patchwork' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'patchwork_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;
}
add_action( 'admin_menu', 'patchwork_theme_options_add_page' );

/**
 * Returns an array of sample radio options registered for _s.
 *

 */
function patchwork_theme_style() {
	$patchwork_theme_style = array(
		'maude' => array(
			'value' => 'maude',
			'label' => __( 'Maude', 'patchwork' )
		),
		'babycakes' => array(
			'value' => 'babycakes',
			'label' => __( 'Babycakes', 'patchwork' )
		),
		'sunny' => array(
			'value' => 'sunny',
			'label' => __( 'Sunny', 'patchwork' )
		)
	);

	return apply_filters( 'patchwork_theme_style', $patchwork_theme_style );
}

/**
 * Returns the default options for Patchwork.
 *

 */
function patchwork_get_default_theme_options() {
	$default_theme_options = array(
		'patchwork_theme_style' => 'maude',
		'patchwork_custom_css' => '',
		'patchwork_camera_support' => 0
	);

	return apply_filters( 'patchwork_default_theme_options', $default_theme_options );
}

/**
 * Returns the options array for Patchwork.
 *

 */
function patchwork_get_theme_options() {
	return get_option( 'patchwork_theme_options', patchwork_get_default_theme_options() );
}

/**
 * Renders the Theme Style setting field.
 *

 */
function patchwork_settings_field_theme_style() {
	$options = patchwork_get_theme_options();

	foreach ( patchwork_theme_style() as $button ) {
	?>
	<div class="layout">
		<label class="description">
			<img src="<?php echo get_template_directory_uri() ?>/images/ss/<?php echo $button['value']; ?>.png" alt="<?php echo $button['label']; ?> Style" /><br />
			<input type="radio" name="patchwork_theme_options[theme_style]" value="<?php echo esc_attr( $button['value'] ); ?>" <?php checked( $options['theme_style'], $button['value'] ); ?> />
			<?php echo $button['label']; ?>
		</label>
	</div>
	<?php
	}
}


/**
 * Renders the Custom CSS setting field.
 *

 */
function patchwork_settings_field_custom_css() {
	$options = patchwork_get_theme_options();
	?>
	<textarea class="large-text" type="text" name="patchwork_theme_options[custom_css]" id="custom_css" cols="50" rows="10" /><?php echo esc_textarea( $options['custom_css'] ); ?></textarea>
	<label class="description" for="custom_css"><?php _e( 'Add any custom CSS rules here so they will persist through theme updates.', 'vintage-camera' ); ?></label>
	<?php
}

/**
 * Renders the Support setting field.
 */
function patchwork_settings_field_support() {
	$options = patchwork_get_theme_options();
	
	if ( $options['support'] !== 'on' || !isset( $options['support'] ) ) {

	?>
	<label for"vintage-camera-support">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="U34MBRZTKTX38">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" class="alignright">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
		<?php _e( 'If you enjoy my themes, please consider making a secure donation using the PayPal button to your right. Anything is appreciated!', 'vintage-camera' ); ?>
		
		<br /><input type="checkbox" name="patchwork_theme_options[support]" id="support" <?php checked( 'on', $options['support'] ); ?> />
		<label class="description" for="support">
			<?php _e( 'No, thank you! Dismiss this message.', 'vintage-camera' ); ?>
		</label>
	</label>
	<?php
	} 
	else { ?>
		<label class="description" for="support">
			<?php _e( 'Hide Donate Button', 'vintage-camera' ); ?>
		</label>
		<input type="checkbox" name="patchwork_theme_options[support]" id="support" <?php checked( 'on', $options['support'] ); ?> />
		
	</td>
		
	<?php
	}
	
}

/**
 * Returns the options array for Patchwork.
 *

 */
function patchwork_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'patchwork' ), get_current_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'patchwork_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see patchwork_theme_options_init()
 * @todo set up Reset Options action
 *

 */
function patchwork_theme_options_validate( $input ) {
	$output = $defaults = patchwork_get_default_theme_options();

	// The sample Theme Styles value must be in our array of Theme Styles values
	if ( isset( $input['theme_style'] ) && array_key_exists( $input['theme_style'], patchwork_theme_style() ) )
		$output['theme_style'] = $input['theme_style'];
	
	// The Support field should either be on or off
	if ( ! isset( $input['support'] ) )
		$input['support'] = 'off';
	$output['support'] = ( $input['support'] == 'on' ? 'on' : 'off' );
	
	// The Custom CSS must be safe text with the allowed tags for posts
	if ( isset( $input['custom_css'] ) )
		$output['custom_css'] = wp_filter_nohtml_kses($input['custom_css'] );

	return apply_filters( 'patchwork_theme_options_validate', $output, $input, $defaults );
}

/**
 * Theme Options Admin Styles
*/

function patchwork_theme_options_admin_styles() {
	echo "<style type='text/css'>";
	echo ".layout .description { width: 300px; float: left; text-align: center; margin-bottom: 10px; padding: 10px; }";
	echo "</style>";
}

add_action( 'admin_enqueue_scripts', 'patchwork_theme_options_admin_styles' );

?>