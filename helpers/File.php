<?php

class File {
    
    const RESIZE_IMAGE_PROPORTIONAL = 1;
    const RESIZE_IMAGE_EXACT = 2;

    protected static $_extensions = array('gif', 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf');
    
    public static function checkExtension($ext) {
        return (bool) in_array($ext, self::$_extensions);        
    }
    
    public static function getUploadError($errorID) {
        switch(intval($errorID)) {
            case 1:
                $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                break;
            case 2:
                $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                break;
            case 3:
                $error = 'The uploaded file was only partially uploaded.';
                break;
            case 4:
                $error = 'No file was uploaded.';
                break;
            case 7:
                $error = 'Failed to write file to disk.';
                break;
            case 8:
                $error = 'A PHP extension stopped the file upload.';
                break;
            default:
                $error = 'File didn\'t download.';
        }
        
        return $error;
    }
    
    public static function getFilePath($filename, $createDir = true) {
        $info = pathinfo($filename);
        $new_filename = transliterate($info['filename']) . '.' . $info['extension'];
        
        $dir = substr(md5($new_filename), 0, 3);
        
        if(file_exists(MEDIA_PATH . $dir .'/') && is_dir(MEDIA_PATH . $dir .'/')) {
            $i = 0;
            while(true) {
                $dir = substr(md5(uniqid("", true)), 0, 3);
                if(!file_exists(MEDIA_PATH . $dir .'/') && !is_dir(MEDIA_PATH . $dir .'/')) {
                    break;
                }
                if($i >= 25) {
                    $dir = substr(md5(mt_rand()), 0, 4); // Это же сколько нужно файлов, чтобы это условие выполнилось //
                    break;
                }
                $i++;
            }
        }
        
        if($createDir && !file_exists(MEDIA_PATH . $dir .'/') && !is_dir(MEDIA_PATH . $dir .'/')) {
            mkdir(MEDIA_PATH . $dir .'/', 0755);
        }
        
        return array(
            'subdir'    => $dir,
            'name'      => $new_filename
        );
    }
    
    public static function getNames($fileID) {
        
        $cacheFile  = md5($fileID) . '.php';
        $cacheFolder = MEDIA_CACHE . substr($cacheFile, 0, 3) .'/';
        
        return array(
            'file' => $cacheFolder . $cacheFile,
            'folder' => $cacheFolder,
            'folderExists' => (int) file_exists($cacheFolder) && is_dir($cacheFolder),
            'fileExists' => (int) file_exists($cacheFolder . $cacheFile)
        );
    }
    
    public static function getByID($fileID, $langID) {
        
        $file = array();
        $info = self::getNames($fileID);
        
        if($info['folderExists'] && $info['fileExists']) {
            $element = self::readCache($fileID);
        } else {
            $db = Registry::get('db');
            $element = $db->query('select * from ?_media_element where id = '. intval($fileID) .' limit 1')->fetch();
            
            if(empty($element)) {
                return $file;
            }
            
            $element = array($element);
            $elementLang = $db->query('select * from ?_media_element_lang where id = '. intval($fileID))->fetchAll();
            
            foreach($elementLang as $eLang) {
                $element[$eLang['langID']] = $eLang;
            }
            
            self::writeCache($element);
        }
        
        if(!empty($element)) {
            if(!empty($element[$langID])) {
                $file = array_merge($element[0], $element[$langID]);
            } else {
                $file = array_merge($element[0], array('title' => '', 'text' => ''));
            }
            $file['src'] = MEDIA . $file['subdir'] . '/' . $file['source'];
        }

        return $file;
    }
    
    public static function getByIDs($fileIDs = array(), $langID) {
        
        $data = array();
        foreach($fileIDs as $fileID) {
            $data[$fileID] = self::getByID($fileID, $langID);
        }

        return array_filter($data);
    }
    
    public static function getPath($fileID) {
        
        $file = self::getByID($fileID, -1);
        if(!empty($file)) {
            return $file['src'];
        }
        
        return false;
    }
    
    public static function resizeImage($fileID, $langID, $sizes = array(), $resizeType = File::RESIZE_IMAGE_PROPORTIONAL, $filters = array()) {
        
        $file = self::getByID($fileID, $langID);

        foreach(array('width', 'height') as $size) {
            if(!isset($sizes[$size]) || intval($sizes[$size]) <= 0) {
                $sizes[$size] = 0;
            }
            $sizes[$size] = intval($sizes[$size]);
        }
        
        if(
            ($sizes['width'] <= 0 || $sizes['width'] >= $file['width']) &&
            ($sizes['height'] <= 0 || $sizes['height'] >= $file['height'])
        )
        {
            if(empty($filters)) {
                return $file;
            } else {
                $sizes['width'] = $file['width'];
                $sizes['height'] = $file['height'];
            }
        }
        
        $resizeDir = 'files/resize_cache/' . $file['subdir'] . '/' . $sizes['width'] . '_' . $sizes['height']
                    . '_' . $resizeType . (!empty($filters) ? md5(serialize($filters)) : '') . '/';
        $resizeFolder = BASEPATH . $resizeDir;
        $resizeFile = $resizeFolder . $file['source'];
        
        if(file_exists($resizeFile)) {
            $file['src'] = $resizeFile;
            return $file;
        } elseif(!file_exists($resizeFolder) && !is_dir($resizeFolder)) {
            mkdir($resizeFolder, 0755, true);
        }
        
        $sourceImageWidth = intval($file['width']);
        $sourceImageHeight = intval($file['height']);
        $bNeedCreatePicture = false;
        $arSourceSize = array("x" => 0, "y" => 0, "width" => 0, "height" => 0);
        $arDestinationSize = array("x" => 0, "y" => 0, "width" => 0, "height" => 0);
        
        if ($sourceImageWidth > 0 && $sourceImageHeight > 0) {
            if ($sizes["width"] > 0 && $sizes["height"] > 0) {
                switch ($resizeType) {
                    case File::RESIZE_IMAGE_EXACT:
                        $bNeedCreatePicture = true;

                        $ratio = (($sourceImageWidth / $sourceImageHeight) < ($sizes["width"] / $sizes["height"])) ?
                                $sizes["width"] / $sourceImageWidth : $sizes["height"] / $sourceImageHeight;

                        $x = max(0, round($sourceImageWidth / 2 - ($sizes["width"] / 2) / $ratio));
                        $y = max(0, round($sourceImageHeight / 2 - ($sizes["height"] / 2) / $ratio));

                        $arDestinationSize["width"] = $sizes["width"];
                        $arDestinationSize["height"] = $sizes["height"];

                        $arSourceSize["x"] = $x;
                        $arSourceSize["y"] = $y;
                        $arSourceSize["width"] = round($sizes["width"] / $ratio, 0);
                        $arSourceSize["height"] = round($sizes["height"] / $ratio, 0);
                        break;
                    case File::RESIZE_IMAGE_PROPORTIONAL:
                        $width = $sourceImageWidth;
                        $height = $sourceImageHeight;

                        $ResizeCoeff["width"] = $sizes["width"] / $width;
                        $ResizeCoeff["height"] = $sizes["height"] / $height;

                        $iResizeCoeff = min($ResizeCoeff["width"], $ResizeCoeff["height"]);
                        $iResizeCoeff = ((0 < $iResizeCoeff) && ($iResizeCoeff < 1) ? $iResizeCoeff : 1);
                        $bNeedCreatePicture = ($iResizeCoeff != 1 ? true : false);

                        $arDestinationSize["width"] = max(1, intval($iResizeCoeff * $sourceImageWidth));
                        $arDestinationSize["height"] = max(1, intval($iResizeCoeff * $sourceImageHeight));

                        $arSourceSize["x"] = 0;
                        $arSourceSize["y"] = 0;
                        $arSourceSize["width"] = $sourceImageWidth;
                        $arSourceSize["height"] = $sourceImageHeight;
                        break;
                }
            } else {
                $arSourceSize = array("x" => 0, "y" => 0, "width" => $sourceImageWidth, "height" => $sourceImageHeight);
                $arDestinationSize = array("x" => 0, "y" => 0, "width" => $sourceImageWidth, "height" => $sourceImageHeight);
            }
            
            $path = MEDIA_PATH . $file['subdir'] . '/' . $file['source'];
            $originalInfo = getimagesize($path);

            if(copy($path, $resizeFile)) { 
                switch ($originalInfo[2]) {
                    case IMAGETYPE_GIF:
                        $sourceImage = imagecreatefromgif($path);
                        break;
                    case IMAGETYPE_PNG:
                        $sourceImage = imagecreatefrompng($path);
                        break;
                    default:
                        $sourceImage = imagecreatefromjpeg($path);
                        break;
                }
                
                if ($bNeedCreatePicture) {
                    $picture = imagecreate($arDestinationSize["width"], $arDestinationSize["height"]);
                    imagecopyresampled($picture, $sourceImage, 0, 0, $arSourceSize["x"], $arSourceSize["y"],
                    $arDestinationSize["width"], $arDestinationSize["height"], $arSourceSize["width"], $arSourceSize["height"]);                    
                } else {
                    $picture = $sourceImage;
                }
                
                switch ($originalInfo[2]) {
                    case IMAGETYPE_GIF:
                        imagegif($picture, $resizeFile);
                        break;
                    case IMAGETYPE_PNG:
                        imagealphablending($picture, false );
                        imagesavealpha($picture, true);
                        imagepng($picture, $resizeFile);
                        break;
                    default:
                        imagejpeg($picture, $resizeFile, 100);
                        break;
                }
                imagedestroy($picture);
                
                $file['width'] = $arDestinationSize["width"];
                $file['height'] = $arDestinationSize["height"];
                $file['src'] = '/' . $resizeDir . $file['source'];
            }
        }
        
        return $file;
    }
    
    public static function readCache($fileID) {
        
        $info = self::getNames($fileID);
        
        if($info['folderExists'] && $info['fileExists']) {
            include $info['file'];
            
            if(strlen($ser_content)) {
                return unserialize($ser_content);
            }
        } 
        
        return array();
    }
    
    public static function writeCache($data = array()) {
        
        $flag = false; 
        $info = self::getNames($data[0]['id']);
        
        if(!$info['folderExists']) {
            mkdir($info['folder'], 0755);
        }
        
        if(file_exists($info['file'])) {
            unlink($info['file']);
        }
        
        if($handle = fopen($info['file'], 'wb+')) 
        {
            $content = "<?";
            $content .= "\n\$ser_content = '".str_replace("'", "\'", str_replace("\\", "\\\\", serialize($data)))."';";            
            $content .= "\n?>";
        }
        
        if(fwrite($handle, $content)) {
            $flag = true;
        }
        
        fclose($handle);
        
        return $flag;
    }
    
    public static function deleteCache($fileID) {
        
        $i = 0;
        $info = self::getNames($fileID);
        
        if($info['fileExists'] && $info['folderExists']) {
            unlink($info['file']);

            if ($handle = opendir($info['folder'])) {
                while (($file = readdir($handle)) !== false) {
                    if (!in_array($file, array('.', '..')) && !is_dir($info['folder'] . $file)) {
                        $i++;
                    }
                }
            }

            if($i === 0) {
                rmdir($info['folder']);
            }
        }
        
        return false;
    }
    
    /*
     * Delete directory recursive (!!!)
    */
    public static function deleteDir($subdir) {
        if(!file_exists($subdir) && !is_dir($subdir)) {
            return false;
        }
        
        if (substr($subdir, strlen($subdir) - 1, 1) != '/') {
            $subdir .= '/';
        }
        
        $files = glob($subdir . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($subdir);
    }
}