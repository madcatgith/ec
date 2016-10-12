<?php

if (isset($_POST["PHPSESSID"])) {
    session_id($_POST["PHPSESSID"]);
} else if (isset($_GET["PHPSESSID"])) {
    session_id($_GET["PHPSESSID"]);
}

header("Content-type: text/plain; charset=utf-8");

include getenv('DOCUMENT_ROOT') . '/config.php';

switch ($_REQUEST['action']) {
    
    case 'changeGalleryTitle':
        foreach ($_POST['items'] as $item) {
            DB::Query("update ?_gallery_category set title='" . mysql_real_escape_string(clearVal($item['title'])) . "' where id=" . intval($item['id']));
        }
        break;
    
    case 'saveLangs':
        foreach ($_REQUEST['titles'] as $val)
            DB::Query('replace into ?_gallery_lang (id, lang_id, title) 
				values 
				("' . intval($_REQUEST['id']) . '", "' . intval($val['id']) . '", "' . mysql_real_escape_string(urldecode($val['title'])) . '")
			');
        break;
    case 'getImageByID':

        $res          = array();
        $galleryQuery = DB::Query('select gl.*, g.* 
			from 
				?_gallery g
				left join ?_gallery_lang gl on g.id=gl.id
			where 
				g.category_id="' . intval($_REQUEST['gallery']) . '" 
				and g.id="' . intval($_REQUEST['id']) . '" 
			');

        while ($i = DB::GetArray($galleryQuery)) {

            if (!isset($res['lang']))
                $res['lang'] = array();

            if ($i['lang_id'] > 0)
                $res['lang'][$i['lang_id']] = array(
                    'title' => urlencode($i['title'])
                );
        }

        echo json_encode($res);

        break;
    case 'sort':
        foreach ($_REQUEST['ids'] as $i)
            DB::Query('update ?_gallery set place=' . intval($i['place']) . ' where category_id=' . intval($_REQUEST['gallery']) . ' and id=' . intval($i['id']));
        break;
    case 'clearBasket':
        DB::Query('delete g.*, gl.* from ?_gallery g 
				left join ?_gallery_lang gl on gl.id=g.id 
			where g.category_id=' . intval($_REQUEST['gallery']) . ' and g.id in (' . mysql_real_escape_string(implode(',', $_REQUEST['ids'])) . ')');
        break;
    case 'addYoutube':

        $res   = array();
        $data  = array();
        $place = DB::GetArray(DB::Query('select max(place) from ?_gallery where category_id=' . intval($_REQUEST['gallery'])));

        if ($place) {
            $place = array_shift($place);
        } else {
            $place = 0;
        }

        parse_str($_POST['data'], $data);

        if ($data['href'] == '') {
            exit(json_encode(array('flag' => false, 'error' => 'Введите ссылку на ролик')));
        }

        $url   = parse_url($data['href']);
        $query = array();

        parse_str($url['query'], $query);

        if ($query['v'] == '') {
            exit(json_encode(array('flag' => false, 'error' => 'Введите ссылку на ролик')));
        }

        $xml = file_get_contents('http://gdata.youtube.com/feeds/api/videos/' . $query['v']);

        $doc                     = new DOMDocument();
        $doc->preserveWhiteSpace = false;

        $doc->loadXML($xml);

        $thumbnails = $doc->getElementsByTagNameNS("*", "thumbnail");
        $thumbnail  = $thumbnails->item(0)->getAttribute("url");
        $url        = 'http://www.youtube.com/embed/' . $query['v'] . '?wmode=transparent';
        $fields     = array(
            'category_id' => intval($_REQUEST['gallery']),
            'href'        => mysql_real_escape_string($thumbnail),
            'active'      => 1,
            'type'        => 1,
            'place'       => ++$place,
            'data'        => mysql_real_escape_string(serialize(array(
                'url' => $url
            )))
        );

        DB::Query('insert into ?_gallery (`' . implode('`, `', array_keys($fields)) . '`) values ("' . implode('", "', $fields) . '")');
        $ID = mysql_insert_id();

        foreach ($data['langs'] as $langID => $values) {

            array_walk($values, function(& $item) {
                $item = mysql_real_escape_string(clearVal($item));
            });

            $values['id']      = $ID;
            $values['lang_id'] = $langID;

            DB::Query('insert into ?_gallery_lang (`' . implode('`, `', array_keys($values)) . '`) values ("' . implode('", "', $values) . '")');
        }

        $res[] = array(
            'thumbs'      => $thumbnail,
            'src'         => $thumbnail,
            'href'        => $thumbnail,
            'title'       => stripslashes(clearVal($data['langs'][1]['title'])),
            'active'      => 1,
            'id'          => $ID,
            'place'       => $place,
            'category_id' => intval($_REQUEST['gallery'])
        );

        echo json_encode($res);

        break;
    case 'getImage':

        $res           = DB::GetArray(DB::Query('select * from ?_gallery where category_id="' . intval($_REQUEST['gallery']) . '" and href like "%' . mysql_real_escape_string(urldecode($_REQUEST['image'])) . '" limit 1'));
        $res['thumbs'] = urlencode(Image::mEncrypt('height=72&fill=ffffff&width=96&ext=jpg&src=' . $res['href']));
        $res['src']    = urlencode(Image::mEncrypt('height=288&src=' . $res['href']));
        $res['query']  = 'select * from ?_gallery where category_id="' . intval($_REQUEST['gallery']) . '" and href like "%' . mysql_real_escape_string(urldecode($_REQUEST['image'])) . '" limit 1';

        echo json_encode($res);

        break;
    case 'addImages':

        $res   = array();
        $place = DB::GetArray(DB::Query('select max(place) from ?_gallery where category_id=' . intval($_REQUEST['gallery'])));

        if ($place)
            $place = array_shift($place);
        else
            $place = 0;

        foreach ($_REQUEST['images'] as $val) {

            DB::Query('insert into ?_gallery (category_id, href, active, place) values ("' . intval($_REQUEST['gallery']) . '", "' . mysql_real_escape_string(urldecode($val)) . '", "1", "' . ++$place . '")');

            $res[] = array(
                'thumbs'      => urlencode(Image::mEncrypt('height=72&fill=ffffff&width=96&ext=jpg&src=' . urldecode($val)))
                , 'src'         => urlencode(Image::mEncrypt('height=288&src=' . urldecode($val)))
                , 'href'        => $val
                , 'title'       => ''
                , 'active'      => 1
                , 'id'          => mysql_insert_id()
                , 'place'       => $place
                , 'category_id' => intval($_REQUEST['gallery'])
            );
        }

        echo json_encode($res);

        break;
    case 'load':

        include BASEPATH . '/admin/lib/wmpUpload.php';

        $upload = new Upload($_FILES['Filedata']);
        $folder = "/files" . (isset($_REQUEST['path']) ? $_REQUEST['path'] : '/gallery') . '/';
        $dir    = getenv('DOCUMENT_ROOT') . $folder;

        if ($upload->setDir($dir) && $upload->upload()) {

            $place = DB::GetArray(DB::Query('select max(place) from ?_gallery where category_id=' . intval($_REQUEST['gallery'])));

            if ($place)
                $place = array_shift($place);
            else
                $place = 0;

            DB::Query('insert into ?_gallery (category_id, href, active, place) values ("' . intval($_REQUEST['gallery']) . '", "' . mysql_real_escape_string($folder . $upload->getName()) . '", "1", "' . ++$place . '")');
        }

        break;
}