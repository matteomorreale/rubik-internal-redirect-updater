<?php
/*
Plugin Name: Rubik Internal Redirect Updater
Description: Sostituisce i link interni che puntano a redirect 301 con il loro target finale.
Version: 1.2
Author: Matteo Morreale
*/

// Blocca accesso diretto
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Definizione del prefisso globale di WP
global $wpdb;
$table_prefix = $wpdb->prefix;

// Definizione della costante del nome del plugin per utilizzarlo all'interno del codice
define('RUBIK_PLUGIN_VERSION', '1.2');

// Carichiamo l'option page e la logica di scansione AJAX
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax-handler.php';

// Funzione di attivazione del plugin
function rubik_activate_plugin() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rubik_processed_links';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        link_id bigint(20) UNSIGNED NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'rubik_activate_plugin');

// Funzione di disattivazione del plugin
function rubik_deactivate_plugin() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rubik_processed_links';

    // Cancella la tabella quando il plugin viene disattivato
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
register_deactivation_hook(__FILE__, 'rubik_deactivate_plugin');