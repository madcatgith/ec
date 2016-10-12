<?

if ( ! defined('BASEPATH')) die();

echo Menu::getTreeByTemplate(Lang::getID(), 0, 'site_map');