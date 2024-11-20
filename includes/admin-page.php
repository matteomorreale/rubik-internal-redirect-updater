<?php

// Blocca accesso diretto
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function rubik_register_admin_menu() {
    add_menu_page(
        'Rubik Redirect Updater',
        'Redirect Updater',
        'manage_options',
        'rubik-redirect-updater',
        'rubik_admin_page',
        'dashicons-admin-links',
        100
    );
}
add_action('admin_menu', 'rubik_register_admin_menu');

function rubik_admin_page() {
    $nonce = wp_create_nonce('rubik_ajax_nonce');
    ?>
    <style>
        td {
            max-width: 350px;
            word-break: break-all;
        }
    </style>
    <div class="wrap">
        <h1>Rubik Internal Redirect Updater</h1>
        <button id="rubik-start-scan" class="button button-primary">Avvia scansione e aggiornamento link</button>
        <div id="rubik-progress" style="margin-top: 20px;">
            <p>Progressione:</p>
            <progress id="rubik-progress-bar" value="0" max="100"></progress>
            <span id="rubik-progress-percent">0%</span>
        </div>
        <div id="rubik-log" style="margin-top: 20px;">
            <h3>Log delle modifiche:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Post Title</th>
                        <th>Old Link</th>
                        <th>New Link</th> <!-- Nuova colonna -->
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="rubik-log-content">
                </tbody>
            </table>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#rubik-start-scan').on('click', function() {
                // Chiama la funzione AJAX per elaborare i link uno a uno
                rubikProcessNextLink();
            });

            function rubikProcessNextLink() {
                $.post(ajaxurl, {
                    action: 'rubik_process_next_link',
                    _ajax_nonce: '<?php echo $nonce; ?>'
                }, function(response) {
                    if (response.success) {
                        var progress = response.data.progress;
                        $('#rubik-progress-bar').val(progress);
                        $('#rubik-progress-percent').text(progress + '%');

                        var logEntry = '<tr>' +
                            '<td>' + '<a href="' + response.data.post_url + '" target="_blank">' + response.data.post_title + '</a>' + '</td>' +
                            '<td>' + response.data.old_link + '</td>' +
                            '<td>' + (response.data.new_link || 'N/A') + '</td>' +
                            '<td>' + response.data.status + '</td>' +
                            '</tr>';
                        $('#rubik-log-content').append(logEntry);

                        if (progress < 100) {
                            rubikProcessNextLink();
                        }
                    } else {
                        var logEntry = '<tr>' +
                            '<td>' + (response.data.post_title || 'N/A') + '</td>' +
                            '<td>' + (response.data.old_link || 'N/A') + '</td>' +
                            '<td>' + 'N/A' + '</td>' +
                            '<td>' + response.data.status + '</td>' +
                            '</tr>';
                        $('#rubik-log-content').append(logEntry);
                    }
                }).fail(function() {
                    alert('Errore di comunicazione con il server.');
                });
            }

        });
    </script>
    <?php
}