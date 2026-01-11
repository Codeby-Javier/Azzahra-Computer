<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

// Auto-create HR database tables on application start
$hook['post_controller_constructor'][] = array(
    'class'    => '',
    'function' => 'ensure_hr_tables',
    'filename' => 'db_migration_helper.php',
    'filepath' => 'helpers'
);

// Fix SQL mode ONLY_FULL_GROUP_BY
$hook['post_controller_constructor'][] = array(
    'class'    => '',
    'function' => 'fix_sql_mode',
    'filename' => 'sql_mode_fix.php',
    'filepath' => 'hooks'
);
