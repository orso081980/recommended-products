<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              http://example.com
* @since             1.0.0
* @package           recommended_products
*
* @wordpress-plugin
* Plugin Name:       Recommended Products
* Description:       A plugin to recommended products
* Version:           1.0.0
* Author:            Marco Maffei
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'WPINC' ) ) die;
if ( ! defined( 'ABSPATH' ) ) exit;


class RecProducts {

    public function __construct() {
        add_action('admin_enqueue_scripts', array( $this,'rec_products_admin_styles') );
        add_action('admin_enqueue_scripts', array( $this,'media_uploader_enqueue') );
        register_activation_hook(__FILE__, 'rec_products_options_install');
        register_deactivation_hook( __FILE__, 'rec_products_options_remove_database' );
        add_action('admin_menu', array( $this,'rec_products_menu') );
        add_action('init', array( $this,'rec_products_post_type') );
        //add_action('enqueue_block_editor_assets', array( $this,'loadMyBlock') );
        add_action('admin_notices', array( $this, 'dependencies_acf' ) );
        define('ROOTDIR', plugin_dir_path(__FILE__));
        add_shortcode('rec_products', array( $this,'render_rec_products') );
        include 'style.php';
        include 'frontend-style.php';
    }

    public function dependencies_acf() {
        if( !class_exists('acf') ) {
            global $pagenow;
            if ( $pagenow == 'plugins.php' ) {
                echo '<div class="notice notice-warning is-dismissible">
                <p>Please install Advance Custom Field in order to properly run the Recommended Products plugin</p>
                </div>';
            }
        }
    }

    public function rec_products_admin_styles() {
        wp_enqueue_style('custom-styles', plugins_url('/css/rec-products-style.css', __FILE__ ));
    }

    public function media_uploader_enqueue() {
        wp_enqueue_media();
        wp_register_script('media-uploader', plugins_url('js/main.js' , __FILE__ ), array('jquery'));
        wp_enqueue_script('media-uploader');
    }

    public function rec_products_options_install() {
        global $wpdb;
        $table_name = $wpdb->prefix . "rec_products";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
        `id` INT(10) NOT NULL AUTO_INCREMENT,
        `name` varchar(250) CHARACTER SET utf8 NOT NULL,
        `url` varchar(250) CHARACTER SET utf8 NOT NULL,
        `html` longtext CHARACTER SET utf8 NOT NULL,
        PRIMARY KEY (`id`) ) $charset_collate; ";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    public function rec_products_options_remove_database() {
        global $wpdb;
        $table_name = $wpdb->prefix . "rec_products";
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("rec_products_options_db_version");
    }

    public function rec_products_menu() {

        add_menu_page('Recommanded Product',
            'Recommended Products Crud',
            'manage_options',
            'rec_products_list',
            array($this, 'rec_products_list')
        );

        add_submenu_page( 'rec_products_list', 
            'Custom Post Type Admin', 
            'Recommended Products Dashboard', 
            'manage_options',
            'edit.php?post_type=rec_products_type');

    }

    public function rec_products_menu_access() {

        $url = 'edit.php?post_type=rec_products_type';

    }

    public function rec_products_post_type() {
        register_post_type('rec_products_type',
            array(
                'labels'      => array(
                    'name'          => __('Recommended Products', 'textdomain'),
                    'singular_name' => __('Recommended Product', 'textdomain'),
                ),
                'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
                'public'      => true,
                'has_archive' => true,
                'show_ui'     => true,
                'show_in_menu'=> false,

            )
        );
    }

    public function rec_products_list() {

        global $wpdb;
        $table_name = $wpdb->prefix . "rec_products";
        $id = $_POST["delete"];
        $rows = $wpdb->get_results("SELECT * from $table_name");
        if (isset($_POST['delete'])) {
            $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %d", $id));
            require_once(ROOTDIR . 'rec_products_list.php');
        }
        require_once(ROOTDIR . 'rec_products_list.php');

    }

    public function rec_products_create() {

        $name = isset($_POST["name"]) ? $name = $_POST["name"] : '';
        $url = isset($_POST["url"]) ? $url = $_POST["url"] : '';
        $html = isset($_POST["html"]) ? $html = $_POST["html"] : '';

        if (isset($_POST['insert'])) {

            global $wpdb;
            $table_name = $wpdb->prefix . "rec_products";

            $exists = $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE name = %s", $_POST['name']
            ) );
            if ( ! $exists ) {
                $wpdb->insert(
                    $table_name,
                    array(
                        'name' => $name,
                        'url'  => $url,
                        'html' => $html),
                    array('%s', '%s', '%s')
                );
                $message = "Product inserted";
            }

        }
        require_once(ROOTDIR . 'rec_products_create.php');
    }

    public function rec_products_update() {

        global $wpdb;

        $table_name = $wpdb->prefix . "rec_products";
        $id = $_GET["id"];

        $name = isset($_POST["name"]) ? $name = $_POST["name"] : '';
        $url = isset($_POST["url"]) ? $url = $_POST["url"] : '';
        $html = isset($_POST["html"]) ? $html = $_POST["html"] : '';

        if (isset($_POST['update'])) {
            $wpdb->update(
                $table_name,
                array(
                    'name' => $name,
                    'url'  => $url,
                    'html' => $html),
                array('id' => $id)
            );
        }

        else if (isset($_POST['delete'])) {
            $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %d", $id));
        } else {
            $locations = $wpdb->get_results($wpdb->prepare("SELECT id,name,url,html from $table_name where id=%d", $id));
            foreach ($locations as $s) {
                $name = $s->name;
                $url = $s->url;
                $html = $s->html;
            }
        }

        require_once(ROOTDIR . 'rec_products_update.php');
    }

    public function render_rec_products() {

        wp_enqueue_style( 'frontend-rec', plugins_url('css/frontend-rec-style.css', __FILE__ ) );
        wp_enqueue_style( 'Roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap');
        wp_enqueue_style( 'bootstrap', plugins_url('css/bootstrap.min.css', __FILE__ ) );

        $posts = get_posts([
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'rec_products_type',
            'post_status' => 'publish',
            'numberposts' => 3
        ]);

        $html = '';

        foreach ($posts as $key => $rec_prod) {
            if( class_exists('acf') ) {
                $add_to_card = get_field( 'add_to_card', $rec_prod->ID );
                $price = get_field( 'price', $rec_prod->ID );
            } else {
                $add_to_card = ' Please, check if the plugin ACF is installed';
                $price = ' Please, check if the plugin ACF is installed';
            }
            $thumbnails = get_the_post_thumbnail_url( $rec_prod->ID );
            $title = $rec_prod->post_title;
            $url = get_permalink( $rec_prod->ID );
            $html .= '<div class="col-md">';
            $html .= '<div>';
            $html .= '<img src="'.$thumbnails.'">';
            $html .= '<p><a href="'.$url.'">'.$title.'</a></p>';
            $html .= '<p>'.'$'.$price.'</p>';
            $html .= '<a class="button-rec" href="'.$add_to_card.'">Add to card</a>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $atts = shortcode_atts( array(
            'data'=>'0'
        ) , $atts);

        $content =  (empty($content))? " " : $content;

        extract($atts);
        ob_start();

        include( dirname(__FILE__) . '/render_rec_products.php' );

        return ob_get_clean();

    }

    // public function loadMyBlock() {
    //     wp_enqueue_script( 'my-new-block', plugin_dir_url(__FILE__) . 'js/test-block.js', array('wp-blocks','wp-editor'), true );
    // }

}

global $RecProducts;
$RecProducts = new RecProducts();
