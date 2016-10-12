<?php

class Product
{
    protected $_storage = array();
    
    public function get($key) {
        return $this->has($key) ? $this->_storage[$key] : null;
    }
    
    public function has($key) {
        return (bool) isset($this->_storage[$key]) && !empty($this->_storage[$key]) ? 1 : 0;
    }
    
    public function set($key, $value, $type = 1) {
        switch($type) {
            case 1: 
                $this->_storage[$key] = $value;
                break;
            case 2:
                $this->_storage[$key][] = $value;
        }
    }
    
    public function getColorAttribute($index = 0, $attr = '')
    {
        return !empty($this->_storage['colors'][$index][$attr]) ? $this->_storage['colors'][$index][$attr] : null;
    }
    
    public function getDigitForeach() {
        
        if ($this->get('price_for_digit')) {
            if (in_array($this->_categoryID, Menu::getChildrenIDs($this->_langID, 15)) && $this->get('price_for_digit') == 1) {
                return 50;
            } else {
                return $this->get('price_for_digit');
            }
        }
            
        return 1;
    }
    
    /*
    public function __call($name, $arguments) {
        if($name === 'get') {
            
        }
    }
    */
    protected $_ID           = null;
    protected $_langID       = null;
    protected $_title        = null;
    protected $_categoryID   = null;
    protected $_image        = null;
    protected $_image_2      = null;
    protected $_price        = null;
    protected $_newPrice     = null;
    protected $_realPrice    = null;
    protected $_url          = null;
    protected $_fieldName    = array();
    protected $_fieldValue   = array();
    protected $_description  = null;
    protected $_useText      = null;
    protected $_galleryID    = null;
    protected $_need         = null;
    protected $_announcement = null;
    protected $_isNew        = false;
    protected $_multy        = array();
    protected $_doc_1        = null;
    protected $_doc_2        = null;

    public function getUseText()
    {
        return $this->_useText;
    }

    public function getID()
    {
        return $this->_ID;
    }
    
    public function getLangID()
    {
        return $this->_langID;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getCategoryID()
    {
        return $this->_categoryID;
    }

    public function hasImage()
    {
        return (bool) $this->_image;
    }

    public function hasAnnouncement()
    {
        return (bool) $this->_announcement;
    }

    public function getAnnouncement()
    {
        return $this->_announcement;
    }

    public function hasDescription()
    {
        return (bool) $this->_description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function getImage2()
    {
        return $this->_image_2;
    }

    public function getImage()
    {
        return $this->_image;
    }
    
    public function getPrice($number = 2, $withDoubleZero = true)
    {
        return afterPoint($this->_price, $number, $withDoubleZero);
    }
    
    public function getPriceZero()
    {
        $parts = explode('.', $this->_price);
        if(!empty($parts[1])) {
            return '.' . $parts[1];
        }
        
        return false;
    }

    public function getNewPrice($withDoubleZero = true)
    {
        return afterPoint($this->_newPrice, 2, $withDoubleZero);
    }

    public function getRealPrice($withDoubleZero = true)
    {
        return afterPoint($this->_realPrice, 2, $withDoubleZero);
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function hasFieldProperty($i)
    {
        if (isset($this->_fieldName[$i])) {
            return (bool) mb_strlen($this->_fieldName[$i]);
        } else {
            return false;
        }
    }

    public function getFieldName($i)
    {
        return $this->_fieldName[$i];
    }

    public function getFieldValue($i)
    {
        return $this->_fieldValue[$i];
    }

    public function getGalleryID()
    {
        return $this->_galleryID;
    }

    public function hasNeed()
    {
        return (bool) $this->_need;
    }

    public function getNeed()
    {
        return $this->_need;
    }

    public function isNew()
    {
        return $this->_isNew;
    }
	
    public function hasMulty()
    {
        return (bool) count($this->_multy);
    }

    public function getMulty()
    {
        return $this->_multy;
    }
    
    public function getDoc($id)
    {
        return $this->{'_doc_'. $id};
    }
    
    public function getDocExt($id)
    {
        return pathinfo(getenv('DOCUMENT_ROOT') . $this->{'_doc_'. $id}, PATHINFO_EXTENSION);
    }
    
    public function getDocSize($id)
    {
        return formatBytes(filesize(getenv('DOCUMENT_ROOT') . $this->{'_doc_'. $id}), 0);
    }
	
	public function isMachine(){
		if(in_array($this->_categoryID, Menu::getChildrens($this->_langID, 14))){
			return true;
		}
		else{
			return false;
		}
	}

    function __construct(array $data = array())
    {
        $this->_storage = array_merge($data, $this->_storage);
        $this->_storage['colors'] = strlen($data['colors']) ? unserialize(stripslashes($data['colors'])) : array();
        
        if(is_array($this->_storage['colors'])) {
            $this->_storage['colors'] = array_filter($this->_storage['colors'], function($item) {
                return (bool) (!empty($item['title']));
            });
        }
        
        $this->_ID         = $data['id'];
        $this->_langID     = $data['lang_id'];
        $this->_categoryID = $data['category_id'];
        $this->_title      = $data['title'];
        $this->_price      = $data['price'];
        $this->_newPrice   = $data['new_price'];
        $this->_realPrice  = $data['realPrice'];
        $this->_image      = $data['url'];
        $this->_url        = Url::setUrl(array(
            'lang'    => $data['lang_id'],
            'menu'    => $data['category_id'],
            'product' => $data['cnc']
        ));

        //$this->_useText      = ConvertAltToHtml($data['useText']);
        $this->_description  = ConvertAltToHtml($data['text']);
        $this->_announcement = ConvertAltToHtml($data['announcement']);
		
        $this->_multy = @unserialize(stripslashes($data['multy']));
        
        if(is_array($this->_multy)) {
            $this->_multy = array_filter($this->_multy, function($item) {
                return (!empty($item[0]) && !empty($item[1]));
            });
        }
    }

}
