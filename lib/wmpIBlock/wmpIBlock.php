<?php

include dirname(__FILE__) . '/wmpIBlocks.php';
include dirname(__FILE__) . '/wmpIBlockElement.php';

class IBlock {
  
    protected $_iblockID = null;
    protected $_withoutLang = null;
    protected $_langID = null;
    protected $_table = null;
    protected $_tpl = null;
    protected $_onPage = 0;
    protected $_page = 1;
    protected $_rowCount = 0;
    protected $_where = '';
    protected $_select = '';
	protected $_table_add = '';
	protected $_sort = '';
        protected $_order = ' order by t.sort ';
    protected $_prepare = null;
    protected $_menuID = 0;

    public function setTpl($tpl) {
        $this->_tpl = $tpl;
        return $this;
    }

    public function setPrepare($prepare) {
        if (is_callable($prepare)) {
            $this->_prepare = $prepare;
        }
        return $this;
    }

    public function addWhere($where) {
        $this->_where .= ' and ' . strval($where);
        return $this;
    }
    
    public function addSelect($select) {
        $this->_select .= ', ' . strval($select);
        return $this;
    }
	
	public function addTable($table) {
        $this->_table_add .= ' ' . strval($table);
        return $this;
    }	
	
	public function addSort($sort) {
        $this->_sort .= ' ' . strval($sort);
        return $this;
    }
    
    public function addOrder($order) {
        $this->_order = ' order by t.'. $order;
        return $this;
    }

    public function setMenuID($menuID) {
        $this->_menuID = $menuID;
        return $this;
    }

    public function setPage($page) {
        $this->_page = (intval($page) ? $page : 1);
        return $this;
    }

    public function setOnPage($onPage) {
        $this->_onPage = intval($onPage);
        return $this;
    }

    public function getIBlockID() {
        return $this->_iblockID;
    }

    public function getMenuID() {
        return $this->_menuID;
    }
    
    public function getLangID() {
        return $this->_langID;
    }
    
    public function getPage() {
        return $this->_page;
    }
    
    public function getOnPage() {
        return $this->_onPage;
    }
    
    public function getRowCount() {
        return $this->_rowCount;
    }
    
    public function __construct($iblockID, $langID) {
        $table = IBlocks::getTable($iblockID);
        if($table) {
            $this->_iblockID    = $iblockID;
            $this->_withoutLang = IBlocks::isWithoutLang($iblockID);
            $this->_table       = $table;
        } else {
            throw new AException('Infoblock doesn\'t exist.');
        }            
        $this->_langID = $langID;
    }    

    public function getOne($oneID) {
        
        if($this->_withoutLang) {
            $sql = 'select t.* '. $this->_select .' from ' . $this->_table . ' t where t.active = 1 and t.id = '. intval($oneID) . $this->_where . ' order by t.sort limit 1';
        } else {
            $sql = 'select t.*, t_l.* '. $this->_select .' 
            from 
                ' . $this->_table . ' t
            left join 
                ' . $this->_table . '_lang t_l on t.id = t_l.id and t.id = ' . intval($oneID)
            .  $this->_table_add .' where t.active = 1 and t_l.lang_id = ' . intval($this->_langID) . $this->_where . ' order by t.sort '
            . 'limit 1';
        }      
        
        $data = Registry::get('db')->query($sql)->fetch();

        return new IBlockElement($data, $this);
    }

    public function getList() {
        
        $data = array();
        if ($this->_onPage) {
            $limit = 'limit ' . (($this->_page - 1) * $this->_onPage) . ', ' . $this->_onPage;
        } else {
            $limit = '';
        }
		if($this->_sort){
			$sort = ' ' . $this->_sort . ' ';
		}
		else{
			$sort = ' asc ';
		}
        
        if($this->_withoutLang) {
             $sql = 'select SQL_CALC_FOUND_ROWS t.* '. $this->_select .'
            from 
                ' . $this->_table . ' t
            where t.active = 1' . $this->_where . ' order by t.sort ' . $sort
            . $limit;           
        } else {
            $sql = 'select SQL_CALC_FOUND_ROWS t.*, t_l.* '. $this->_select .'
            from 
                ' . $this->_table . ' t
            left join 
                ' . $this->_table . '_lang t_l on t.id = t_l.id'
				. $this->_table_add .
            ' where t.active = 1 and t_l.lang_id = ' . intval($this->_langID) . $this->_where . $this->_order . $sort 
            . $limit;
        }
        $query = Registry::get('db')->query($sql);

        $this->_rowCount = Registry::get('db')->query('select found_rows()')->fetch(PDO::FETCH_COLUMN);

        foreach($query->fetchAll() as $get) {
            $data[] = new IBlockElement($get, $this);
        }

        return $data;
    }

    public function showList() {
        if (count($data = $this->getList())) {
            $tpl = new Template;
            if (is_callable($this->_prepare) === true) {
                $tpl->assign('data', call_user_func($this->_prepare, $data, $tpl));
            } else {
                $tpl->assign('data', $data);
            }
            $tpl->assign('instance', $this);
            
            return $tpl->fetch(dirname(__FILE__) .'/'. $this->_iblockID .'/'. __FUNCTION__ .'_'. $this->_tpl . '.tpl');
        }
        return false;
    }

    public function showOne($oneID) {
        if (count($data = $this->getOne($oneID))) {
            $tpl = new Template;
			if (is_callable($this->_prepare) === true) {
                $tpl->assign('data', call_user_func($this->_prepare, $data, $tpl));
            } else {
                $tpl->assign('data', $data);
            }
            $tpl->assign('instance', $this);

            return $tpl->fetch(dirname(__FILE__) .'/'. $this->_iblockID .'/'. __FUNCTION__ .'_'. $this->_tpl . '.tpl');
        }

        return false;
    }

    public function displayList() {
        echo $this->showList();
    }

    public function displayOne($oneID) {
        echo $this->showOne($oneID);
    }
}
