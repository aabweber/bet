<?php
/**
 * @package Bet
 */
/*
Plugin Name: Bets Plugin
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

class BetPlugin{
    /** @var string Base Plugin URL */
    private $baseURL = '';
    /** @var string Base Plugin path */
    private $baseDIR = '';
    /** @var bool is user logged in */
    private $userLoggedIn = false;
    /** @var bool is Bet plugin executed */
    private $isBetPlugin = false;

    /**
     * Bet constructor.
     * Инициализируем переменные и вешаем необходимые хуки
     */
    function __construct(){
        $this->baseURL = rtrim(plugin_dir_url( __FILE__ ), '/');
        $this->baseDIR = rtrim(plugin_dir_path( __FILE__ ), '/');
        $this->isBetPlugin = preg_match('/^bets/si', trim($_SERVER['REQUEST_URI'], '/'));
        register_activation_hook( __FILE__, [$this, 'onActivate']);
        register_deactivation_hook(__FILE__, [$this, 'onDeActivate']);
        add_action('admin_menu', [$this, 'addPluginSettingsMenu']);
        add_action('init', function(){
            $this->userLoggedIn = is_user_logged_in();
            $this->init();
        });
    }

    /**
     * По акшену init вставляем скрипты и меняем контент и заголовок
     */
    public function init(){
        add_action( 'wp_enqueue_scripts', [$this, 'insertScripts']);
        if($this->isBetPlugin) {
            add_filter('the_content', [$this, 'content'], 1);
            add_filter('the_title', [$this, 'title'], 10, 2);
            $this->request();
        }
    }

    /**
     * Встовляем JS & CSS
     */
    public function insertScripts(){
        wp_enqueue_style('betstyle', $this->baseURL.'/css/main.css');
        wp_enqueue_style('betstylebootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css');
        wp_enqueue_script('betscriptbootstrap_jq', 'https://code.jquery.com/jquery-3.3.1.slim.min.js');
        wp_enqueue_script('betscriptbootstrap_po', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js');
        wp_enqueue_script('betscriptbootstrap_bs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js');
        wp_enqueue_script('betscript', $this->baseURL.'/js/main.js');
    }

    /**
     * При активации плагина создаём страницу под него
     */
    public function onActivate(){
        $betPageID = get_option('bet_plugin_page');
        if (!$betPageID){
            $bet_page_array = array(
                'post_title' => 'Bet page',
                'post_content' => 'Bet page',
                'post_status' => 'publish'
            );

            $betPageID = wp_insert_post( $bet_page_array );
            update_option('bet_plugin_page', $betPageID);
        }
    }

    /**
     * При деактивации - удаляем страницу и запись в опциях
     */
    public function onDeActivate(){
        delete_option('bet_plugin_page');
    }

    /**
     * Добавляем менюшку в админку
     */
    public function addPluginSettingsMenu(){
        add_menu_page('Bet Plugin Settings', 'Bet Plugin', 'manage_options', 'bet-plugin', function(){
            include __DIR__.'/templates/admin/index.php';
        });
    }

    /**
     * Обработка запроса по REST
     * @param array $args
     */
    public function process($args){
        if(!$args) {
        }
    }

    /**
     * Возвращает URL плагина
     * @return string
     */
    public function getBaseURL(){
        return $this->baseURL;
    }

    /**
     * Возвращает директорию плагина
     * @return string
     */
    public function getBaseDIR(){
        return $this->baseDIR;
    }

    /**
     * Подключаем основной шаблон
     * @return string
     */
    public function content(){
        $mainTemplate = $this->getBaseDIR() . '/templates/main/index.php';
        ob_start();
        include $mainTemplate;
        $c = ob_get_contents();
        ob_end_clean();
        return $c;
    }

    /**
     * Обнуляем заголовок
     * @param $title
     * @param $id
     * @return string
     */
    function title( $title, $id ) {
        return '';
    }

    /**
     * Проверка на аутентификацию
     * @return bool
     */
    public function isUserLoggedIn(){
        return $this->userLoggedIn;
    }

    /**
     * Вернёт наш плагин раобтает?
     * @return bool
     */
    public function isBetPlugin(){
        return $this->isBetPlugin;
    }

    /**
     * Проверим ша плагин? Авторизованы? Тогда обработаем запрос.
     */
    public function request(){
        if ($this->isBetPlugin()) {
            if($this->isUserLoggedIn()) {
                $this->process($_REQUEST);
            }else{
                header('Location: https://bet.aabweber.com/wp-login.php');
                exit;
            }
        }
    }
}

$bet = new BetPlugin();

