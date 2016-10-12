<?php

function get_html_editor($name)
{
  global $hrefauthorization;

  $temp = "<a class=blue href=\"javascript:void(window.open('/admin/html_editor.php?{$hrefauthorization}&name={$name}','HTMLeditor','resizable=yes,width=800,height=600,left=30,top=30'))\">".
          "HTML-редактор".
          "</a>";

  return $temp;
}

?>