<?

$GridTitle = "Купоны";
$from      = array(
    "table" => "coupon",
    "as" => "cp",
    "nonlang" => true,
    "lang" => "1",
    "style" => "width:100%",
    "nonlang_field" => array(
        "id" => array(
            "title" => "ID",
            "style" => "width: 40px;",
            "tablestyle" => "width: 30px;",
            "colType" => "lbl"
        ),
        "title" => array(
            "title" => "Название скидки",
            "tablestyle" => "width: 110px;",
            "colType" => "text"
        ),
        "code" => array(
            "title" => "Код",
            "tablestyle" => "width: 80px;",
            "colType" => "text"
        ),
        "value" => array(
            "title" => "Скидка",
            "tablestyle" => "width: 80px;",
            "colType" => "text"
        ),
        /*"type" => array(
            "title" => "Код",
            "tablestyle" => "width: 80px;",
            "style" => "width: 95%;",
            "tablestyle" => "width: 60px; white-space:normal;",
            "colType" => "selectLocal",
            "data" => array(
                0 => "% - Проценты ",
                1 => "ГРН"
            )
        ),*/
        "dateFrom" => array(
            "title" => "Дата начала",
            "style" => "width: 100px;",
            "tablestyle" => "width: 120px;",
            "colType" => "date"
        ),
        "limited" => array(
            "title" => "Лимит",
            "tablestyle" => "width: 80px;",
            "colType" => "text"
        ),
        "dateTo" => array(
            "title" => "Дата окончания",
            "style" => "width: 100px;",
            "tablestyle" => "width: 120px;",
            "colType" => "date"
        ),
        "product_category" => array(
            "title"      => "Категории",
            "style"      => "width: 200px;",
            "tablestyle" => "width: 100px; white-space:normal;",
            "table"      => "menu",
            "link_table" => "couponToCategory",
            "linkfield"  => "product_category",
            "field"      => "coupon",
            "outfield"   => "title",
            "multy"      => true,
            "rules"      => true,
            "islanged"   => true,
            "colType"    => "select"
         ),
        "active" => array(
            "title" => "Активный",
             "tablestyle" => "width: 70px;",
             "colType" => "check"
        ),
        "used" => array(
            "title" => "Использован",
            "tablestyle" => "width: 50px;",
            "colType" => "lbl"
        ),
        "orderId" => array(
            "title" => "ID заказа",
            "tablestyle" => "width: 50px;",
            "colType" => "lbl"
        )
    )
    , "row_seq" => array("id", "title", "code", "value", "dateFrom", "dateTo", "orderId", "limited", "used", "active")
);