<?


include $_SERVER['DOCUMENT_ROOT'] . "/config.php";
include BASEPATH . '/admin/lib/wmpGallery/wmpGallery.php';


$parent = isset($_REQUEST['parentID']) ? $_REQUEST['parentID'] : 1;

if (isset($_GET['name'])) {
    $gallery_name = $_GET['name'];
}

if (isset($_POST['name'])) {
    $gallery_name = $_POST['name'];
}

if (isset($_POST['ids'])) {
    $gallery_id = $_POST['ids'];
}

if (isset($_REQUEST['holder'])) {
    $holder = $_REQUEST['holder'];
} else {
    $holder = 'input[name=galleryID]';
}

// перевод название в нормальную кодировку
$temp_name    = $gallery_name;
$gallery_name = ($temp_name) ? $temp_name : $gallery_name;

// Если галереи нет то создаём если есть то передаём её номер
if ((int) $gallery_id == 0) {

    $result  = DB::Query("INSERT INTO ?_gallery_category (title, type, parent) values ('{$gallery_name}', 2, '{$parent}')");
    $last_id = mysql_insert_id();

    ?>
    <script type="text/javascript">
        $('<?= $holder; ?>').val('<?= $last_id; ?>');
    </script>	
    Галерея успешна создана!
    <?
} else {
    $last_id = $gallery_id;
}

$parent_cat = $last_id;
$gallery    = new JQGallery($parent_cat);

echo $gallery->show('aGallery');

?>
<script type="text/javascript">

    $('#photogalary1, #galleryEditHolder').center();

    $(window).bind("resize", function()
    {
        $('#photogalary1, #galleryEditHolder').center();
    }).bind("scroll", function()
    {
        $('#photogalary1, #galleryEditHolder').center();
    });
</script>