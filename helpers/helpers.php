<?php

require_once BASEPATH . 'helpers/AException.php';
require_once BASEPATH . 'helpers/Singleton.php';
require_once BASEPATH . 'helpers/Registry.php';
require_once BASEPATH . 'helpers/Controller.php';
require_once BASEPATH . 'helpers/fn.php';
require_once BASEPATH . 'helpers/math.php';
require_once BASEPATH . 'helpers/Template.php';
require_once BASEPATH . 'helpers/File.php';
require_once BASEPATH . 'helpers/DB/DB.class.php';
require_once BASEPATH . 'helpers/DB/Dictionary.class.php';
require_once BASEPATH . 'helpers/Date/Date.class.php';
require_once BASEPATH . 'helpers/Modules/Modules.class.php';
require_once BASEPATH . "helpers/html_echo.php";
require_once BASEPATH . "helpers/Mailer/Mailer.class.php";
require_once BASEPATH . "helpers/Mobile_Detect.php";

require_once dirname(__FILE__) . "/DelayFunctions.php";
require_once dirname(__FILE__) . "/ManagedCache.php";
require_once dirname(__FILE__) . "/Curl.php";

class DefaultArrayObject extends ArrayObject
{
    public function get($index, $default = null)
    {
        if ($this->offsetExists($index) && parent::offsetGet($index)) {
            return parent::offsetGet($index);
        } else {
            return $default;
        }
    }
}

class DOMDocumentExtended extends DOMDocument {
    public function __construct($version = "1.0", $encoding = "UTF-8") {
        parent::__construct($version, $encoding);
        $this->registerNodeClass("DOMElement", "DOMElementExtended");
    }
}

class DOMElementExtended extends DOMElement {
    public function insertAfter($targetNode) {
        if ($targetNode->nextSibling) {
            $targetNode->parentNode->insertBefore($this, $targetNode->nextSibling);
        } else {
            $targetNode->parentNode->appendChild($this);
        }
    }

    public function wrapAround(DOMNodeList $nodeList) {
        while (($node = $nodeList->item(0)) !== NULL) {
            $this->appendChild($node);
        }
    }
}