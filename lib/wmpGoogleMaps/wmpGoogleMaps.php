<?php

class GoogleMapsCoord
{

    protected $_lng     = null;
    protected $_lat     = null;
    protected $_address = null;

    public function getAddress()
    {
        return $this->_address;
    }

    public function setAddress($address)
    {
        $this->_address = (string) $address;
        return $this;
    }

    public function setLat($lat)
    {
        $this->_lat = $lat;
        return $this;
    }

    public function getLat()
    {
        return $this->_lat;
    }

    public function setLng($lng)
    {
        $this->_lng = $lng;
        return $this;
    }

    public function getLng()
    {
        return $this->_lng;
    }

}

class GoogleMapsMarker extends GoogleMapsCoord
{

    protected $_url         = '';
    protected $_icon        = '';
    protected $_iconHover   = '';
    protected $_iconShadow  = '';
    protected $_title       = '';
    protected $_description = '';

    public function getUrl()
    {
        return $this->_url;
    }

    public function setUrl($url = '')
    {
        $this->_url = (string) $url;
    }

    public function setIconHover($icon)
    {
        $this->_iconHover = (string) $icon;
    }

    public function setIcon($icon)
    {
        $this->_icon = (string) $icon;
    }

    public function setIconShadow($iconShadow)
    {
        $this->_iconShadow = (string) $iconShadow;
    }

    public function getIconHoverUrl()
    {
        return $this->_iconHover;
    }

    public function getIconHover()
    {
        list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $this->_iconHover);
        return 'new google.maps.MarkerImage("http://' . $_SERVER['HTTP_HOST'] . $this->_iconHover . '", new google.maps.Size(' . $width . ', ' . $height . '),new google.maps.Point(0, 0), new google.maps.Point(' . ($width / 2) . ', ' . $height . '))';
    }

    public function getIconUrl()
    {
        return $this->_icon;
    }

    public function getIcon()
    {
        list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $this->_icon);
        return 'new google.maps.MarkerImage("http://' . $_SERVER['HTTP_HOST'] . $this->_icon . '", new google.maps.Size(' . $width . ', ' . $height . '),new google.maps.Point(0, 0), new google.maps.Point(' . ($width / 2) . ', ' . $height . '))';
    }

    public function getIconShadow()
    {
        return $this->_icon;
    }

    public function setTitle($title)
    {
        $this->_title = (string) $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setDescription($description)
    {
        $this->_description = (string) $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

}

class GoogleMaps extends GoogleMapsCoord
{

    protected $_zoom = 10;
    protected $_markers = array();
    protected $_width = 400;
    protected $_height = 400;
    
    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }
    
    public function getWidth() {
        return $this->_width;
    }
    
    public function getHeight() {
        return $this->_height;
    }
    
    public function setHeight($height) 
    {
        $this->_height = $height;
        return $this;
    }

    public function getZoom()
    {
        return $this->_zoom;
    }

    public function setZoom($zoom)
    {
        $this->_zoom = (int) $zoom;
        return $this;
    }

    public function getMarkers()
    {
        return $this->_markers;
    }

    public function hasMarkers()
    {
        return (bool) count($this->_markers);
    }

    public function addMarker(GoogleMapsMarker $marker)
    {
        $this->_markers[] = $marker;
    }

    public function show($data, $tplID = 1)
    {
        $tpl = new Template;

        $tpl->assign('data', $data);
        $tpl->assign('ID', substr(md5(uniqid()), 0, 3));
        $tpl->assign('gMap', $this);

        return $tpl->fetch(BASEPATH . 'lib/wmpGoogleMaps/map_' . $tplID . '.tpl');
    }

    public static function getInstance($context = array())
    {
        
        $_instance = new self;
        
        if (isset($context['zoom'])) {
            $_instance->setZoom($context['zoom']);
        }

        return $_instance;
    }

}
