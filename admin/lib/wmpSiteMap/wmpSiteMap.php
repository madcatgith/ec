<?

function buildMenuCnc($lang, $key)
{

    global $globalMenuArray;

    if (isset($globalMenuArray[$lang][$key])) {
        return buildMenuCnc($lang, $globalMenuArray[$lang][$key]["menu_id"]) . urlencode($globalMenuArray[$lang][$key]["cnc"]) . "-";
    } else {
        return "";
    }

}

function smGenerator()
{

    global $globalMenuArray;

    $totalLink   = 0;
    $productLink = 0;
    $contentLink = 0;
    $langArray   = array();
    $langQuery   = DB::Query("select id, http_accept_language cnc from ?_lang where active=1 and http_accept_language!=''");

    while ($get = DB::GetArray($langQuery))
        $langArray[$get["id"]] = $get["cnc"];

    $menuQuery = DB::Query("select sql_calc_found_rows id, lang_id, menu_id, cnc from ?_menu where active=1 and lang_id in (" . implode(',', array_keys($langArray)) . ") and cnc!=''");
	$check     = array_shift(DB::GetArray(DB::Query("select FOUND_ROWS()")));

    if ($check == 0)
        return false;

    while ($get = DB::GetArray($menuQuery))
        $globalMenuArray[$get["lang_id"]][$get["id"]] = array(
	        "id"        => $get["id"]
	        , "cnc"     => $get["cnc"]
	        , "menu_id" => $get["menu_id"]
        );

    $smArray = array();

    $host = $_SERVER["SERVER_NAME"];
    $host = "www." . str_replace("www.", "", $host);

    foreach ($globalMenuArray as $lang => $menuArray)
        foreach ($menuArray as $key => $menu)
            $smArray[$lang][$key] = "http://" . $host . "/" . $langArray[$lang] . "/" . buildMenuCnc($lang, $menu["menu_id"]) . urlencode($menu["cnc"]) . "/";

    $globalMenuArray = array();
    $contentQuery    = DB::Query("select SQL_CALC_FOUND_ROWS
    c.menu_id menu_id
    , c.lang_id lang_id
    , c.id c_id
    , c.cnc c_cnc
    , c.imgurl c_imgurl
    , p.cnc p_cnc
    , p.url p_url
    from  ?_content c
    left join ?_product p on p.lang_id=c.lang_id and p.active=1 and p.menu_id=c.id
    where c.active=1 order by c.lang_id, c.id, p.lang_id");
    
    $check = array_shift(DB::GetArray(DB::Query("select FOUND_ROWS()")));
    $sPath = "/sitemap.xml";
    $file  = $_SERVER["DOCUMENT_ROOT"] . $sPath;
    $fFile = fopen($file, "w+");

    if (flock($fFile, LOCK_EX) == false)
        return false;

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n\txmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\">\n";

    fwrite($fFile, $xml);

    if ($check) {

        $contentArray = array();

        $i         = 0;
        $lang      = 0;
        $lContent  = 0;
        $xml = "";

        while ($get = DB::GetArray($contentQuery)) {

            if ($i == 400) {
                fwrite($fFile, $xml);
                $xml = "";
            }

            if ($lang != $get["lang_id"]) {
                $lang     = $get["lang_id"];
                $lContent = 0;
            }

            if ($lContent != $get["c_id"])
                $lContent = $get["c_id"];

            $img = "";

            if ($get["c_imgurl"] != "" && $get["c_imgurl"] != "http://") {

                $explode     = explode("/", $get["c_imgurl"]);
                $c           = count($explode) - 1;
                $explode[$c] = urlencode($explode[$c]);
                $img         = implode("/", $explode);

            } else if ($get["p_url"] != "" && $get["p_url"] != "http://") {

                    $explode     = explode("/", $get["p_url"]);
                    $c           = count($explode) - 1;
                    $explode[$c] = urlencode($explode[$c]);
                    $img         = implode("/", $explode);

                }

                if ($img != "")
                	$img = "\t\t<image:image>\n\t\t\t<image:loc>" . "http://" . $_SERVER["SERVER_NAME"] . $img . "</image:loc>\n\t\t</image:image>\n";

            if (isset($smArray[$lang][$get["menu_id"]])) {
                if ($get["p_cnc"] != "") {
                    $xml .= "\t<url>\n\t\t<loc>" . $smArray[$lang][$get["menu_id"]] . urlencode($get["p_cnc"]) . "</loc>\n{$img}\t</url>\n";
                    $totalLink++;
                    $productLink++;
                } else if($get["c_cnc"] != "") {
                        $xml .= "\t<url>\n\t\t<loc>" . $smArray[$lang][$get["menu_id"]] . urlencode($get["c_cnc"]) . ".html</loc>\n{$img}\t</url>\n";
                        $totalLink++;
                        $contentLink++;
                    } else {
                        $xml .= "\t<url>\n\t\t<loc>" . $smArray[$lang][$get["menu_id"]] . "</loc>\n\t</url>\n";
                        $totalLink++;
                }
            }
            
            ++$i;

        }
        
        fwrite($fFile, $xml);
        
        $xml = "";
        
    } else {
        
        $xml = "";
        
        foreach ($smArray as $lang => $menuArray)
            foreach ($menuArray as $key => $cnc) {
                $xml .= "\t\t<url>\n\t\t\t<loc>{$cnc}</loc>\n\t\t</url>\n";
                $totalLink++;
            }
        
        fwrite($fFile, $xml);

    }

    $xml = "</urlset>";

    fwrite($fFile, $xml);
    fclose($fFile);
    
    return array(
	    "totalLink"     => $totalLink
	    , "productLink" => $productLink
	    , "contentLink" => $contentLink
	    , "fullPath"    => $file
	    , "smallPath"   => $sPath
    );

}