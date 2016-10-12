<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

# класс продукт
include dirname(__FILE__) . '/wmpProduct.php';

/**
 * Класс каталог продуктов  
 */
class ProductCatalogue
{
    protected $_langID       = 1;
    protected $_page         = 1;
    protected $_onPage       = 0;
    protected $_rowCount     = 0;
    protected $_tpl          = 1;
    protected $_filter       = '';
    protected $_filterOrder  = 'p.place asc';
    protected $_filterBefore = '';
    protected $_tableBefore  = '';
    protected $_prepare      = null;
    protected $_htmlClass    = '';
    protected static $_filtersOrder = array(
		'isNew' => array(
            'order' => 'p.isNew desc',
            'dic' => 104
        ),
        'priceAsc' => array(
            'order' => 'p.price asc',
            'dic' => 70
        ), 
        'priceDesc' => array(
            'order' => 'p.price desc',
            'dic' => 71            
        ), 
        'rating' => array(
            'order' => 'p.popularity desc, p.id asc',
            'dic' => 72
        )
    );

    protected static $_types = array(1 => 'Point', 2 => 'Blue');

    public function __construct($langID)
    {
        $this->_langID = intval($langID);
    }

    public function setPage($page)
    {
        $this->_page = (intval($page) ? $page : 1);
        return $this;
    }

    public function setOnPage($onPage)
    {
        $this->_onPage = intval($onPage);
        return $this;
    }

    public function setTpl($tpl)
    {
        $this->_tpl = strval($tpl);
        return $this;
    }

    public function addFilter($filter)
    {
        $this->_filter .= ' and ' . strval($filter);
        return $this;
    }

    public function addTableBefore($table)
    {
        $this->_tableBefore .= strval($table) . ',';
        return $this;
    }

    public function addFilterBefore($filter)
    {
        $this->_filterBefore .= strval($filter) . ' and ';
        return $this;
    }

    public function getRowCount()
    {
        return $this->_rowCount;
    }

    public function getPage()
    {
        return $this->_page;
    }

    public function getOnPage()
    {
        return $this->_onPage;
    }

    public function setPrepare($prepare)
    {

        if (is_callable($prepare)) {
            $this->_prepare = $prepare;
        }

        return $this;
    }
    
    public function setHtmlClass($class)
    {
        $this->_htmlClass = $class;
        return $this;
    }
    
    public function getHtmlClass() {
        return $this->_htmlClass;
    }

    public function getProduct($productID)
    {

        $product = Registry::get('db')->query('select
                p.*,
                if(p.new_price > 0, p.new_price, p.price) realPrice
            from ?_product p
            where
                p.id=' . intval($productID) . '
                    and 
                p.active=1
                    and
                p.lang_id=' . $this->_langID . ' limit 1')->fetch();

        return (new Product($product));
    }

    public function getProducts()
    {         
        if ($this->_onPage) {
            $limit = 'limit ' . (($this->_page - 1) * $this->_onPage) . ', ' . $this->_on;
        } else {
            $limit = '';
        }

        $products = array();
        $query    = Registry::get('db')->query('select sql_calc_found_rows
                p.*,
                if(p.new_price > 0, p.new_price, p.price) realPrice
            from ?_product p
            where
                p.active=1 ' . $this->_filter . '
                    and
                p.lang_id=' . $this->_langID . ' order by ' . $this->_filterOrder . ' ' . $limit);

        $this->_rowCount = Registry::get('db')->query('select found_rows()')->fetch(PDO::FETCH_COLUMN);

        foreach ($query->fetchAll() as $row) {
            $products[] = new Product($row);
        }

        return $products;
    }

    public function displayProduct($productID)
    {
        echo $this->showProduct($productID);
    }

    public function showProduct($productID)
    {
        $tpl = new Template;

        $tpl->assign('product', $this->getProduct($productID));
        $tpl->assign('langID', $this->_langID);

        return $tpl->fetch(__DIR__ . '/' . __FUNCTION__ . '_' . $this->_tpl . '.tpl');
    }

    public function displayProducts()
    {
        echo $this->showProducts();
    }

    public function showProducts()
    {
        $tpl       = new Template;
        $products = $this->getProducts();

        $tpl->assign('langID', $this->_langID);
        $tpl->assign('instance', $this);
        $tpl->assign('htmlClass', $this->_htmlClass);
		
        if (is_callable($this->_prepare) === true) {
            $tpl->assign('products', call_user_func($this->_prepare, $products, $tpl));
        } else {
            $tpl->assign('products', $products);
        }

        return $tpl->fetch(__DIR__ . '/' . __FUNCTION__ . '_' . $this->_tpl . '.tpl');
    }

    public static function isExist($langID, $ID)
    {
        $stm = Registry::get('db')->prepare('select id from ?_product where lang_id = :langID and id = :ID limit 1');
        $stm->bindValue(':langID', $langID, PDO::PARAM_INT);
        $stm->bindValue(':ID', $ID, PDO::PARAM_INT);
        
        if($stm->execute() && intval($stm->fetch(PDO::FETCH_COLUMN))) {           
            return true;
        }
        
        return false;
    }
    
    public function setFilterOrder($order = '') 
    {        
        if(empty($order) && !empty($_SESSION['CATALOG_ORDER'])) {
            $order = $_SESSION['CATALOG_ORDER'];
        }
        
        if(!empty($order) && !empty(self::$_filtersOrder[$order])) {
            $this->_filterOrder = self::$_filtersOrder[$order]['order'];
            $_SESSION['CATALOG_ORDER'] = $order;
        }
        
        return $this;
    }
    
    public static function getFilterOrder()
    {
        $tpl = new Template;
        $tpl->assign('current', !empty($_SESSION['CATALOG_ORDER']) ? $_SESSION['CATALOG_ORDER'] : '');
        $tpl->assign('orders', self::$_filtersOrder);
        return $tpl->fetch(dirname(__FILE__) . '/' . __FUNCTION__ . '.tpl');
    }
    
    public static function search($search) 
    {
        $catalog = new ProductCatalogue(Lang::getID());
        return $catalog
            ->setTpl('steps')
            ->setFilterOrder(filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING))
            ->addFilter('(p.title) like "%'. $search .'%"')
            ->showProducts();
    }
    
    public static function getTypes() 
    {
        return self::$_types;
    }
}
