<?php

/**
 * Description of Orders
 *
 * @author mefest
 */
class Orders
{

    private $_onPage  = 0;
    private $_offset  = 0;
    private $_numRows = 0;
    private $_sort    = 'id';
    private $_sord    = 'desc';
    private $_fields  = array();
    private $_filters = array();

    public function __construct()
    {

        $query = DB::Query('show columns from ?_order');

        while ($r = DB::GetArray($query)) {
            $this->_fields[$r['Field']] = $r;
        }
    }

    public function getSort()
    {
        return $this->_sort;
    }

    public function setSort($sort)
    {
        if (array_key_exists($sort, $this->_fields)) {
            $this->_sort = $sort;
        }
    }

    public function getSord()
    {
        return $this->_sord;
    }

    public function setSord($sord)
    {
        if (in_array($sord, array('asc', 'desc'))) {
            $this->_sord = $sord;
        }
    }

    public function getOnPage()
    {
        return $this->_onPage;
    }

    public function setOnPage($onPage)
    {
        $this->_onPage = intval($onPage);
    }

    public function getOffset()
    {
        return $this->_offset;
    }

    public function setOffset($offset)
    {
        $this->_offset = intval($offset);
    }

    public function getNumRows()
    {
        return $this->_numRows;
    }

    public function addFilter($field, $value, $nav = '')
    {

        if (array_key_exists($field, $this->_fields)) {

            $type = preg_replace('/\([\d,]+\)/usi', '', $this->_fields[$field]['Type']);

            switch ($type) {
                case 'int':
                case 'double':
                case 'tinyint':
                    if ($nav == '') {
                        $this->_filters[] = $field . '=' . $value . '';
                    } else {
                        if ($nav == 'eq') {
                            $this->_filters[] = $field . '=' . $value . '';
                        } else if ($nav == 'gt') {
                            $this->_filters[] = $field . '>' . $value . '';
                        } else if ($nav == 'lt') {
                            $this->_filters[] = $field . '<' . $value . '';
                        }
                    }
                    break;
                case 'datetime':
                    if ($value) {
                        $this->_filters[] = 'DATE_FORMAT(' . $field . ', "%Y-%m-%d")="' . mysql_real_escape_string(clearVal($value)) . '"';
                    }
                    break;
                case 'varchar':
                    $this->_filters[] = $field . ' like "%' . mysql_real_escape_string(clearVal($value)) . '%"';
                    break;
            }
        }
    }

    public function getRows()
    {

        $orders  = array();
        $filters = '1';

        if (count($this->_filters)) {
            $filters = implode(' and ', $this->_filters);
        }

        $query = DB::Query('select 
               sql_calc_found_rows * 
            from 
                ?_order
            where ' . $filters . ' and isDeleted=0
            order by ' . $this->_sort . ' ' . $this->_sord . '
            limit ' . $this->_offset . ', ' . $this->_onPage
        );

        while ($r = DB::GetArray($query)) {
            $r['items']       = array();
            $orders[$r['id']] = $r;
        }

        $query          = DB::GetArray(DB::Query('select found_rows()'));
        $this->_numRows = array_shift($query);
        $query          = DB::Query('select ci.*, c.orderID from 
                ?_order_cart_item ci left join ?_order_cart c on ci.cartID = c.id
            where 
                c.orderID in (' . implode(',', array_keys($orders)) . ') 
            order by ci.id');

        while ($r = DB::GetArray($query)) {
            if (isset($r['orderID'])) {
                $r['info']                                = unserialize($r['info']);
                $orders[$r['orderID']]['items'][$r['id']] = $r;
            }
        }

        return $orders;
    }

}
