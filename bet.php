<?php
/**
 * @package Bet
 */
/*
Plugin Name: Bet
Plugin URI: https://bet.aabweber.com/
Description: Do bet and win!!!
Version: 0.1
Author: aabweber
Author URI: https://aabweber.com
License: GPLv2 or later
*/

// Убедимся, что нас вызвали не на прямую
if ( !function_exists( 'add_action' ) ) {
	echo 'Доступ к плагину на прямую запрещён!';
	exit;
}

class Bet{
    /** @var string Base Plugin URL */
    private $baseURL = '';

    /**
     * Bet constructor.
     */
    function __construct(){
        $this->baseURL = plugin_dir_url( __FILE__ );
        add_action( 'wp_enqueue_scripts', [$this, 'insertScripts']);
        register_activation_hook( __FILE__, [$this, 'onActivate']);
        register_deactivation_hook(__FILE__, [$this, 'onDeActivate']);
        add_action('admin_menu', [$this, 'addPluginSettingsMenu']);
    }

    public function insertScripts(){
        wp_enqueue_style('betstyle', $this->baseURL.'css/main.css');
        wp_enqueue_script('betscript', $this->baseURL.'js/main.js');
    }

    public function onActivate(){
        $betPageID = get_option('bet_plugin_page');
        if (!$betPageID){
            $bet_page_array = array(
                'post_title' => 'Bet page',
                'post_content' => 'Bet page content',
                'post_status' => 'publish'
            );

            $betPageID = wp_insert_post( $bet_page_array );
            update_option('bet_plugin_page', $betPageID);
        }
    }

    public function onDeActivate(){
        delete_option('bet_plugin_page');
    }

    public function addPluginSettingsMenu(){
        add_menu_page('Bet Plugin Settings', 'Bet Plugin', 'manage_options', 'bet-plugin', function(){
            include __DIR__.'/templates/admin/index.php';
        });
    }
}

$bet = new Bet();
