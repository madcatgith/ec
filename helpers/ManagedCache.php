<?php

/**
 * Description of ManagedCache
 *
 * @author Антон
 */
class ManagedCache extends Singleton
{
    protected static $_instance = null;
    protected static $_folder = null;
    
    protected function __construct() {
        parent::__construct();
        
        self::$_folder = BASEPATH . 'templates/managed_cache/';
    }

    public function get($function, $data, $langID) 
    {    
        if(empty($function)) {
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
        
        $file   = self::$_folder . $this->filename($function, $data, $langID);
        $result = $this->read($file);

        if(empty($result)) {
            $result = call_user_func_array($function, $data);
            $this->write($file, $function, $result);
        }
        
        return $result;
    }
    
    private function filename($function, $data, $langID)
    {
        $file = md5(serialize($function));
        
        if(!empty($data)) {
            $file .= '_' . md5(serialize($data));
        }
        
        return $file . '_' . $langID . '.php';
    }
    
    private function write($file, $function, $data) 
    {
        $flag = false;
        
        if($handle = fopen($file, 'wb+')) 
        {
            $time = time()+60*60*24*7;
            
            $content = "<?";
            $content .= "\n/*\nCreated: " . date('d-m-Y H:i:s');
            $content .= "\nFunction: " . (is_array($function) ? implode(' ', $function) : $function) . "\n*/";
            $content .= "\n\$limit = {$time};";
            $content .= "\n\$ser_content = '".str_replace("'", "\'", str_replace("\\", "\\\\", serialize($data)))."';";            
            $content .= "\n?>";
        }
        
        if(fwrite($handle, $content)) {
            $flag = true;
        }
        
        fclose($handle);
        
        return $flag;
    }
    
    private function read($file)
    {
        if(file_exists($file)) {
            include $file;
            
            if(strlen($ser_content)) {
                if(!$limit || $limit < time()) {
                    unlink($file);
                } else {
                    return unserialize($ser_content);   
                }
            }
        } 
        
        return false;
    }
}
