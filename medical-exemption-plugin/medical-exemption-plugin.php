<?php
  /**
  * Plugin Name: Medical Exemption Plugin
  * Description: A WordPress plugin that receives a date and two optional arguments to retrieve and display medical exemption information from a database.
  * Version: 1.0.0
  * Author: Reza Hosseini Dolama
  * Text Domain: medical-exemption-plugin
  * Domain Path: /languages
  */

// define plugin constants
define('MEDICAL_EXEMPTION_VERSION','1.0.0');
define('MEDICAL_EXEMPTION_DIR',plugin_dir_path(__FILE__));
define('MEDICAL_EXEMPTION_URL',plugin_dir_url(__FILE__));

  if (!defined('ABSPATH')) {
    die("You are not supposed to be here!");
  }
  if (!class_exists('MedicalExemption')){
    class MedicalExemption{
      function __construct(){
        register_activation_hook(__FILE__, array($this, 'create_medical_exemption_table'));
        add_action('init',array($this,'register_block'));
        add_action('enqueue_block_assets', array($this, 'enqueue_block_assets'));
  }

      public function create_medical_exemption_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'medical_exemption';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
          id INT AUTO_INCREMENT PRIMARY KEY,
          user_ip VARCHAR(255) NOT NULL,
          request_time DATETIME NOT NULL,
          medical_exemption_date VARCHAR(255) NOT NULL,
          medical_exemption_section VARCHAR(255) NOT NULL,
          medical_exemption_article VARCHAR(255) NOT NULL
          ) $charset_collate";
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          dbDelta($sql);
          add_option('medical_exemption_db_version', MEDICAL_EXEMPTION_VERSION);
      }

      public function register_block() {
        register_block_type(MEDICAL_EXEMPTION_DIR . 'block.json', array(
            'render_callback' => array($this, 'render_block'),
        ));
      }

      public function render_block($attributes, $content) {
        return '<div id="medical-exemption-root" class="wp-block-medical-exemption"></div>';
      }

      public function enqueue_block_assets() {
        $asset_manifest = MEDICAL_EXEMPTION_DIR . 'assets/build/.vite/manifest.json';
        
        // Development mode (Vite dev server)
        if (defined('WP_DEBUG') && WP_DEBUG && !file_exists($asset_manifest)) {
          wp_enqueue_script(
              'medical-exemption-vite-client',
              'http://localhost:5173/@vite/client',
              array(),
              null,
              true
          );
          
          wp_enqueue_script(
              'medical-exemption-app',
              'http://localhost:5173/src/main.tsx',
              array(),
              null,
              true
          );
          
          wp_add_inline_script(
              'medical-exemption-vite-client',
              'window.process = { env: { NODE_ENV: "development" } };',
              'before'
          );
          
          // Localize script for dev mode
          wp_localize_script('medical-exemption-app', 'medicalExemptionData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('medical_exemption_nonce'),
            'apiUrl' => rest_url('medical-exemption/v1/'),
            'pluginUrl' => MEDICAL_EXEMPTION_URL,
          ));
        } 
        // Production mode (built files)
        else if (file_exists($asset_manifest)) {
          $manifest = json_decode(file_get_contents($asset_manifest), true);
          
          if (isset($manifest['src/main.tsx'])) {
              $main = $manifest['src/main.tsx'];
              
              // Enqueue CSS
              if (isset($main['css'])) {
                  foreach ($main['css'] as $css_file) {
                      wp_enqueue_style(
                          'medical-exemption-style',
                          MEDICAL_EXEMPTION_URL . 'assets/build/' . $css_file,
                          array(),
                          MEDICAL_EXEMPTION_VERSION
                      );
                  }
              }
              
              // Enqueue JS
              wp_enqueue_script(
                'medical-exemption-app',
                MEDICAL_EXEMPTION_URL . 'assets/build/' . $main['file'],
                array(),
                MEDICAL_EXEMPTION_VERSION,
                true
            );
            
            // Localize script for production mode
            wp_localize_script('medical-exemption-app', 'medicalExemptionData', array(
              'ajaxUrl' => admin_url('admin-ajax.php'),
              'nonce' => wp_create_nonce('medical_exemption_nonce'),
              'apiUrl' => rest_url('medical-exemption/v1/'),
              'pluginUrl' => MEDICAL_EXEMPTION_URL,
            ));
          }
        }
      }
    }
  }
  new MedicalExemption();
  require_once MEDICAL_EXEMPTION_DIR . 'includes/class-api.php';
?>

