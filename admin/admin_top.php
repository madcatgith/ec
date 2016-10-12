<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

foreach (array($_GET, $_POST) as $arr) {
    extract($arr, EXTR_OVERWRITE);
}

function getIDs($parents, $ID)
{
    $arr = array($ID);

    if (isset($parents[$ID])) {
        foreach ($parents[$ID] as $v) {
            $arr[] = getIDs($parents, $v);
        }
    }

    return implode(',', array_filter($arr));
}

include getenv('DOCUMENT_ROOT') . '/config.php';
include BASEPATH . 'admin/admin_modules.php';

Menu::load(1);

/*
 * Include MVC modules
 */
define('APP', dirname(__FILE__) .'/module');

foreach (array(
    glob(APP . '/controller/*.php'),
    glob(APP . '/model/*.php'),
    glob(APP . '/model/*/*.php'),
    glob(APP . '/controller/*/*.php')
) as $file) {
    foreach ($file as $f) {
        include $f;
    }
}

define('_ADMIN_ZONE', 1);
define('_UP_LICENSE', 1);

Registry::set('maxMenuLevel', 6);

$adminLang = (array) DB::GetRow(DB::Query('select id from ?_lang where admin_default = 1 limit 1'));

if(isset($adminLang['id'])) {
    Registry::set('defAdmin', $adminLang['id']);
} else {
    Registry::set('defAdmin', Lang::getID());
}

$default_lang       = Lang::getID();
$global_lang_id     = Lang::getID();
$default_lang_admin = Lang::getID();

require_once BASEPATH . 'admin/lib/wmpGallery/wmpGallery.php';
require_once BASEPATH . 'admin/admin_functions.php';
require_once BASEPATH . 'admin/hierarchyhelpers/treeBuilder.php';
require_once BASEPATH . 'admin/system_modules.php';
require_once BASEPATH . 'admin/helpers.php';

$wmpAdmin = new Admin($_COOKIE);
$wmpAdmin->LogIn();

if (!$wmpAdmin->isLogedIn()) {
    $return = parse_url('http://' . getenv('SERVER_NAME') . getenv('REQUEST_URI'));
    $back_url = '';
    
    if($return) {
        $back_url = http_build_query(array('backUrl' => base64url_encode(serialize($return))));
    }

    header('Location: /admin/?'. $back_url);
} else {

    if (!isset($no_html) || $no_html == '') {
        include BASEPATH . 'admin/admin_top_head.php';

        if (!isset($no_top) || $no_top == '') {
            ?>
            <ul id="jsddm">
                <?php
                if ($wmpAdmin->info['status'] != 2) {

                    echo '<li><span>Управление</span><ul>';
                    echo admin_func_menu_block_list('Управление структурой сайта', '#', 'crossing');
                    echo admin_func_menu_block_list('Пункты меню', 'menus.php');
                    echo admin_func_menu_block_list('Контент', 'content.php');
                    echo admin_func_menu_block_list('Медиа-центр', '#', 'crossing');
                    echo admin_func_menu_block_list('Файлы', 'files.php');
                    echo admin_func_menu_block_list('Галереи', 'lib/wmpGallery/gallery.php');
                    echo '</ul></li>';
                    echo '<li><span>Инфоблоки</span><ul>';
                    //echo admin_func_menu_block_list('Категории', mGridTable('Category'));
                    //echo admin_func_menu_block_list('Слайдер (главная)', mGridTable('Portfolio'));
                    echo admin_func_menu_block_list('Контакты', mGridTable('Contacts'));
					
                    echo '</ul></li>';
                    echo '<li><span>Модули</span><ul>';
                    echo admin_func_menu_block_list('Конструктор форм', 'module/form/');
                    echo admin_func_menu_block_list('Баннеры', 'module/banner/');
                    echo '</ul></li>';
					echo '<li><span>Магазин</span><ul>';
                    //echo admin_func_menu_block_list('Свойства товаров', mGridTable('Dictionary'));
                    echo admin_func_menu_block_list('Каталог', 'lib/wmpProduct/catalogue.php');
                    //echo admin_func_menu_block_list('Заказы', 'orders/');
					//echo admin_func_menu_block_list('Таблица электрических товаров', mGridTable('ElProduct'));
                                        //echo admin_func_menu_block_list('Преформы', mGridTable('Preform_products'));
                                        echo admin_func_menu_block_list('Свойства преформ', mGridTable('Preform_attr'));
                    echo '</ul></li>';
                }

                echo '<li><span>Словари</span><ul>';
                echo admin_func_menu_block_list("Локализация", '#', 'crossing');
                echo admin_func_menu_block_list('Уникальный словарь', mGridTable('Unique'));
                echo '</ul></li>';

                if ($wmpAdmin->info['status'] != 2) {

                    echo "<li><span>Настройки</span><ul>";
                    //echo admin_func_menu_block_list('Телефоны', mGridTable('Telephones'));
                    echo admin_func_menu_block_list('Титлы', "ConfigTitle.php");
                    echo admin_func_menu_block_list("Общие", "db_config.php");
                    echo admin_func_menu_block_list("Языки", mGridTable('Langs'));

                    if ($wmpAdmin->info['status'] == 1) {
                        echo admin_func_menu_block_list("Администраторы", "admins.php");
                    }

                    echo "</ul></li>";
                }

                echo '<li>
                <a target="_parent" style="background-image: url(/admin/g/exit.gif); background-position: 15px center; background-repeat: no-repeat;" href="/admin/?exit=1">Выход</a>
                </li>';
                ?>
            </ul><div class="clear">

                <script type="text/javascript">

                    function newwin2(url, width, height) {
                        window.open(url, "window", "width=" + width + ",height=" + height + ",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
                    }

                    function newwin(url, width, height) {
                        window.open(url, "window", "width=" + width + ",height=" + height + ",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
                    }
                    function newwin3(url, name, width, height) {
                        window.open(url, name, "width=" + width + ",height=" + height + ",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
                    }

                    var timeout = 500;
                    var closetimer = 0;
                    var ddmenuitem = 0;

                    function jsddm_open()
                    {
                        jsddm_canceltimer();
                        jsddm_close();
                        ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');
                    }

                    function jsddm_close()
                    {
                        if (ddmenuitem)
                            ddmenuitem.css('visibility', 'hidden');
                    }

                    function jsddm_timer()
                    {
                        closetimer = window.setTimeout(jsddm_close, timeout);
                    }

                    function jsddm_canceltimer()
                    {
                        if (closetimer)
                        {
                            window.clearTimeout(closetimer);
                            closetimer = null;
                        }
                    }

                    $('#jsddm > li').hover(jsddm_open, jsddm_timer);

                    document.onclick = jsddm_close;

                </script>

                <?php
            }
        }
        $adm_wellcome = "Y";
    }

    unset($no_html);
    unset($no_top);
    