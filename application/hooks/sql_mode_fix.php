<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fix SQL mode to disable ONLY_FULL_GROUP_BY
 * This is required for MySQL 5.7+ compatibility with legacy queries
 */
function fix_sql_mode()
{
    $CI =& get_instance();
    
    // Only run if database is loaded
    if (isset($CI->db)) {
        $CI->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    }
}
