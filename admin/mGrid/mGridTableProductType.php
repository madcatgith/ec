<?

$GridTitle = "Редактор слайдера";
$from      = array(
    "table" => "product_type"
    , "as" => "etm"
    , "lang" => "1"
    , "style" => "width:100%"
    , "nonlang_field" => array(
        "id" => array(
            "title"      => "id",
            "style"      => "width: 40px;",
            "tablestyle" => "width: 40px;",
            "colType"    => "lbl"
        )
        , "image" => array(
            "title"       => "Изображение (ширина - 35)",
            "imagesizing" => array('height', 48, 'width', 48),
            "tablestyle"  => "width: 50px; ",
            "colType"     => "image"
        )
    )
    , "multylang_field" => array(
            "title" => array(
                "title"       => "Выводимое название",
                "colType"     => "text",
                "description" => "Всегда выводимый краткий тект. 'Lady Gaga 4ever'"
            )
    )
    , "row_seq"  => array("id", "image", "title")
);
