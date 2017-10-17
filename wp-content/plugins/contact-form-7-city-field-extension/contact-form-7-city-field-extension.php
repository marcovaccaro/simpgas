<?php

/*
Plugin Name: Contact Form 7 - City Field Extension
Plugin URI: http://cfe.wp-themes.it
Description: Provides a text input field for city search, based on Google Place Autocomplete library.  Requires Contact Form 7.
Version: 1.3
Author: Pasquale Bucci
Author URI: http://wp-themes.it/
License: GPL2
*/

/*  Copyright 2014 - 2017 Pasquale Bucci (email : paky.bucci@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Option module
 */
add_action( 'admin_menu', 'cfe_add_admin_menu' );
add_action( 'admin_init', 'cfe_settings_init' );

function cfe_add_admin_menu() { 
	add_menu_page( 'City Field Extension', 'City Field Extension', 'manage_options', 'city_field_extension', 'cfe_options_page' );
}

function cfe_settings_init() { 

	register_setting( 'pluginPage', 'cfe_settings' );

	add_settings_section(
		'cfe_pluginPage_section', 
		'', 
		'', 
		'pluginPage'
	);

	add_settings_field( 
		'cfe_text_field_0', 
		'Your API Key', 
		'cfe_text_field_0_render', 
		'pluginPage', 
		'cfe_pluginPage_section' 
	);

}

function cfe_text_field_0_render() { 

	$options = get_option( 'cfe_settings' );
	$opt1='';
	if (isset($options['cfe_text_field_0'])) {
		$opt1=$options['cfe_text_field_0'];
	}
	?>
	<input type='text' name='cfe_settings[cfe_text_field_0]' value='<?php echo $opt1; ?>'>
	<p><small>Insert your Google Maps API Key. <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">Get your KEY!</a></small></p>
	<?php

}

function cfe_options_page(  ) { 
	?>
	<form action='options.php' method='post'>
		
		<h2>City Field Extension - Settings</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php
}

/*
* Loads Scripts
*/
function pcfe_load_scripts() {
	$options = get_option( 'cfe_settings' );
	$key='';
	if (isset($options['cfe_text_field_0'])) {
		$key=$options['cfe_text_field_0'];
	}
	wp_enqueue_script( 'pcfe-google-places-api', 'https://maps.googleapis.com/maps/api/js?key=' .$key . '&libraries=places' );
	wp_enqueue_script( 'pcfe-plugin-script', plugins_url( '/js/script.js', __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'pcfe_load_scripts' );

function load_pcfe_wp_admin_style() {
    wp_enqueue_style( 'pcfe-plugin-style', plugins_url( '/css/pakystyle.css', __FILE__ ));
}
add_action( 'admin_enqueue_scripts', 'load_pcfe_wp_admin_style' );

/*
* A base module for [cityfieldtext], [cityfieldtext*]
*/
function wpcf7_cityfieldtext_init(){

	add_action( 'wpcf7_init', 'wpcf7cfe_add_shortcode_cityfield' );
	add_filter( 'wpcf7_validate_cityfield*', 'wpcf7cfe_cityfield_validation_filter', 10, 2 );
	
}
add_action( 'plugins_loaded', 'wpcf7_cityfieldtext_init' , 20 );

function wpcf7cfe_add_shortcode_cityfield() {
	wpcf7_add_form_tag(
		array( 'cityfieldtext' , 'cityfieldtext*' ),
		'wpcf7_cityfield_shortcode_handler', true );
}

/*
* CityFieldText Shortcode
*/
function wpcf7_cityfield_shortcode_handler( $tag ) {

	$tag = new WPCF7_FormTag( $tag );

	if ( empty( $tag->name ) )
		return '';

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type, 'wpcf7cfe-cityfield' );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
    }

    $atts = array();

	$atts['size'] = $tag->get_size_option( '40' );
	$atts['maxlength'] = $tag->get_maxlength_option();
	$atts['minlength'] = $tag->get_minlength_option();

	if ( $atts['maxlength'] && $atts['minlength'] && $atts['maxlength'] < $atts['minlength'] ) {
		unset( $atts['maxlength'], $atts['minlength'] );
	}

	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = 'autocomplete';
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

	if ( $tag->has_option( 'readonly' ) )
		$atts['readonly'] = 'readonly';

	if ( $tag->is_required() )
		$atts['aria-required'] = 'true';

	$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

	$value = (string) reset( $tag->values );

    $atts['type'] = 'text';
			
	$atts['name'] = $tag->name;

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf(
		'<span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span>',
		sanitize_html_class( $tag->name ), $atts, $validation_error );

	return $html;

}

/*
* CityFieldText Validation filter
*/
function wpcf7cfe_cityfield_validation_filter( $result, $tag ) {

	$tag = new WPCF7_FormTag( $tag );

	$name = $tag->name;

	$value = isset( $_POST[$name] )
		? trim( wp_unslash( strtr( (string) $_POST[$name], "\n", " " ) ) )
		: '';


	if ( 'cityfieldtext' == $tag->basetype ) {
		if ( $tag->is_required() && '' == $value ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		}
	}

	if ( ! empty( $value ) ) {
		$maxlength = $tag->get_maxlength_option();
		$minlength = $tag->get_minlength_option();

		if ( $maxlength && $minlength && $maxlength < $minlength ) {
			$maxlength = $minlength = null;
		}

		$code_units = wpcf7_count_code_units( $value );

		if ( false !== $code_units ) {
			if ( $maxlength && $maxlength < $code_units ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_too_long' ) );
			} elseif ( $minlength && $code_units < $minlength ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_too_short' ) );
			}
		}
	}

	return $result;
}


/*
* CityFieldText Tag generator
*/
if ( is_admin() ) {
	//add_action( 'admin_init', 'wpcf7cfe_add_tag_generator_cityfield', 25 );
	add_action( 'wpcf7_admin_init' , 'wpcf7_add_tag_generator_cityfield' , 100 );
}

function wpcf7_add_tag_generator_cityfield() {
	if ( ! class_exists( 'WPCF7_TagGenerator' ) ) return;

	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'cityfieldtext', __( 'City Field Extension', 'contact-form-7' ),
		'wpcf7cfe_tag_generator_cityfield' );
}

function wpcf7cfe_tag_generator_cityfield( $contact_form , $args = '' ){
	$args = wp_parse_args( $args, array() );
	$type = $args['id'];

	$description = __( "Generate a form tag for an autocomplete text field that returns place predictions in the form of a dropdown pick list.", 'contact-form-7' );
		


?>
<div class="control-box">
<fieldset>
<legend><?php echo $description; ?></legend>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
		<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
		</fieldset>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>


	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
	</tr>

</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />

</div>
<?php
}


/*
* CityFieldText Welcome panel
*/
add_action( 'wpcf7_admin_notices', 'wpcf17_welcome_panel', 2 );
function wpcf17_welcome_panel() {
	global $plugin_page;

	if ( 'wpcf7' != $plugin_page || ! empty( $_GET['post'] ) ) {
		return;
	}

	$classes = 'welcome-panel';

	$vers = (array) get_user_meta( get_current_user_id(),
		'wpcf7_hide_welcome_panel_on', true );

	if ( wpcf7_version_grep( wpcf7_version( 'only_major=1' ), $vers ) ) {
		$classes .= ' hidden';
	}

?>
<div id="welcome-panel" class="<?php echo esc_attr( $classes ); ?>">
	<?php wp_nonce_field( 'wpcf7-welcome-panel-nonce', 'welcomepanelnonce', false ); ?>
	<a class="welcome-panel-close" href="<?php echo esc_url( menu_page_url( 'wpcf7', false ) ); ?>"><?php echo esc_html( __( 'Dismiss', 'contact-form-7' ) ); ?></a>

	<div class="welcome-panel-content">
		<div class="welcome-panel-container">
			<div class="">
				<h4><?php echo esc_html( __( 'City Field Extension for Contact Form 7 - Want more?', 'contact-form-7' ) ); ?></h4>
				<p class="message"><?php echo esc_html( __( "If you want more fields and/or restrict country and/or get the fields as address, cities and more, consider PREMIUM version.", 'contact-form-7' ) ); ?></p>
				<p><a href="http://cfe.wp-themes.it/" class="button button-paky" target="_blank"><?php echo esc_html( __( 'Take a look at PREMIUM', 'contact-form-7' ) ); ?></a></p>
			</div>

		</div>
	</div>
</div>
<?php
}
