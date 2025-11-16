<?php
  /**
  * Plugin Name: Medical Exemption Plugin
  * Description: A WordPress plugin that receives a date and two optional arguments to retrieve and display medical exemption information from a database.
  * Version: 1.0.0
  * Author: Reza Hosseini Dolama
  * Text Domain: medical-exemption-plugin
  * Domain Path: /languages
  */

  if (!defined('ABSPATH')) {
  die("You are not supposed to be here!");
  }

  echo "<h1>بررسی علت معافیت مراجعین</h1>";
  echo "<link rel='stylesheet' href='medical-exemption.css'>";
  require('initialize-plugin.php');
  require('medical-exemption-plugin.php');
?>

