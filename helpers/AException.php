<?php

class AException extends Exception {
    
    public function logError() {

        $fp = fopen(BASEPATH . 'files/log.txt', 'ab');
        fwrite($fp, date("m.d.Y H:i:s") . " " . $this->getFile() . " " . $this->getLine() . " " . $this->getMessage() . "\n");
        fclose($fp);

        return false;
    }
}
