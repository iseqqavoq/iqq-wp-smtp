<?php

/*
Plugin Name: IQQ WP SMTP
Version: 0.1.1
Description: Reconfigures the wp_mail() function to use desired SMTP instead of mail() and creates an options page to manage the settings.
Author: IQQ
Author URI: http://www.iqq.se
Domain Path: /languages
Text Domain: iqq
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class IQQ_SMTP {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_options_page' ) );
		add_action( 'network_admin_menu', array( __CLASS__, 'add_options_page' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__, 'add_settings_page_link' ) );
		add_filter( 'network_admin_plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__, 'add_settings_page_link' ) );
		add_action( 'admin_init', array( __CLASS__, 'add_options' ) );
		add_action( 'admin_post_update_iqq_smtp_settings', array( __CLASS__, 'update_network_settings' ) );
		add_action( 'phpmailer_init', array( __CLASS__, 'configure_smtp' ) );
	}

	public static function add_options_page() {

		// If global variable set to not show gui.
		if ( defined( 'IQQ_SMTP_GUI' ) && ! IQQ_SMTP_GUI ) {
			return;
		}

		$is_network_activated = self::is_network_activated();

		add_submenu_page(
			$parent_slug = $is_network_activated ? 'settings.php' : 'options-general.php',
			$page_title = __( 'SMTP Settings', 'iqq' ),
			$menu_title = __( 'SMTP', 'iqq' ),
			$capability = $is_network_activated ? 'manage_network_options' : 'activate_plugins',
			$menu_slug = 'smtp',
			$function = array( __CLASS__, 'options_page' )
		);
	}

	public static function options_page() {
		$is_network_activated  = self::is_network_activated();
		$settings_in_wp_config = self::settings_in_wp_config();

		if ( $is_network_activated ) {
			include( 'network-options-page.php' );
		} else {
			include( 'options-page.php' );
		}

	}

	function add_settings_page_link( $links ) {

		if( self::is_network_activated() ) {
			$settings_link = '<a href="' . network_admin_url( 'settings.php?page=smtp' ) . '">' . __( 'Settings' ) . '</a>';
		} else {
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=smtp' ) . '">' . __( 'Settings' ) . '</a>';
		}

		array_push( $links, $settings_link );

		return $links;
	}

	public static function add_options() {
		register_setting( 'iqq-smtp-settings-group', 'iqq-smtp-active' );
		register_setting( 'iqq-smtp-settings-group', 'iqq-smtp-host' );
		register_setting( 'iqq-smtp-settings-group', 'iqq-smtp-port' );
		register_setting( 'iqq-smtp-settings-group', 'iqq-smtp-username' );
		register_setting( 'iqq-smtp-settings-group', 'iqq-smtp-password' );
		register_setting( 'iqq-smtp-settings-group', 'iqq-smtp-sender' );
		register_setting( 'iqq-smtp-settings-group', 'iqq-smtp-sendermail' );
	}

	public static function update_network_settings() {
		check_admin_referer( 'iqq_smtp_nonce' );

		if ( ! current_user_can( 'manage_network_options' ) ) {
			wp_die( "You dont't have permissions to save these options", 'iqq' );
		}

		// Save options
		update_site_option( 'iqq-smtp-active', $_POST['iqq-smtp-active'] );
		update_site_option( 'iqq-smtp-host', $_POST['iqq-smtp-host'] );
		update_site_option( 'iqq-smtp-port', $_POST['iqq-smtp-port'] );
		update_site_option( 'iqq-smtp-username', $_POST['iqq-smtp-username'] );
		update_site_option( 'iqq-smtp-password', $_POST['iqq-smtp-password'] );
		update_site_option( 'iqq-smtp-sender', $_POST['iqq-smtp-sender'] );
		update_site_option( 'iqq-smtp-sendermail', $_POST['iqq-smtp-sendermail'] );

		wp_redirect( admin_url( 'network/settings.php?page=smtp' ) );
		exit;
	}

	public static function is_network_activated() {

		$plugin_file = plugin_basename( __FILE__ );

		return ( array_key_exists( $plugin_file, maybe_unserialize( get_site_option( 'active_sitewide_plugins' ) ) ) );
	}

	public static function settings_in_wp_config() {

		$globals = self::get_available_globals();

		$settings = array_filter( $globals, function ( $g ) {
			return defined( $g );
		} );

		if ( sizeof( $settings ) > 0 ) {
			return $settings;
		}

		return false;
	}

	public static function get_available_globals() {
		return array(
			'IQQ_SMTP_ACTIVE',
			'IQQ_SMTP_HOST',
			'IQQ_SMTP_PORT',
			'IQQ_SMTP_USERNAME',
			'IQQ_SMTP_PASSWORD',
			'IQQ_SMTP_SENDER',
			'IQQ_SMTP_SENDERMAIL',
			'IQQ_SMTP_GUI'
		);
	}

	public static function configure_smtp( $phpmailer ) {

		$is_network_activated = self::is_network_activated();

		if ( self::settings_in_wp_config() && defined( 'IQQ_SMTP_ACTIVE' ) && IQQ_SMTP_ACTIVE ) {
			$phpmailer->isSMTP();
			$phpmailer->SMTPAuth = true;
			$phpmailer->Host     = defined( 'IQQ_SMTP_HOST' ) ? IQQ_SMTP_HOST : '';
			$phpmailer->Port     = defined( 'IQQ_SMTP_PORT' ) ? IQQ_SMTP_PORT : '';
			$phpmailer->Username = defined( 'IQQ_SMTP_USERNAME' ) ? IQQ_SMTP_USERNAME : '';
			$phpmailer->Password = defined( 'IQQ_SMTP_PASSWORD' ) ? IQQ_SMTP_PASSWORD : '';

			if ( defined( 'IQQ_SMTP_SENDER' ) ) {
				$phpmailer->FromName = IQQ_SMTP_SENDER;
			}

			if ( defined( 'IQQ_SMTP_SENDERMAIL' ) ) {
				$phpmailer->From = IQQ_SMTP_SENDERMAIL;
			}

		} elseif ( $is_network_activated && get_site_option( 'iqq-smtp-active' ) ) {
			$phpmailer->isSMTP();
			$phpmailer->SMTPAuth = true;
			$phpmailer->Host     = get_site_option( 'iqq-smtp-host' );
			$phpmailer->Port     = get_site_option( 'iqq-smtp-port' );
			$phpmailer->Username = get_site_option( 'iqq-smtp-username' );
			$phpmailer->Password = get_site_option( 'iqq-smtp-password' );

			if ( get_site_option( 'iqq-smtp-sender' ) ) {
				$phpmailer->FromName = get_site_option( 'iqq-smtp-sender' );
			}

			if ( get_site_option( 'iqq-smtp-sendermail' ) ) {
				$phpmailer->From = get_site_option( 'iqq-smtp-sendermail' );
			}

		} elseif ( get_option( 'iqq-smtp-active' ) ) {
			$phpmailer->isSMTP();
			$phpmailer->SMTPAuth = true;
			$phpmailer->Host     = get_option( 'iqq-smtp-host' );
			$phpmailer->Port     = get_option( 'iqq-smtp-port' );
			$phpmailer->Username = get_option( 'iqq-smtp-username' );
			$phpmailer->Password = get_option( 'iqq-smtp-password' );

			if ( get_option( 'iqq-smtp-sender' ) ) {
				$phpmailer->FromName = get_option( 'iqq-smtp-sender' );
			}

			if ( get_option( 'iqq-smtp-sendermail' ) ) {
				$phpmailer->From = get_option( 'iqq-smtp-sendermail' );
			}
		}
	}
}

IQQ_SMTP::init();