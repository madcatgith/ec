<?php
class AdminMenu {

	public static $adminStatus = 0;

	public static function GetMenu ($admin_status = 0) {

		self::$adminStatus = $admin_status;
		$tpl               = new Template;
	
		$tpl->set('menu', self::GetMenuChildren(0, array()));
		
		return $tpl->fetch(BASEPATH . 'admin/lib/wmpMenu/templates_1.tpl');    

	}

	public static function GetMenuChildren ($parent_id, $menu_arr) {
		$results = DB::SuperQuery("
		SELECT 
		ma.* 
		, mal.title
		FROM 
		?_menu_admin ma
		left join ?_menu_admin_lang mal on ma.id = mal.id and mal.lang_id = " . 1 . "
		WHERE 1=1
		AND ma.parent_id = {$parent_id}
		ORDER BY
		ma.parent_id asc
		, ma.url desc
		, ma.id asc
		", true);

		foreach ($results as $res) {
			if($res['super_admin_needed'] == 1 && self::$adminStatus != 1) continue;


			# если ссылка на страницу мГрида, то сначала меняем путь на страницу, а потом только устанавливаем абсолютный путь
			if($res['is_mgrid_table'] == 1) {
				$res['url'] = mGridTable($res['url']);
			}

			//$res['url'] = '/admin/' . $res['url'];


			if ($parent_id == 0) {
				$menu_arr[$res['id']] = self::GetMenuChildren($res['id'], $res);
			} else {
				$menu_arr['subs'][$res['id']] = self::GetMenuChildren($res['id'], $res);
			}
		}

		return $menu_arr;
	}
}