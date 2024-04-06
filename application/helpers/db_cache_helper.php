<?php

// application/helpers/db_cache_helper.php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function clear_database_cache()
{
    $ci = & get_instance();
    $ci->db->cache_delete_all();
    log_message('info', 'Database cache cleared.');
}
