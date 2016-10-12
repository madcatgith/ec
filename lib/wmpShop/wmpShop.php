<?php

class Shop extends Singleton
{

    protected static $_config      = array();
    protected static $_instance    = null;
    protected static $_cartMenuID  = 29;  // order menuID
    protected static $_countdown   = 1800; // sec
    protected static $_salt        = 'F0,k@#Fon';
    protected $_storage            = array();
    protected $_link               = null;
    protected $_cartID             = null;
    protected $_totalAmount        = 0;
    protected $_cart               = array();
    protected $_cartInfo           = array();
    protected $_info               = array(
        'cartID'  => null,
        'cartStr' => null
    );
    protected static $_paymentType = array(
        1 => 'Банк',
        2 => 'PayPal'
    );
    
    protected static $_isExistCartID = false;


    public function getID() {
        $this->_cartID;
    }

    public static function getConfig($key)
    {
        return (isset(self::$_config[$key]) === true) ? self::$_config[$key] : array();
    }

    public static function hasPaymentType($type)
    {
        return isset(self::$_paymentType[$type]);
    }

    protected function __construct()
    {
        parent::__construct();

        $this->_setLink();
        $this->_restoreCartID();
        $this->_cartID ? $this->_fillCart() : $this->_createCart();
        $this->_cartInfo();
    }

    protected function _cartInfo()
    {
        $this->_cartInfo = Registry::get('db')->query('select * from ?_order_cart where id=' . $this->_cartID)->fetchAll();
    }

    protected function _setLink()
    {
        $this->_link = Url::setUrl(array(
                    'lang' => Lang::getID(),
                    'menu' => self::$_cartMenuID
        ));
    }

    public function getLink()
    {
        return $this->_link;
    }

    protected function _setCartID($cartID)
    {
        $this->_cartID = $cartID;

        Session::getInstance()->setcookie(__CLASS__, $_SESSION[__CLASS__] = array_merge($this->_info, array(
            'cartID'  => $cartID,
            'cartStr' => md5(serialize($cartID) . self::$_salt)
        )));
    }

    protected function _restoreCartID()
    {
        $shopSession = array_merge($this->_info, (array) (isset($_SESSION[__CLASS__]) ? $_SESSION[__CLASS__] : array()));
        $shopCookies = array_merge($this->_info, (array) Session::getInstance()->get(__CLASS__));

        if (intval($shopSession['cartID']) > 0) {
            $this->_cartID = $shopSession['cartID'];
        } else if (intval($shopCookies['cartID']) > 0 && $shopCookies['cartStr'] === md5(serialize($shopCookies['cartID']) . self::$_salt)) {
            $this->_cartID                 = $shopCookies['cartID'];
            $_SESSION[__CLASS__]['cartID'] = $this->_cartID;
        }
    }

    protected function _fillCart()
    {
        $data = Registry::get('db')->query('select * from ?_order_cart_item where cartID = ' . $this->_cartID)->fetchAll();
        $this->_storage = array();

        foreach ($data as $row) {
            $row['info']                    = $row['info'] ? unserialize(stripslashes($row['info'])) : '';
            $this->_storage[$row['itemID']] = $row;
        }
    }

    protected function _clearCart()
    {
        $this->_storage = array();
    }
    
    protected function _clearCartID() 
    {
        Session::getInstance()->unsetcookie(__CLASS__);
        unset($_SESSION[__CLASS__]);
    }

    public function getTotalAmount()
    {
        return $this->_totalAmount;
    }

    public function _createCart()
    {
        Registry::get('db')->exec('insert into ?_order_cart (userID, countdown) values (' . (Registry::get('isLogin') ? Registry::get('auth')->get('id') : 0) . ', now())');
        $this->_setCartID(Registry::get('db')->lastInsertId());
    }

    public function addItem($item = array())
    {
        $catalogue = new ProductCatalogue(Lang::getID());
        $product = $catalogue->getProduct($item['id']);
		
		$product->set('noPrice', ((Url::get('menuID') == 11 && in_array($product->getCategoryID(), Menu::getChildrenIDs($product->getLangID(), 14))) ? true : false));
		
        $fields = array(
            'cartID' => $this->_cartID,
            'itemID' => $item['id'],
            'amount' => $item['amount'],
            'info'   => addslashes(serialize($product))
        );

        Registry::get('db')->exec('insert into ?_order_cart_item (`' . implode('`, `', array_keys($fields)) . '`) values ("' . implode('", "', $fields) . '") ');
        $this->_fillCart();

        return Registry::get('db')->lastInsertId();
    }
    
    public function _resaveItemAmount($ID, $amount) 
    {
        Registry::get('db')->exec('update ?_order_cart_item set amount = "'. intval($amount) .'" where itemID = '. $ID . ' and cartID = '. $this->_cartID);
    }
    
    public function _resaveItemInfo($ID, $info) 
    {
        Registry::get('db')->exec('update ?_order_cart_item set info = "'. addslashes(serialize($info)) .'" where itemID = '. $ID . ' and cartID = '. $this->_cartID);
    }

    public function getItems()
    {
        return $this->_storage;
    }

    public function getItem($ID)
    {
        return $this->_storage[$ID];
    }

    public function removeItem($ID)
    {
        Registry::get('db')->exec('delete from ?_order_cart_item where cartID=' . $this->_cartID . ' and itemID=' . $ID . ' limit 1');
        $this->_fillCart();
    }

    public function removeItems()
    {
        Registry::get('db')->exec('delete from ?_order_cart_item where cartID=' . $this->_cartID);
        $this->_clearCart();
    }

    public function hasItem($ID)
    {
        return isset($this->_storage[$ID]);
    }

    public function hasItems()
    {
        return (bool) count($this->_storage);
    }
    
    public function _getItemsInfo() 
    {       
        $this->_totalAmount = 0;
        foreach ($this->_storage as &$item) {
			if($item['info']->has('noPrice') && $item['info']->get('noPrice')) {
				continue;
			}
            $price_for_digit = $item['info']->get('price_for_digit') ?: 1;
            $this->_totalAmount += $item['summary'] = (float) ((float) ($item['amount'] / $price_for_digit) * $item['info']->getPrice());
        }
    }
    
    public static function checkCartID() 
    {
        $cartID = 0;
        $info = array(
            'cartID'  => null,
            'cartStr' => null
        );
        
        $shopSession = array_merge($info, (array) (isset($_SESSION[__CLASS__]) ? $_SESSION[__CLASS__] : array()));
        $shopCookies = array_merge($info, (array) Session::getInstance()->get(__CLASS__));    
        
        if (intval($shopSession['cartID']) > 0) {
            $cartID = $shopSession['cartID'];
        } else if (intval($shopCookies['cartID']) > 0 && $shopCookies['cartStr'] === md5(serialize($shopCookies['cartID']) . self::$_salt)) {
            $cartID = $shopCookies['cartID'];
        }
        
        self::$_isExistCartID = $cartID;
    }
    
    public static function isExistCartID() 
    {
        return (bool) self::$_isExistCartID;
    }
    
    public static function init() 
    {
        self::checkCartID();
    }
    
    public static function getCartEmpty($tplID = 1) 
    {
        $tpl = new Template;
        return $tpl->fetch(__DIR__ . '/getCart_' . $tplID . '_empty.tpl');    
    }

    public function getCart($tplID = 1)
    {
        if(!$this->hasItems()) {
            return self::getCartEmpty($tplID);
        }
        
        $this->_getItemsInfo();

        $tpl = new Template;
        $tpl->assign('shop', $this);
        $tpl->assign('data', $this->_storage);
        $tpl->assign('link', $this->_link);

        return $tpl->fetch(__DIR__ . '/' . __FUNCTION__ . '_' . $tplID . '.tpl');
    }
    
    public function makeOrder($data)
    {
        $this->_getItemsInfo();
        
        $data = array_merge(array(
            'sessionID'     => session_id(),
            'isOrdered'     => 1,
            'amount'        => $this->_totalAmount,
            'langID'        => Lang::getID()
        ), $data);

        $db = Registry::get('db');
        
        $insert = $db->exec('insert into ?_order (`' . implode('`, `', array_keys($data)) . '`) values ("' . implode('", "', $data) . '")');
        $update = $db->exec('update ?_order_cart set orderID=' . ($orderID = $db->lastInsertId()) . ' where id=' . $this->_cartID);
          
        if ($insert && $update) {
			switch($data['deliveryType']){
				case 1:
					$data['deliveryType'] = Dictionary::GetUniqueWord(106);
					break;
				case 2:
					$data['deliveryType'] = Dictionary::GetUniqueWord(68);
					break;
				case 3:
					$data['deliveryType'] = Dictionary::GetUniqueWord(107);
					break;
			}
            $this->notifyAdmin($orderID, $data);
            
            $this->_clearCart();
            $this->_clearCartID();
            
            return $orderID;
        }
        
        return false;
    }
    
    public function notifyAdmin($orderID, $info)
    {
        $mail = new PHPMailer;
        $tpl = new Template;

        $host = str_replace('www.', '', getenv('HTTP_HOST'));
        
        $subject = 'Новый заказ #' . $orderID . ' (' . $host . ')';
        $mail->CharSet = 'utf-8';
        $mail->Subject = $subject;

        $mail->SetFrom('no-reply@' . $host, $subject);
        foreach (explode(',', Config::get('email')) as $email) {
            $mail->AddAddress($email, $subject);
        }
        
        $tpl->assign('orderID', $orderID);
        $tpl->assign('shop', $this);
        $tpl->assign('data', $this->_storage);
        $tpl->assign('info', $info);
        
        $mail->MsgHTML($tpl->fetch(dirname(__FILE__) . '/'. __FUNCTION__ .'.tpl'));
        $mail->Send();
        
        return false;
    }
    
    public static function routeCart()
    {
        if (self::isExistCartID()) {
            return self::getInstance()->getCart('small');
        } else {
            return self::getCartEmpty('small');
        }
    }
}
