<?php

include("../config.php");
	function HandleError($message) {
		echo $message;
		exit(0);
	}

	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	} else if (isset($_GET["PHPSESSID"])) {
		session_id($_GET["PHPSESSID"]);
	}

	session_start();

    header("Content-type: text/html; charset=utf-8");

	$POST_MAX_SIZE = ini_get('post_max_size');
	$unit = strtoupper(substr($POST_MAX_SIZE, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

	if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
		HandleError(0);
	}

// Settings
	$save_path =  $_SERVER['DOCUMENT_ROOT'] . "/files" . ($_POST['path'] ? $_POST['path'] : '/gallery') . "/";				// The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
	$upload_name = "Filedata";
	$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
	$extension_whitelist = array("jpg", "gif", "png", "jpeg", "doc", "zip", "rar");	// Allowed file extensions
	$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)

// Other variables	

	$MAX_FILENAME_LENGTH = 260;
	$file_name = "";
	$file_extension = "";
	$uploadErrors = array(
        0 => 1,
        1 => 2,
        2 => 3,
        3 => 4,
        4 => 5,
        6 => 6
	);


// Validate the upload
	if (!isset($_FILES[$upload_name])) {
		HandleError(7);
	} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
		HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
	} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
		HandleError(8);
	} else if (!isset($_FILES[$upload_name]['name'])) {
		HandleError(9);
	}
	
	if (isset($_FILES[$upload_name]['name'])&&isset($_POST["gallery"])){

        if ($_POST['uniq']){
            $path_parts = pathinfo($_FILES[$upload_name]['name']);           
            $_FILES[$upload_name]['name'] = $_POST['gallery'].'_'.gmdate("YmdHis",time()).'_'.$path_parts['filename'].'.'.$path_parts['extension'];
        }    
    
		$img_href 	= $_FILES[$upload_name]['name'];
		$id_gallery = $_POST["gallery"];
		$img_href 	= "/files" . ($_POST['path'] ? $_POST['path'] : '/gallery') . "/" . $img_href;

		$id_end_sel = "SELECT id, place FROM`?_gallery` order by `id` DESC limit 0,1";
		$id_end 		= DB::GetArray(DB::Query($id_end_sel));
		$img_id 		= $id_end['id'] + 1;
		$img_place  = $id_end['place'] + 1;
		
		$zapr= "INSERT INTO `?_gallery` (id,category_id,href,place,lang_id) values ('{$img_id}','{$id_gallery}','{$img_href}','{$img_place}','1')";
		$result = DB::Query($zapr);
		$zapr= "INSERT INTO `?_gallery` (id,category_id,href,place,lang_id) values ('{$img_id}','{$id_gallery}','{$img_href}','{$img_place}','2')";
		$result = DB::Query($zapr);
		$zapr= "INSERT INTO `?_gallery` (id,category_id,href,place,lang_id) values ('{$img_id}','{$id_gallery}','{$img_href}','{$img_place}','3')";
		$result = DB::Query($zapr);

	}
// Validate the file size (Warning: the largest files supported by this code is 2GB)
	$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
	if (!$file_size || $file_size > $max_file_size_in_bytes) {
		HandleError(10);
	}
	
	if ($file_size <= 0) {
		HandleError(11);
	}


// Validate file name (for our purposes we'll just remove invalid characters)
	$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
	if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
		HandleError(12);
	}


// Validate that we won't over-write an existing file
	if (file_exists($save_path . $file_name)) {
		HandleError(13);
	}

// Validate file extension
	$path_info = pathinfo($_FILES[$upload_name]['name']);
	$file_extension = $path_info["extension"];
	$is_valid_extension = false;
	foreach ($extension_whitelist as $extension) {
		if (strcasecmp($file_extension, $extension) == 0) {
			$is_valid_extension = true;
			break;
		}
	}
	if (!$is_valid_extension) {
		HandleError(14);
	}

	if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
		HandleError(15);
	}

	exit(0);
