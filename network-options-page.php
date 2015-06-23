<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wrap">

	<?php $disabled = '' ?>

	<h2><?php _e( 'SMTP Settings', 'iqq' ) ?></h2>

	<?php if ( $settings_in_wp_config ) : ?>
		<div class="error">
			<?php printf( __( 'Settings found in wp-config.php: %s%sIf you wish to use settings from this admin page, remove the listed settings from wp-config.php', 'iqq' ), '<i>' . implode( ', ', $settings_in_wp_config ) . '</i>', '<br>' ) ?>
			<?php $disabled = 'disabled' ?>
		</div>
	<?php endif ?>

	<div class="updated">
		<p>
			<?php printf( __( 'Set desired SMTP settings here. If you preffer to set them globally in your code, you can put them in wp-config. The following globals are available for use, %s', 'iqq' ), '<i>' . implode( ', ', self::get_available_globals() ) . '</i>' ) ?>
		</p>
	</div>

	<form method="post" action="<?php echo admin_url( 'admin-post.php?action=update_iqq_smtp_settings' ); ?>">

		<?php wp_nonce_field( 'iqq_smtp_nonce' ) ?>

		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="iqq-smtp-active"><?php _e( 'Use Settings', 'iqq' ) ?></label></th>
				<td><input type="checkbox" name="iqq-smtp-active" id="iqq-smtp-active"
						<?php checked( get_site_option( 'iqq-smtp-active' ) ) ?>
						   value="1" <?php echo $disabled ?>/></td>
			</tr>
			<tr>
				<th scope="row"><label for="iqq-smtp-host"><?php _e( 'Host', 'iqq' ) ?></label></th>
				<td><input type="text" name="iqq-smtp-host" id="iqq-smtp-host"
				           value="<?php echo esc_attr( get_site_option( 'iqq-smtp-host' ) ); ?>"
				           required <?php echo $disabled ?>/></td>
			</tr>
			<tr>
				<th scope="row"><label for="iqq-smtp-port"><?php _e( 'Port', 'iqq' ) ?></label></th>
				<td><input type="number" name="iqq-smtp-port" id="iqq-smtp-port"
				           value="<?php echo esc_attr( get_site_option( 'iqq-smtp-port' ) ); ?>"
				           required <?php echo $disabled ?>/></td>
			</tr>
			<tr>
				<th scope="row"><label for="iqq-smtp-username"><?php _e( 'SMTP Username', 'iqq' ) ?></label></th>
				<td><input type="text" name="iqq-smtp-username" id="iqq-smtp-username"
				           value="<?php echo esc_attr( get_site_option( 'iqq-smtp-username' ) ); ?>"
				           required <?php echo $disabled ?>/></td>
			</tr>
			<tr>
				<th scope="row"><label for="iqq-smtp-password"><?php _e( 'SMTP Password', 'iqq' ) ?></label></th>
				<td><input type="password" name="iqq-smtp-password" id="iqq-smtp-password"
				           value="<?php echo esc_attr( get_site_option( 'iqq-smtp-password' ) ); ?>"
				           required <?php echo $disabled ?>/></td>
			</tr>

			</tbody>
		</table>

		<hr>

		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="iqq-smtp-sender"><?php _e( 'Send mail from name', 'iqq' ) ?></label></th>
				<td><input type="text" name="iqq-smtp-sender" id="iqq-smtp-sender"
				           value="<?php echo esc_attr( get_site_option( 'iqq-smtp-sender' ) ); ?>"
				           required <?php echo $disabled ?>/></td>
			</tr>
			<tr>
				<th scope="row"><label for="iqq-smtp-sendermail"><?php _e( 'Send mail from e-mail', 'iqq' ) ?></label>
				</th>
				<td><input type="text" name="iqq-smtp-sendermail" id="iqq-smtp-sendermail"
				           value="<?php echo esc_attr( get_site_option( 'iqq-smtp-sendermail' ) ); ?>"
				           required <?php echo $disabled ?>/></td>
			</tr>
			</tbody>
		</table>

		<?php submit_button( null, 'primary', 'submit', true, $disabled ) ?>

	</form>

</div>
