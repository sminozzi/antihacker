<?php

namespace antihacker_BillChat;
// 2024-12=18
if (!defined('ABSPATH')) {
    die('Invalid request.');
}
if (function_exists('is_multisite') && is_multisite()) {
    return;
}
class ChatPlugin
{
    public function __construct()
    {
        // Hooks para AJAX
        add_action('wp_ajax_bill_chat_send_message', [$this, 'bill_chat_send_message']);
        //add_action('wp_ajax_nopriv_bill_chat_send_message', [$this, 'bill_chat_send_message']);
        add_action('wp_ajax_bill_chat_reset_messages', [$this, 'bill_chat_reset_messages']);
        //add_action('wp_ajax_nopriv_bill_chat_reset_messages', [$this, 'bill_chat_reset_messages']);
        add_action('wp_ajax_bill_chat_load_messages', [$this, 'bill_chat_load_messages']);
        // Registrar os scripts
        add_action('admin_init', [$this, 'chat_plugin_scripts']);
        add_action('admin_init', [$this, 'enqueue_chat_scripts']);
    }
    public function chat_plugin_scripts()
    {
        wp_enqueue_style(
            'chat-style',
            plugin_dir_url(__FILE__) . 'chat.css'
        );
    }
    public function enqueue_chat_scripts()
    {
        wp_enqueue_script(
            'chat-script',
            plugin_dir_url(__FILE__) . 'chat.js',
            array('jquery'),
            '',
            true
        );
        wp_localize_script('chat-script', 'bill_data', array(
            'ajax_url'                 => admin_url('admin-ajax.php'),
            'reset_success'            => esc_attr__('Chat messages reset successfully.', 'antihacker'),
            'reset_error'              => esc_attr__('Error resetting chat messages.', 'antihacker'),
            'invalid_message'          => esc_attr__('Invalid message received:', 'antihacker'),
            'invalid_response_format'  => esc_attr__('Invalid response format:', 'antihacker'),
            'response_processing_error' => esc_attr__('Error processing server response:', 'antihacker'),
            'not_json'                 => esc_attr__('Response is not valid JSON.', 'antihacker'),
            'ajax_error'               => esc_attr__('AJAX request failed:', 'antihacker'),
            'send_error'               => esc_attr__('Error sending the message. Please try again later.', 'antihacker'),
            'empty_message_error'      => esc_attr__('Please enter a message!', 'antihacker'),
        ));
    }
    /**
     * Função para carregar as mensagens do chat.
     */
    public function bill_chat_load_messages()
    {
        $messages = get_option('chat_messages', []);
        $last_count = isset($_POST['last_count']) ? intval($_POST['last_count']) : 0;
        // Verifica se há novas mensagens
        $new_messages = [];
        if (count($messages) > $last_count) {
            $new_messages = array_slice($messages, $last_count);
        }
        // Retorna as mensagens no formato JSON
        wp_send_json([
            'message_count' => count($messages),
            'messages' => array_map(function ($message) {
                return [
                    'text' => esc_html($message['text']),
                    'sender' => esc_html($message['sender'])
                ];
            }, $new_messages)
        ]);
        wp_die();
    }
    public function bill_read_file($file, $lines)
    {
        $handle = fopen($file, "r");
        if (!$handle) {
            return "";
        }
        $bufferSize = 8192; // Tamanho do bloco de leitura (8KB)
        $text = [];
        $currentChunk = '';
        $linecounter = 0;
        // Move para o final do arquivo e começa a leitura para trás
        fseek($handle, 0, SEEK_END);
        $filesize = ftell($handle); // Tamanho do arquivo
        // Ajustar bufferSize para o tamanho do arquivo se for menor que 8KB
        if ($filesize < $bufferSize) {
            $bufferSize = $filesize;
        }
        $pos = $filesize - $bufferSize;
        while ($pos >= 0 && $linecounter < $lines) {
            if ($pos < 0) {
                $pos = 0;
            }
            fseek($handle, $pos);
            $chunk = fread($handle, $bufferSize);
            $currentChunk = $chunk . $currentChunk;
            $linesInChunk = explode("\n", $currentChunk);
            $currentChunk = array_shift($linesInChunk);
            foreach (array_reverse($linesInChunk) as $line) {
                $text[] = $line;
                $linecounter++;
                if ($linecounter >= $lines) {
                    break 2;
                }
            }
            $pos -= $bufferSize;
        }
        if (!empty($currentChunk)) {
            $text[] = $currentChunk;
        }
        fclose($handle);
        return $text;
    }
    /**
     * Função para chamar a API do ChatGPT.
     */
    public function bill_chat_call_chatgpt_api($data, $chatType)
    {
        //ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);



        $transient_name = 'bill_chat';

        // delete_transient($transient_name);


        if (false === get_transient($transient_name)) {
            $file = ABSPATH . "error_log";
            try {
                // Verificar se o arquivo existe e é legível
                if (file_exists($file) && is_readable($file)) {
                    $bill_chat_erros = $this->bill_read_file($file, 20);
                } else {
                    $bill_chat_erros = "The file does not exist or is not readable.";
                }
                // Debug e logs
                // debug2($bill_chat_erros);
                // error_log(var_export($bill_chat_erros, true));
                if (is_array($bill_chat_erros) || is_object($bill_chat_erros)) {
                    // error_log(print_r($bill_chat_erros, true));
                } else {
                    // error_log($bill_chat_erros);
                }
                // Incluir ferramenta adicional
                include_once ANTIHACKERPATH . 'dashboard/tools.php';
                $antihacker_checkup = antihacker_sysinfo_get();
            } catch (Exception $e) {
                // Captura qualquer exceção lançada e registra no log
                error_log("Exception caught: " . $e->getMessage());
                $bill_chat_erros = "An error occurred: " . $e->getMessage();
                $antihacker_checkup = '';
            }
            // Transiente não existe, cria um novo com a data atual
            $current_date = date('Y-m-d H:i:s'); // Formato da data: Ano-Mês-Dia Hora:Minuto:Segundo
            set_transient($transient_name, $current_date, DAY_IN_SECONDS); // Transiente com duração de 1 dia

        } else {
            $antihacker_checkup = '';
            $bill_chat_erros = '';
        }
        //
        //
        //
        //
        //


        $plugin_path = plugin_basename(__FILE__); // Retorna algo como "plugin-folder/plugin-file.php"
        $language = get_locale();
        $plugin_slug = explode('/', $plugin_path)[0]; // Pega apenas o primeiro diretório (a raiz)
        $domain = parse_url(home_url(), PHP_URL_HOST);




        $data2 = [
            'param1' => $data,
            'param2' => $antihacker_checkup,
            'param3' => $bill_chat_erros,
            'param4' => $language,
            'param5' => $plugin_slug,
            'param6' => $domain,
            'param7' => $chatType,
        ];


        $response = wp_remote_post('https://BillMinozzi.com/chat/api/api.php', [
            'timeout' => 60,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data2),
        ]);
        if (is_wp_error($response)) {
            $error_message = sanitize_text_field($response->get_error_message());
        } else {
            $body = sanitize_text_field(wp_remote_retrieve_body($response));
            $data = json_decode($body, true);
        }
        if (isset($data['success']) && $data['success'] === true) {
            $message = $data['message'];
        } else {
            $message = esc_attr__("Error contacting the Artificial Intelligence (API). Please try again later.", "antihacker");
        }
        return $message;
    }
    /**
     * Função para enviar a mensagem do usuário e obter a resposta do ChatGPT.
     */
    public function bill_chat_send_message()
    {


        // Captura e sanitiza a mensagem
        $message = sanitize_text_field($_POST['message']);

        // Verifica e sanitiza o chat_type, atribuindo 'default' caso não exista
        $chatType = isset($_POST['chat_type']) ? sanitize_text_field($_POST['chat_type']) : 'default';






        // Chama a API e obtém a resposta
        $response_data = $this->bill_chat_call_chatgpt_api($message, $chatType);
        // Verifique se a resposta foi obtida corretamente
        if (!empty($response_data)) {
            $output = $response_data;
            $resposta_formatada = $output;
        } else {
            $output = "Error to get response from AI source!";
            $output = esc_attr__("Error to get response from AI source!", "antihacker");
        }
        // Prepara as mensagens
        $messages = get_option('chat_messages', []);
        $messages[] = [
            'text' => $message,
            'sender' => 'user'
        ];
        $messages[] = [
            'text' => $resposta_formatada,
            'sender' => 'chatgpt'
        ];
        update_option('chat_messages', $messages);
        wp_die();
    }
    /**
     * Função para resetar as mensagens.
     */
    public function bill_chat_reset_messages()
    {
        update_option('chat_messages', []);
        wp_die();
    }
}
new ChatPlugin();
