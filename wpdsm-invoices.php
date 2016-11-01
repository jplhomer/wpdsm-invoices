<?php
/**
 * Plugin Name:     Invoices
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wpdsm-invoices
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wpdsm_Invoices
 */

define('WPDSM_INVOICES_POST_TYPE', 'wpdsm_invoice');

require 'post-types/wpdsm_invoice.php';
require 'src/class-invoice.php';
