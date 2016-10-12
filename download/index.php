<?php

include getenv('DOCUMENT_ROOT') . '/config.php';

$f = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_STRING);

if(!$f) {
    Url::pageNotFound();
}

$file = getenv('DOCUMENT_ROOT') . base64url_decode($f);

if(!file_exists($file)) {
    Url::pageNotFound();
}

file_force_download($file);
