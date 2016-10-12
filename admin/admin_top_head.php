<?php

$admin_status = 1;

header('Content-Type: text/html; charset=utf-8');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

?>
<html>
    <head>
        <title><? echo Dictionary::GetAdminWord(345) . ' &#171;' . BASE_URL . '&#187;!'; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="/admin/js/translit.js"></script>
        <? if (mb_strpos(getenv('SCRIPT_FILENAME'), 'admin/module') !== false || 
            mb_strpos(getenv('SCRIPT_FILENAME'), 'admin/specials') !== false) 
        { ?>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
            <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <? } else { ?>
            <script type="text/javascript" src="/admin/js/jquery-1.7.1.min.js"></script>
            <script type="text/javascript" src="/admin/js/jquery-ui-1.8.18.custom.min.js"></script>        
        <? } ?>
        <script type="text/javascript" src="/admin/js/colorpicker.js"></script>
        <script type="text/javascript" src="/admin/js/timepicker.js"></script>
        <script type="text/javascript" src="/admin/js/def.js"></script>
        <script type="text/javascript" >var plugsExecs = new Array();</script>
        <script type="text/javascript" src="/admin/js/jstree/jquery.tree.min.js"></script>
        <script type="text/javascript" src="/admin/js/jstree/plugins/jquery.tree.checkbox.js"></script>
        <link rel="stylesheet" type="text/css" title="text style" href="/admin/tags.css" />
        <link rel="stylesheet" type="text/css" title="text style" href="/admin/colorpicker.css" />
        <link rel="stylesheet" type="text/css" title="text style" href="/admin/css/ui-lightness/jquery-ui-1.8.18.custom.css" />
    </head>
    <body>
