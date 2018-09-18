<?php
   /*
   Plugin Name: Custom Portfolio importer
   Version: 1
   Author: Garima
   */
?>
<?php

	add_action('admin_menu', 'import_portfolio_menu');
 
function import_portfolio_menu(){
        add_menu_page( 'Portfolio Importer Page', 'Portfolio importer', 'manage_options', 'portfolio-importer', 'upload_data' );
        
}


define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'insert-data.php');

?>
