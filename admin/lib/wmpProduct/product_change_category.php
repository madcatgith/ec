<?

//$include = @include("admin_top.php");
//if(!$include or $adm_wellcome != "Y") exit;
unset($go_away);

if ($ids == "") {
    $sys_message[] = Dictionary::GetAdminWord(463);
    $go_away       = 1;
} else {



    if ($delete_one != "") {

        $ids = @array_reverse($ids);
        for ($i = 0; $i < count($ids); $i++) {
            $id = $ids[$i];

            $select_product  = DB::Query("select id,title,category_id,place from `?_product where id=$id order by lang_id desc ");
            $get_product     = DB::GetArray($select_product);
            $old_category_id = $get_product[category_id];
            $old_place       = $get_product[place];
            $title           = $get_product[title];

            $update = DB::Query("update `?_product` set place=place-1 where place>$old_place and category_id=$old_category_id ");

            $delete_product = DB::Query("delete from `?_product` where id='$id' ");
            if (!$delete_product)
                $sys_message[]  = "" . Dictionary::GetAdminWord(464) . " \"$title\"...";
            else
                $sys_message[]  = "" . Dictionary::GetAdminWord(308) . " \"$title\" " . Dictionary::GetAdminWord(456) . "";
            $go_away        = 1;
        }
    }
    else {


        if ($edit_one != "") {

            $ids = @array_reverse($ids);
            for ($i = 0; $i < count($ids); $i++) {
                $id = $ids[$i];

                $select_product  = DB::Query("select id,title,category_id,place from `?_product` where id=$id order by lang_id desc ");
                $get_product     = DB::GetArray($select_product);
                $old_category_id = $get_product['category_id'];
                $old_place       = $get_product['place'];
                $title           = $get_product['title'];

                if ($old_category_id != $category_id) {
                    $place = 1;

                    $update = DB::Query("update `?_product` set place=place-1 where place>$old_place and category_id=$old_category_id ");
                    $update = DB::Query("update `?_product` set place=place+1 where category_id=$category_id ");

                    $edit_product = DB::Query("update `?_product` set category_id='$category_id',place='$place' where id='$id' ");
                }
                else
                    $edit_product  = 1;
                if (!$edit_product)
                    $sys_message[] = "" . Dictionary::GetAdminWord(465) . " \"$title\"...";
                else
                    $sys_message[] = "" . Dictionary::GetAdminWord(466) . " \"$title\" " . Dictionary::GetAdminWord(459) . "";
            }
        }


// Заголовок
        echo admin_func_top(Dictionary::GetAdminWord(467));

        if (count($sys_message) > 0) {
            echo admin_func_right_table_start("");
            echo admin_func_right_table_row_start(2);
            echo "<td bgcolor=ffffff height=30 class=\"mes\">";

            $temp = @implode("</li>\n\r<li>", $sys_message);
            $temp = "<li>" . $temp . "</li>";

            echo $temp;

            echo "</td>";
            echo admin_func_right_table_end();
            exit;
        }

        echo admin_func_right_table_start(7);
        echo "<form method=post action=catalogue.php name=product>";
        echo "$formauthorization";
        echo "<input type=hidden name=edit_one value=1>";

        if ($new_category_id != "") {
            $category_id      = $new_category_id;
            $prev_category_id = $category_id;
        }
        else
            $prev_category_id = "0";

        echo "<input type=hidden name=item_id value=\"$category_id\">";
        echo admin_func_right_table_row_start(1);
        echo admin_func_right_table_data(Dictionary::GetAdminWord(333), "", "9b");
        echo "<td width=100%>";

        $str_ids = "";
        for ($i = 0; $i < count($ids); $i++)
            $str_ids .= "&ids[]=$ids[$i]";

        $dos_category_id = $category_id;
        if ($dos_category_id > 0) {
            do {
                $select_cur_position = DB::Query("select * from `?_product_category` where id=$dos_category_id and lang_id='" . Lang::getID() . "'");
                $get_dos_category    = DB::GetArray($select_cur_position);
                $str_cur_position    = " / <a href=catalogue.php?new_category_id=$get_dos_category[id]" . $str_ids . ">$get_dos_category[title]</a>$str_cur_position";
                $dos_category_id     = $get_dos_category['category_id'];
            } while ($dos_category_id > 0);
        }

        echo "$str_cur_position";

        echo "<br><hr noshade size=1px>";

        $select_categorys = DB::Query("select * from `?_product_category` where category_id=$prev_category_id and lang_id='" . Lang::getID() . "' order by place");

        echo "<table border=1 cellpadding=5 cellspacing=0>";
        if (@mysql_num_rows($select_categorys) != "")
            echo Dictionary::GetAdminWord(248) . "<br><br>";

        while ($get_categorys = DB::GetArray($select_categorys)) {
            echo admin_func_right_table_row_start(1);
            echo "<td><nobr>";
            echo "<a href=catalogue.php?new_category_id=$get_categorys[id]";

            for ($i = 0; $i < count($ids); $i++)
                echo "&ids[]=$ids[$i]";

            echo " style=\"color:black\">$get_categorys[title]</a>";
            echo "</nobr>";
        }

        echo admin_func_right_table_end() . "<br>";
        echo admin_func_right_table_row_start(1);
        echo admin_func_right_table_data(Dictionary::GetAdminWord(468), "", "9b");
        echo "<td class=tdt>";

        $tipa_echo = "";
        for ($i = 0; $i < count($ids); $i++) {
            $select_product = DB::Query("select id,title, category_id,place from `?_product` where id=$ids[$i] order by lang_id ");
            $get_product    = DB::GetArray($select_product);

            $tipa_echo .= "$get_product[title]<br>";
            $tipa_echo .= "<input type=hidden name=ids[] value=\"$ids[$i]\">";
        }

        echo "$tipa_echo";

        echo "</td>";
        echo admin_func_right_table_end() . "<br>";
        echo admin_func_right_input("submit", "", Dictionary::GetAdminWord(462), "", 1);
        echo "</form>";
    }
}