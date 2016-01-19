=== IQQ WP SMTP ===
Contributors: iseqqavoq
Tags: smtp,mail
Requires at least: 4.2
Tested up to: 4.4
Stable tag: 4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
WordPress plugin for sending all mails from your WordPress via a SMTP. Works for both network and single site installations.
Reconfigures the wp_mail() function to use desired SMTP instead of mail() and creates an options page to manage the SMTP settings.

Features:
*Allows you to set host, port, username and password for smtp.
*Allows you to specify sender name and email.
*Allows you to setup variables above from wp-config.

== Installation ==
1. Download IQQ WP SMTP.
2. Upload the ’iqq-wp-smtp’ directory to your ’/wp-content/plugins/’ directory, using your favorite method (ftp, sftp, scp, etc…)
3. Activate IQQ WP SMTP from your Plugins page.

== Setup from wp-admin ==
If plugin is activated across network on a network site all mails in the site will be sent with the smtp settings provided.
1. Go to ’SMTP’ in the settings menu. If network activated, go to the settings menu in the network admin.
2. Add desired settings to available fields. Don’t forget to check ’Active’.
3. Press ’Save’.

== Setup from wp-config ==
Setting up available variables from wp-config will take precedence over variables set from the GUI. Also, if variables are set in wp-config, you will not be able to set variables from  GUI.
Example:
Put this in in your wp-config.php to make settings global and to take precedence over settings made from GUI.
`
define(’IQQ_SMTP_ACTIVE’, true); // set to true or false depending on if you want to send mails with provided smtp settings.
define(’IQQ_SMTP_HOST’, ’smtp.foo.com’); //  set to smtp host
define(’IQQ_SMTP_PORT’, 123); // set to smtp port.
define(’IQQ_SMTP_USERNAME’, ’foo@bar.com’); // set to smtp username.
define(’IQQ_SMTP_PASSWORD’, ’xxxxxxxxx’); // set to smtp password.
define(’IQQ_SMTP_SENDER’, ’Name of sender’); // set to name from which you wish to send emails.
define(’IQQ_SMTP_SENDERMAIL’, ’foo@bar.com’); // set to email from which you wish to send emails.
define(’IQQ_SMTP_GUI’, false); // set to false to hide admin interface of plugin.
`