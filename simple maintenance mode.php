<?php
/*
Plugin Name: Simple Maintenance Mode
Plugin URI: https://github.com/YOUR_GITHUB_USERNAME/simple-maintenance-mode
Description: Adds a toggle for enabling maintenance mode with a front-end lockout screen.
Version: 1.0
Author: Manjunath
Author URI: https://yourwebsite.com
License: GPL2
*/

defined('ABSPATH') || exit;

// Show maintenance page to non-logged-in users
function smm_maintenance_mode() {
    if (!is_user_logged_in() && !is_admin() && get_option('smm_enabled')) {
        wp_die(
            '<h1 style="text-align:center;margin-top:20%;">🚧 Site Under Maintenance 🚧</h1><p style="text-align:center;">We\'ll be back shortly.</p>',
            'Maintenance Mode',
            ['response' => 503]
        );
    }
}
add_action('template_redirect', 'smm_maintenance_mode');

// Add settings to admin
function smm_register_settings() {
    add_option('smm_enabled', false);
    register_setting('smm_options_group', 'smm_enabled', ['type' => 'boolean']);
}
add_action('admin_init', 'smm_register_settings');

function smm_settings_page() {
    ?>
    <div class="wrap">
        <h1>Simple Maintenance Mode</h1>
        <form method="post" action="options.php">
            <?php settings_fields('smm_options_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Maintenance Mode</th>
                    <td><input type="checkbox" name="smm_enabled" value="1" <?php checked(1, get_option('smm_enabled'), true); ?> /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function smm_menu() {
    add_options_page('Maintenance Mode', 'Maintenance Mode', 'manage_options', 'smm-settings', 'smm_settings_page');
}
add_action('admin_menu', 'smm_menu');
