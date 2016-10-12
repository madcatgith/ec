<?php

class Buffer extends Singleton {

    protected static $_instance = null;

    public function obStart() {
        ob_start();
    }

    public function obFlush() {
        ob_flush();
    }

    public function obEndFlush() {
        ob_end_flush();
    }

    public function obGetContents() {
        return ob_get_contents();
    }
    
    public function obEndClean() {
        ob_end_clean();
    }
}
