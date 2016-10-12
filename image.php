<?php

define('BASEPATH', getenv('DOCUMENT_ROOT') . '/');

require BASEPATH . 'lib/wmpImage/wmpImage.php';

function imgError()
{
    header('Content-type: image/gif');
    echo file_get_contents(BASEPATH . '/blank.gif');
}

function getStaticHeader($path, $ext = 'png')
{

    $ETAG = md5_file($path);
    $LMT  = filemtime($path);

    header('content-type: image/' . $ext);
    header('cache-control: public, max-age=3600000');
    header('expires: ' . gmdate('D, d M Y H:i:s', time() + 36000000) . ' GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $LMT) . ' GMT');
    header('etag: "' . $ETAG . '"');

    return array('ETAG' => $ETAG, 'LMT' => $LMT);
}

$request = parse_url(getenv('REQUEST_URI'));

if (!isset($request['query'])) {
    imgError();
} else {

    $par   = array();
    $query = Image::mDecrypt($request['query']);

    parse_str($query, $par);

    if (!isset($par['src'])) {
        imgError();
    } else if (file_exists(BASEPATH . $par['src']) == true) {

        $image = new Image(BASEPATH . $par['src']);

        // output path          
        $baseSrc = '/files_small/' . md5_file(BASEPATH . $par['src']) . '_' . preg_replace('([^\w_]+)', '_', $query) . '.' . ( isset($par['ext']) ? $par['ext'] : $image->getExtension());
        $desSrc  = getenv('DOCUMENT_ROOT') . $baseSrc;

        if (file_exists($desSrc) && isset($par['test']) == false) {

            $res = getStaticHeader($desSrc, $image->getExtension());

            if (strtotime(getenv('HTTP_IF_MODIFIED_SINCE')) == $res['LMT'] || trim(getenv('HTTP_IF_NONE_MATCH')) == $res['ETAG']) {
                header('HTTP/1.1 304 Not Modified');
            } else {
                header('Location: http://' . getenv('HTTP_HOST') . $baseSrc);
            }
        } else {

            if (!$image->isImage()) {
                imgError();
            } else {

                $par = array_merge(array(
                    'fn'        => '',
                    'width'     => 0,
                    'height'    => 0,
                    'maxWidth'  => 0,
                    'maxHeight' => 0
                        ), $par);

                if ($par['maxWidth'] || $par['maxHeight']) {
                    if ($par['maxWidth']) {

                        if(!$par['height']) {
                            $height = $image->getHeight();
                            $par['height'] = $height;
                        } else {
                            $height = $par['height'];
                        }
                        
                        $width  = floor($height * $image->getWidth() / $image->getHeight());
                        
                        if ($width > $par['maxWidth']) {
                            $width  = $par['maxWidth'];
                            $height = floor($width * $image->getHeight() / $image->getWidth());
                        }

                        $image = $image->resize($width, $height)->crop($width, $height);
                    }
                } else {
                    if ($par['width'] > 0 && $par['height'] > 0) {

                        $width  = $par['width'];
                        $height = floor($width * $image->getHeight() / $image->getWidth());

                        if ($height < $par['height']) {
                            $height = $par['height'];
                            $width  = floor($height * $image->getWidth() / $image->getHeight());
                        }

                        $image = $image->resize($width, $height)->crop($par['width'], $par['height']);
                    } else if ($par['width'] > 0) {
                        $image = $image->resize($par['width'], floor($par['width'] * $image->getHeight() / $image->getWidth()));
                    } else if ($par['height'] > 0) {
                        $image = $image->resize(floor($par['height'] * $image->getWidth() / $image->getHeight()), $par['height']);
                    }
                }

                if (isset($par['fill'])) {
                    $image = $image->fill(Image::html2rgb($par['fill']));
                }

                if (isset($par['ext'])) {
                    $image->setExtension($par['ext']);
                }
                
                if (isset($par['radius'])) {
                    $image->round($par['radius']);
                }
                
                $image->setAlpha();

                if (isset($par['quality'])) {
                    $image->setQuality($par['quality']);
                }

                if ($image->save($desSrc)) {
                    header('Location: http://' . getenv('HTTP_HOST') . $baseSrc);
                    $image->destroy();
                } else {
                    imgError();
                }
            }
        }
    } else {
        imgError();
    }
}
