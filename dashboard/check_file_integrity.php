<?php

/**
 * @author William Sergio Minozzi
 * @copyright 2021 - 2025
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly 

//debug4();

?>

<div id="antihacker-notifications-page">
    <div class="antihacker-block-title">
        <?php esc_attr_e('File Integrity Checker', 'antihacker'); ?>
    </div>
    <div id="notifications-tab">

        <?php


        global $antihacker_file_integrity_checker;


        require_once(ANTIHACKERPATH . '/includes/functions/function-site-integrity.php');

        // Garantir que a instância existe
        if (is_null($antihacker_file_integrity_checker) || !($antihacker_file_integrity_checker instanceof antihacker_File_Integrity_Checker)) {
            require_once plugin_dir_path(__FILE__) . '../antihacker.php'; // Ajuste o caminho
            $antihacker_file_integrity_checker = new antihacker_File_Integrity_Checker();
        }

        // Processar a verificação se o formulário foi enviado
        $results = null;
        $show_spinner = isset($_POST['run_integrity_check']) && check_admin_referer('run_integrity_check_nonce', 'run_integrity_check_nonce');
        if ($show_spinner && current_user_can('manage_options')) {
            set_time_limit(300); // Aumentar tempo de execução
            $results = $antihacker_file_integrity_checker->get_integrity_results();
            $show_spinner = false; // Esconder spinner após processar
        }

        ?>
        <div class="wrap" id="antihacker-theme-help-wrapper" style="opacity: 1;">

            <p><?php esc_html_e('Click the button below to verify the integrity of your WordPress core files.', 'file-integrity-checker'); ?></p>
            <form method="post" action="" id="integrity-check-form">
                <?php wp_nonce_field('run_integrity_check_nonce', 'run_integrity_check_nonce'); ?>
                <button type="submit" name="run_integrity_check" id="run-integrity-check" class="button button-primary">
                    <?php esc_html_e('Run Integrity Check', 'file-integrity-checker'); ?>
                </button>
            </form>
            <div id="antihacker_spinner" style="display: <?php echo $show_spinner ? 'block' : 'none'; ?>; float: left; margin-right: 10px;" class="spinner is-active"></div>
            <div id="integrity-results" style="margin-top: 20px;">
                <?php
                if (!$show_spinner && is_array($results)) {
                    if (empty($results)) {
                        echo '<p style="color: green;">' . esc_html__('All core files passed the integrity check. Everything seems to be OK!', 'file-integrity-checker') . '</p>';
                    } else {
                        echo '<p style="color: red;">' . esc_html__('Some core files may have been modified or are missing:', 'file-integrity-checker') . '</p>';
                        echo '<table class="widefat striped"><thead><tr><th style="text-align: left;">' . esc_html__('File', 'file-integrity-checker') . '</th><th>' . esc_html__('Reason', 'file-integrity-checker') . '</th></tr></thead><tbody>';





                        $max_results = 200;
                        $count = 0;
                        foreach ($results as $issue) {
                            if ($count++ >= $max_results) {
                                echo '<tr><td colspan="2">' . esc_html__('Additional issues not shown (limit reached).', 'antihacker') . '</td></tr>';
                                break;
                            }
                            echo '<tr><td style="text-align: left;">' . esc_html(ABSPATH . $issue[0]) . '</td><td>' . wp_kses_post($issue[1]) . '</td></tr>';
                        }





                        echo '</tbody></table>';
                    }
                } elseif (!$show_spinner && isset($_POST['run_integrity_check'])) {
                    echo '<p style="color: red;">' . esc_html__('Failed to retrieve file integrity data. Please try again later.', 'file-integrity-checker') . '</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>