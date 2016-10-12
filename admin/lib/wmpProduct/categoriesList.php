<?
    $category_id = $parent_id;

    if(!$include or $adm_wellcome != "Y") exit;

    if($move == "up" or $move == "down") {
      if($move == "up")   $move_num = -1;
      if($move == "down") $move_num = 1;

      $select_product_category = DB::Query("select * from `?_product_category` where id=$id ");
      $get_product_category = DB::GetArray($select_product_category);

      $change_place1 = DB::Query("update `?_product_category` set place=place-$move_num where place=$get_product_category[place]+$move_num and category_id='$get_product_category[category_id]'");
      $change_place2 = DB::Query("update `?_product_category` set place=place+$move_num where id=$id");
    }

    if($delete == "yes" && $_REQUEST["delType"] == "category") {
        $select_subproduct_category = DB::Query("select count(*)+0 from `?_product_category` where category_id={$id} ");
      if(@array_shift(DB::GetArray($select_subproduct_category)) > 0)
        $sys_message[] = Dictionary::GetAdminWord(886);
      else {
        $select_product_category = DB::Query("select * from `?_product_category` where id=$id ");
        $get_product_category = DB::GetArray($select_product_category);
        $update_places = DB::Query("update `?_product_category` set place=place-1 where place>$get_product_category[place] and category_id=$get_product_category[category_id]");

        $delete_product_category    = DB::Query("delete from `?_product_category` where id=$id ");
      //  $delete_content = DB::Query("delete from `?_product` where category_id=$id ");
        if(!$delete_product_category or !$delete_content) $sys_message[] = Dictionary::GetAdminWord(706);
        else $sys_message[] = Dictionary::GetAdminWord(707);
      }
    }

    $select_max_place = DB::Query("select max(place)+0 from `?_product_category` where category_id='$category_id' ");
    $max_place = @array_shift(DB::GetArray($select_max_place));

    $dos_category_id = $category_id;
    if($dos_category_id > 0) {
      do {
        $select_cur_position = DB::Query("select * from `?_product_category` where id=$dos_category_id and lang_id='" . Lang::getID() . "'");
        $get_dos_product_category = DB::GetArray($select_cur_position);
        $str_cur_position = " <font style=\"font-size:15px\"><b>&raquo;</b> <a class=\"w\" href=catalogue.php?item_id=$get_dos_product_category[id]>$get_dos_product_category[title]</a>$str_cur_position";
        $dos_category_id = $get_dos_product_category[category_id];
      } while($dos_category_id > 0);
    }

    $select_product_categories = DB::Query("select * from `?_product_category` where category_id=$category_id order by place,lang_id ");
    while($get_product_category = DB::GetArray($select_product_categories)) {
      if(!@in_array($get_product_category['id'],$array_category_ids)) {
        $array_product_categories[]       = $get_product_category['title'];
        $array_category_ids[]    = $get_product_category['id'];
        $array_product_category_places[] = $get_product_category['place'];
      }
    }
    echo admin_func_right_table_start(3);
    echo admin_func_right_table_row_start(3);

    $anch = "<a onClick='open_popupWMP(\"category_add.php?category_id={$category_id}\"); return false;' href='category_add.php?category_id={$category_id}' style='text-decoration:none; margin-left:20px;' >
             <img src=\"/admin/g/nw.gif\" border=0 alt=\"". Dictionary::GetAdminWord(888) ."\" width=11 height=12>
             </a>";

    echo "<td class=\"w\" colspan=2 height=\"25\" style='padding-left: 20px; font-weight:bold; color:white;' > Вложенные категории ";
    echo $anch;
    echo "</td></tr>";


echo admin_func_right_table_start("");
echo "$formauthorization";
echo admin_func_right_table_row_start("");
echo admin_func_right_table_data( Dictionary::GetAdminWord(178), "100%", "");
echo admin_func_right_table_data( Dictionary::GetAdminWord(478), "", "");
echo admin_func_right_table_data( Dictionary::GetAdminWord(350), "", "");
echo admin_func_right_table_data( Dictionary::GetAdminWord(479), "", "");
echo admin_func_right_table_data( "<img src=/admin/g/v.gif width=11 height=10 border=0>", $width, $type);


    while(count($array_category_ids)) {
        $get_product_category['id'] = @array_shift($array_category_ids);
        $get_product_category['title'] = @array_shift($array_product_categories);
        $get_product_category['place'] = @array_shift($array_product_category_places);

        $select_fm = DB::Query("select count(*)+0 from `?_product_category` where category_id='$get_product_category[id]' ");
        $sub_product_categories = @array_shift(DB::GetArray($select_fm));

        echo  admin_func_right_table_row_start(2);
        echo "<td width=\"90%\">";
        if($sub_product_categories > 0){
            echo admin_func_right_link(
                "<span style=\"text-decoration:none;\">
                    <img src=/admin/g/f2.gif width=12 height=9 border=0> &nbsp;
                </span>
                $get_product_category[title]"
                , "catalogue.php?item_id=$get_product_category[id]"
                , ""
                , "");
        } else {
            echo "<img src=/admin/g/f.gif width=15 height=9 border=0> $get_product_category[title]";
        }

      // языки
      echo "<td>";
      $langs_array = "";
      @array_pop($langs_array);
      $select_c = DB::Query("select lang_id from `?_product_category` where id={$get_product_category['id']} order by lang_id ");
      while($get_c = DB::GetArray($select_c))  {
        $select_l = DB::Query("select title_short from `?_lang` where id={$get_c['lang_id']} ");
        $langs_array[] = @array_shift(DB::GetArray($select_l));
      }
      echo "<nobr style=\"font-size:10px;\">". @implode(", ", $langs_array) ."</nobr>";
      echo '</td>';

        $anch = "<a onClick='open_popupWMP(\"category_add.php?category_id={$get_product_category['id']}\"); return false;' href='category_add.php?category_id={$get_product_category['id']}' style='text-decoration:none;' >
                    <img src=\"/admin/g/n.gif\" border=0 alt=\"". Dictionary::GetAdminWord(888) ."\" width=11 height=12>
                 </a>";
        echo admin_func_right_table_data( "&nbsp;" . $anch ."&nbsp;|&nbsp;"
            . admin_func_right_link( "<img src=\"/admin/g/e.gif\" border=0 alt=\""
                                            . Dictionary::GetAdminWord(371)
                                            ."\" width=10 height=12>"
                                        , "category_edit.php?id=$get_product_category[id]", "", "")
            ."&nbsp;|&nbsp; "
            . admin_func_right_link( "<img src=\"/admin/g/d.gif\" border=0 alt=\""
                                            . Dictionary::GetAdminWord(354)
                                            ."\"  onclick=\"return confirm('"
                                            . Dictionary::GetAdminWord(1172)
                                            ."')\" width=11 height=12>"
                                       , "catalogue.php?item_id=$category_id&id=$get_product_category[id]&delete=yes&delType=category"
                                       , ""
                                       , "")
            ."&nbsp;"
            , ""
            , 5
        );

        echo "<td><nobr>";
        if($get_product_category['place'] > 1)
            echo admin_func_right_link(
                "<img src=\"/admin/g/1.gif\" border=0 alt=\"вверх\" width=11 height=12 hspace=\"5\">"
                , "catalogue.php?item_id=$category_id&id=$get_product_category[id]&move=up"
                , ""
                , "");
        else
            echo "<div align=right>";
        if($get_product_category['place'] < $max_place)
            echo admin_func_right_link(
                "<img src=\"/admin/g/2.gif\" border=0 alt=\"вниз\" width=11 height=12 hspace=\"5\">"
                , "catalogue.php?item_id=$category_id&id=$get_product_category[id]&move=down"
                , ""
                , "");
        else echo "&nbsp;";
        echo "</nobr>";

        echo admin_func_right_table_data( admin_func_right_input( "checkbox", "ids[]", $get_product_category['id'], "", 3), "", 2);
    }

    echo admin_func_right_table_end();
