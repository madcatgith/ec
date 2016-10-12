<?
// Modified by Shkodenko V. Taras 08/12/2006
define(_CRON_INCLUDE, '1');

// Все расписания для крона
include('../config.php');
require_once('admin_functions.php');
//

include('system_modules.php');
//

if(defined('_INSTALL_PROMOTION') && (_INSTALL_PROMOTION == 1))
 @include ('cron_raskrutka.php');
if(defined('_INSTALL_BILLING') && (_INSTALL_BILLING == 1))
 @include ('billing_cron.php');
if(defined('_INSTALL_STATISTICS') && (_INSTALL_STATISTICS == 1)) {
 @include ('statistics_delete.php');
 @include ('statistics_cron.php');
 @include ('statistics_storage_cron.php');
}
if(defined('_INSTALL_PRODUCT_CATALOGUE') && (_INSTALL_PRODUCT_CATALOGUE == 1))
 @include ('t_search_cron.php');