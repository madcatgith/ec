<?
$tmpStart = '<link rel="stylesheet" type="text/css" media="screen" href="/admin/mGrid/engine/css/ui.jqgrid.css" />
			<link type="text/css" href="/admin/mGrid/engine/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
			<div id="gbox_editgrid" class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
				<div class="ui-jqgrid">
			 		<div class="ui-jqgrid-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"><span class="ui-jqgrid-title">{title}</span></div>
			 		<div id="pager" class="ui-state-default ui-jqgrid-pager corner-bottom"><div id="pg_pager" class="ui-pager-control" role="group">
			 			<table class="ui-pg-table" cellspacing="0" cellpadding="0" border="0" role="row" style="table-layout: fixed;"><tr>
			 			<td id="pager_left" align="left"><table cellspacing="0" cellpadding="0" border="0" style="float: left; table-layout: auto;" class="ui-pg-table navtable"><tr><td  onMouseOver="$(this).addClass(\'ui-state-hover\')" onMouseOut="$(this).removeClass(\'ui-state-hover\')" class="ui-pg-button ui-corner-all" title="Добавить новую запись" id="add_list" style="border-style:solid; border-width:1px;"><div class="ui-pg-div"><span class="ui-icon ui-icon-plus"></span></div></td>'
			 		.'<td onMouseOver="$(this).addClass(\'ui-state-hover\')" onMouseOut="$(this).removeClass(\'ui-state-hover\')" class="ui-pg-button ui-corner-all" title="Найти записи" id="search_editgrid" style="border-style:solid; border-width:1px;"><div class="ui-pg-div"><span class="ui-icon ui-icon-search"></span></div></td>'
			 		.'<td onMouseOver="$(this).addClass(\'ui-state-hover\')" onMouseOut="$(this).removeClass(\'ui-state-hover\')" class="ui-pg-button ui-corner-all" title="Обновить таблицу" id="refresh_list" style="border-style:solid; border-width:1px;">
			 		<div class="ui-pg-div">
			 		<a href="'.$_SERVER['REQUEST_URI'].'"><span class="ui-icon ui-icon-refresh"></span></a></div>
			 		</td>
			 		<td style="padding-left: 10px;">Всего {count} записей.</td><td>{pager_control}</td><td>{lang_control}</td>
			 		</tr></table></td></tr></table>
			 		</div></div>			 		
			 		<table id="mGridTable" cellspacing="0" cellpadding="0" border="0" aria-labelledby="gbox_editgrid" role="grid" class="ui-jqgrid-htable" style="{style}">';
$tmpEnd   = '	    </table><div id="pager" class="ui-state-default ui-jqgrid-pager corner-bottom">{pager_control}</div>';

$onPage     = '<td><select role="listbox" class="ui-pg-selbox">{optionList}</select></td>';
$optionList = '<option {selected} value="{value}" role="option">{value}</option>';

//
$tmpTrStrat = '<tr role="rowheader" class="ui-jqgrid-labels">';
$tmpTrEnd   = '<th style="width: 60px; text-align: center;" class="ui-state-default ui-th-column" role="columnheader">Действия</th></tr>';
$tmpThAsc   = '<th {style} class="ui-state-default ui-th-column" role="columnheader"><span class="ui-jqgrid-resize">&nbsp;</span><div id="jqgh_title_1" class="ui-jqgrid-sortable" style="padding-right: 15px;"><a href="{url}&amp;sidx={field}&amp;sord=desc">{title}</a><span style="" class="s-ico"><span class="ui-grid-ico-sort ui-icon-asc ui-icon ui-icon-triangle-1-n" sort="asc"></span><span class="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s" sort="desc"></span></span></div></th>';
$tmpThDesc  = '<th {style} class="ui-state-default ui-th-column" role="columnheader"><span class="ui-jqgrid-resize">&nbsp;</span><div id="jqgh_title_1" class="ui-jqgrid-sortable" style="padding-right: 15px;"><a href="{url}&amp;sidx={field}&amp;sord=asc">{title}</a><span class="s-ico"><span class="ui-grid-ico-sort ui-icon-asc ui-icon ui-icon-triangle-1-n ui-state-disabled" sort="asc"></span><span class="ui-grid-ico-sort ui-icon-desc ui-icon ui-icon-triangle-1-s" sort="desc"></span></span></div></th>';
$tmpTh		= '<th {style} class="ui-state-default ui-th-column" role="columnheader"><span class="ui-jqgrid-resize">&nbsp;</span><div id="jqgh_title_1" class="ui-jqgrid-sortable"><a href="{url}&amp;sidx={field}&amp;sord=asc">{title}</a><span style="display: none;" class="s-ico"><span class="ui-grid-ico-sort ui-icon-asc ui-icon ui-icon-triangle-1-n {asc}" sort="asc"></span><span class="ui-grid-ico-sort ui-icon-desc ui-icon ui-icon-triangle-1-s {desc}" sort="desc"></span></span></div></th>';

$tmpTrContentStart = '<tr class="ui-widget-content jqgrow" onMouseOver="$(this).addClass(\'ui-state-hover\')" onMouseOut="$(this).removeClass(\'ui-state-hover\')" role="row" id="{id}" on>';
$tmpTrContentEnd   = '<td title="Действия" style="" class="delete_row" role="gridcell"><div id="del_row" class="ui-pg-div" style="text-align: center;"><span class="ui-icon ui-icon-trash" style="cursor: pointer; margin: 0 auto;"></span></div></td></tr>';

$tmpTd['image']    = '<td title="{title}" role="gridcell" style="text-align: center;{style}"><img src="/i.php?temp_small_img_url={title}&{par1}={val1}" alt="{title}" /></td>';
$tmpTd['gallery']    = '<td title="{title}" role="gridcell" style="text-align: center;{style}">{title}</td>';
$tmpTd['menu']    = '<td title="{title}" role="gridcell" style="text-align: center;{style}">{title}</td>';

$tmpTd['color']    = '<td title="{title}" role="gridcell" style="background-color:#{color};text-align: center;{style}"><span style="background-color:white;">{val1}</span></td>';
$tmpTd['map'] = $tmpTd['lbl'] = $tmpTd['select'] = $tmpTd['date'] = $tmpTd['datetime'] = $tmpTd['text'] = $tmpTd['cnc'] = $tmpTd['textarea'] = $tmpTd['input'] = 
	'<td style="white-space:normal;{style}" role="gridcell">{title}</td>';
$tmpTd['check'] = '<td title="{title}" style="{style}" role="gridcell"><ins class="{title}" >&nbsp;</ins></td>';

$tmpTd['html'] = '<td title="{title}" style="{style}" role="gridcell">{title}</td>';