<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page 
{
    public function display($tplID)
    {
        echo $this->show($tplID);
    }

    public function show($tplID = 'default')
    {
        $tpl     = new Template;
        $langID  = Lang::getID();
        $logoUrl = Url::setUrl(array('lang' => Lang::getID()));

        $tpl->assign(array(
            'postal'      => Config::get('postal'),
            'siteTitle'   => Config::get('title'),
            'aboveMenu'   => Config::get('above_menu'),
            'text'        => Config::get('text'),
            'copyright'   => Config::get('copyright'),
            'phones'   	  => unserialize(Config::get('phone')),
            'phoneOne'    => Config::get('phone_one'),
            'publicEmail' => Config::get('publicEmail'),
            'logoUrl'     => $logoUrl,
            'logo'        => Config::get('logo'),
            'contentID'   => Url::get('contentID'),
            'menuID'      => Url::get('menuID'),
            'langID'      => $langID,
            'page'        => new Page,
            'noCache'	  => intval(microtime(true) * 10000)
        ));

        return $tpl->fetch('lib/wmpPage/page_' . $tplID . '.tpl');
    }
}
