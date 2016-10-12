<?php

if (defined("BASEPATH") == false) {
    die();
}

define('SMARTY_SPL_AUTOLOAD', 1);

require_once BASEPATH . "helpers/Smarty/Smarty.class.php";

class Template extends Smarty
{
    public function __construct()
    {
        parent::__construct();

        $this->setTemplateDir(BASEPATH);
        $this->compile_dir = BASEPATH . "/templates/compile";
        $this->cache_dir   = BASEPATH . "/templates/cache";

      // $this->debugging       = false;
      // $this->error_reporting = false;
    }
}
