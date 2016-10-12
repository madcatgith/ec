<html><head><title>Create a zip archive of project by Kefir.Dev</title></head><body>
<?php

define ('IS_WMP', (int) (in_array(getenv('REMOTE_ADDR'), array('91.197.146.98', '89.252.54.33'))));
define ('APP', getenv('DOCUMENT_ROOT'));

if (!IS_WMP) {
	header('HTTP/1.0 404 Not Found');
	header('Location: http://' . getenv('HTTP_HOST'));
}

if (!is_dir(APP . '/files/backup/')) {
	die('<b>Folder does NOT exist!</b>');
}

function Zip($source, $destination)
{
    if (extension_loaded('zip') === true)
    {
        if (file_exists($source) === true)
        {
            $zip = new ZipArchive();

            if ($zip->open($destination, ZIPARCHIVE::CREATE) === true)
            {   
                $source = realpath($source);

                if (is_dir($source) === true)
                {
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                    foreach ($files as $file)
                    {
						if(strpos($file, 'files_small') === false)
						{
							$file = realpath($file);

							if (is_dir($file) === true)
							{
								$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							}
							else if (is_file($file) === true)
							{
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
							}
						}
                    }
                }
                else if (is_file($source) === true)
                {
                    $zip->addFromString(basename($source), file_get_contents($source));
                }
            }

            return $zip->close();
        }
    }

    return false;
}
?>
<form action="<?=getenv('REQUEST_URI')?>" method="post">
	<input type="hidden" name="create" value="Y" />
	<button type="submit">Create a zip archive</button>
</form>
<?
if (filter_input(INPUT_POST, 'create', FILTER_SANITIZE_STRING) && filter_input(INPUT_POST, 'create', FILTER_SANITIZE_STRING) == 'Y') {
	$link   = '/files/backup/backup_'. str_replace('www.', '', getenv('HTTP_HOST')) .'_'. date('d_m_Y_H_i_s') .'.zip';
	$isZip  = (int) Zip(APP, APP . $link);

	echo '<br />Result: <b>' . ($isZip == 1 ? 'success' : 'error') . '</b>';

	if ($isZip) {
		echo '<br />Link: <b><a href="'. $link .'">'. $link .'</a></b>';
	}
}
?>
</body></html>

