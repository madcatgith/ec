<?php

$no_html = 1;
$include = @include("../../../admin_top.php");
if(!$include or $adm_wellcome != "Y") exit;
//$include = include(BASEPATH . "admin/hierarchyhelpers/treeBuilder.php");

?>
<html>
<head>
<title>Выбор пунктов меню</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<meta name="robots" content="noindex, nofollow">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/admin/js/jstree/jquery.tree.min.js"></script>
<script type="text/javascript" src="/admin/js/jstree/plugins/jquery.tree.checkbox.js"></script>


<link rel="stylesheet" type="text/css" title="text style" href="/admin/tags.css" />
<link rel="stylesheet" type="text/css" title="text style" href="/admin/colorpicker.css" />
<link rel="stylesheet" type="text/css" title="text style" href="/admin/css/ui-lightness/jquery-ui-1.7.2.custom.css" />


<link rel="stylesheet" type="text/css" title="text style" href="/admin/tags.css" />
<link href="/admin/css/ui-lightness/jquery-ui-1.7.2.custom.css" title="text style" type="text/css" rel="stylesheet">

    <script type="text/javascript">

    function newwin2(url,width,height) {
        window.open(url,"window","width="+width+",height="+height+",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
    }

    function newwin(url,width,height){
        window.open(url,"window","width="+width+",height="+height+",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
    }
    function newwin3(url,name, width,height) {
        window.open(url,name,"width="+width+",height="+height+",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
    }

    var timeout         = 500;
    var closetimer        = 0;
    var ddmenuitem      = 0;

    function jsddm_open()
    {    jsddm_canceltimer();
    jsddm_close();
    ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');}

    function jsddm_close()
    {    if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}

    function jsddm_timer()
    {    closetimer = window.setTimeout(jsddm_close, timeout);}

    function jsddm_canceltimer()
    {    if(closetimer)
    {    window.clearTimeout(closetimer);
    closetimer = null;}}

    $('#jsddm > li').hover (jsddm_open, jsddm_timer);

    document.onclick = jsddm_close;

    function  CustomClickHandler(node){
        var id = $(node).attr('menu_id')
        if (opener.FireEventFromChild){
            opener.FireEventFromChild ('<?=$_GET['name']?>', id);
            window.close();
        } else {
            opener.document.getElementById('<?=$_GET['name']?>').value=id;
            window.close();
        }
    }

    $(document).ready(function() {
        $('a[type=2]').css('font-style', 'italic');
    });

   </script>
</head>
<body style='margin:0; background-color: #fcf7d5;' onload='this.focus();'>
<?

$lang_array = Lang::getLanguages();
$parent_cat = 0;
if (isset($_GET['item_id']))
    $parent_cat = $_GET['item_id'];

// Заголовок
echo admin_func_top('Выбор пункта меню');
echo admin_func_sys_message($sys_message);

$onClick = "CustomClickHandler(node); return false";

// Кнопки
//поиск
echo admin_func_menu_tree("menu_id", "/admin/menus.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "CustomClickHandler(node);"
            , "width:300px; float:left; font-size:9px;", array(104));

?>
</script>

</body>
</html>
