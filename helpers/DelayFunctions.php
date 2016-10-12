<?php

/**
 * Description of DelayFunctions
 *
 * @author Антон
 */
class DelayFunctions extends Singleton
{
    protected static $_instance = null;
    protected $_storage = array();
    protected $_template = '[DELAY_FUNCTION:{id}]';
    
    public function add($id, $function, $data = array()) 
    {    
        if(empty($id) || empty($function) || !empty($this->_storage[$id])) {
            return false;
        }
        
        if(is_array($function)) {
            if(!class_exists($function[0]) || !method_exists($function[0], $function[1]) || !is_callable($function)) {
                return false;
            }
        } else {
            if(!function_exists($function) || !is_callable($function)) {
                return false;
            }
        }
        
        $id = strtoupper($id);
        $template = str_replace('{id}', $id, $this->_template);
        
        $this->_storage[$id] = array('function' => $function, 'parameters' => $data);
        
        return $template;
    }
    
    public function execute($html)
    {
        if(empty($this->_storage)) {
            return $html;
        }
        
        $matches = array();
        $template = str_replace('{id}', '(.*?)\\', $this->_template);
        preg_match_all('/\\' . $template . '/', $html, $matches);
        
        if(count($matches) && !empty($matches[1])) {
            foreach ($matches[1] as $key => $id) {
                if(isset($this->_storage[$id])) {
                    $result = call_user_func_array($this->_storage[$id]['function'], $this->_storage[$id]['parameters']);
                    $html = str_replace($matches[0][$key], $result, $html);
                }
            }
        }
        
        return $html;
    }
}
