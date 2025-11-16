<?php
function create_medical_exemption_table() {
  global $wpdb;
  $medical_exemption_db_version = '1.0.0';
  $table_name = $wpdb->prefix . 'medical_exemption';
  $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
  if ($table_exists) {
    return;
  }
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE $table_name (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_ip VARCHAR(255) NOT NULL,
    request_time DATETIME NOT NULL,
    medical_exemption_date VARCHAR(255) NOT NULL,
    medical_exemption_section VARCHAR(255) NOT NULL,
    medical_exemption_article VARCHAR(255) NOT NULL,
    ) $charset_collate";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option('medical_exemption_db_version', $medical_exemption_db_version);
}

register_activation_hook(__FILE__, 'create_medical_exemption_table');

function submit_medical_exemption() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'medical_exemption';
  $wpdb->insert($table_name, [
    'user_ip' => $_SERVER['REMOTE_ADDR'],
    'request_time' => current_time('mysql'),
    'medical_exemption_date' => $_POST['medical_exemption_date'],
    'medical_exemption_section' => $_POST['medical_exemption_section'],
    'medical_exemption_article' => $_POST['medical_exemption_article'],
  ]);
}
?>