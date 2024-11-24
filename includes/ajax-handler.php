<?php

// Blocca accesso diretto
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('wp_ajax_rubik_process_next_link', 'rubik_process_next_link');

function rubik_process_next_link() {
    check_ajax_referer('rubik_ajax_nonce', '_ajax_nonce');

    global $wpdb;

    $link_table = $wpdb->prefix . 'rubik_link_data';
    $processed_table = $wpdb->prefix . 'rubik_processed_links';

    $link_data = $wpdb->get_row("SELECT * FROM $link_table WHERE id NOT IN (SELECT link_id FROM $processed_table) LIMIT 1");

    if ($link_data) {
        $link = $link_data->link;

        $site_url = get_site_url();
        $parsed_site_url = parse_url($site_url);
        $site_host = $parsed_site_url['host'];

        $parsed_link = parse_url($link);
        $link_host = $parsed_link['host'];

        if ($site_host === $link_host) {
            // Chiama la funzione per ottenere URL finale e codice HTTP
            list($final_url, $http_code) = rubik_get_final_redirect_target($link);

            if ($final_url) {
                $post_id = $link_data->post_id;
                $post_content = get_post_field('post_content', $post_id);

                if( $http_code == '200' && $link === $final_url){
                    $http_code = 'Same url';
                }
                else{
                    $updated_content = str_replace($link, $final_url, $post_content);
                    // Commentato: aggiornamento reale del post
                    
                    wp_update_post([
                        'ID' => $post_id,
                        'post_content' => $updated_content
                    ]);
                }


                $wpdb->insert($processed_table, [
                    'link_id' => $link_data->id
                ]);

                wp_send_json_success([
                    'progress' => rubik_calculate_progress($link_table, $processed_table),
                    'post_title' => get_the_title($post_id),
                    'post_url' => get_permalink($post_id),
                    'old_link' => $link,
                    'new_link' => $final_url, // Aggiungi il nuovo URL
                    'status' => $http_code == 200 ? 'OK' : 'Errore: ' . $http_code,
                    'http_code' => $http_code
                ]);
                
            } else {
                wp_send_json_error([
                    'message' => 'Il link non ha restituito un redirect valido.',
                    'post_title' => get_the_title($link_data->post_id),
                    'old_link' => $link,
                    'status' => 'Errore: loop di redirect, elaborazione interrotta'
                ]);
            }
        } else {
            $wpdb->insert($processed_table, [
                'link_id' => $link_data->id
            ]);

            wp_send_json_success([
                'progress' => rubik_calculate_progress($link_table, $processed_table),
                'post_title' => get_the_title($link_data->post_id),
                'old_link' => $link,
                'status' => 'Ignorato (link esterno)'
            ]);
        }
    } else {
        wp_send_json_success(['progress' => 100]);
    }
}

// Funzione per ottenere la destinazione finale di tutti i 301
function rubik_get_final_redirect_target($url) {
    $max_redirects = 50; // Limite massimo di redirezioni per evitare loop
    $redirect_count = 0;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    do {
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $new_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

        if ($http_code == 301 || $http_code == 302) {
            if ($new_url) {
                $url = $new_url;
            } else {
                break;
            }
        } else {
            break;
        }

        $redirect_count++;
    } while ($redirect_count < $max_redirects);

    curl_close($ch);

    // Ritorna un array con URL e codice HTTP
    return ($redirect_count < $max_redirects) ? [$url, $http_code] : [false, $http_code];
}

// Funzione per calcolare il progresso
function rubik_calculate_progress($link_table, $processed_table) {
    global $wpdb;
    $total_links = $wpdb->get_var("SELECT COUNT(*) FROM $link_table");
    $processed_links = $wpdb->get_var("SELECT COUNT(*) FROM $processed_table");
    return round(($processed_links / $total_links) * 100);
}