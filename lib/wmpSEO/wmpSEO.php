<?php

class SEO
{
    private static $_storage = array();

    public static function get($key)
    {
        return isset(self::$_storage[$key]) ? self::$_storage[$key] : '';
    }
    
    public static function addSeo(array $data = array()) {
        self::$_storage = array_merge($data, self::$_storage);
    }

    public static function getSeoTags()
    {
        $db = Registry::get('db');
        
        $SEO                = array();
        $langID             = Lang::getID();
        $template           = $db->query('select * from `?_title_config` where id=1 limit 1')->fetch();
        $title              = $template['viewTitle'];
        $SEO['mainSEO']     = $db->query('select title, SEOTitle, SEOKeywords, SEODescription from `?_config` where lang_id=' . $langID . ' limit 1')->fetch();
        $SEO['title']       = $SEO['mainSEO']['SEOTitle'];
        $SEO['keywords']    = $SEO['mainSEO']['SEOKeywords'];
        $SEO['description'] = $SEO['mainSEO']['SEODescription'];

        if (Url::get('menuID') > 0) {
            $title              = $template['viewTitleMenu'];
            $SEO['menuSEO']     = $db->query('select title, SEOTitle, SEOKeywords, SEODescription from `?_menu` where id=' . intval(Url::get('menuID')) . ' and lang_id=' . $langID . ' limit 1')->fetch();
            $SEO['title']       = $SEO['menuSEO']['SEOTitle'];
            $SEO['keywords']    = $SEO['menuSEO']['SEOKeywords'];
            $SEO['description'] = $SEO['menuSEO']['SEODescription'];
        } else {
            $SEO['menuSEO'] = array(
                'title'    => '',
                'SEOTitle' => ''
            );
        }

        if (Url::get('contentID') > 0) {
            $title              = $template['viewTitleContent'];
            $SEO['contentSEO']  = $db->query('select title, SEOTitle, SEOKeywords, SEODescription from `?_content` where id=' . intval(Url::get('contentID')) . ' and lang_id=' . $langID . ' limit 1')->fetch();
            $SEO['title']       = $SEO['contentSEO']['SEOTitle'];
            $SEO['keywords']    = $SEO['contentSEO']['SEOKeywords'];
            $SEO['description'] = $SEO['contentSEO']['SEODescription'];
        } else {
            $SEO['contentSEO'] = array(
                'title'    => '',
                'SEOTitle' => ''
            );
        }

        if (Url::get('productID') > 0) {
            $title              = $template['viewTitleProduct'];
            $SEO['productSEO']  = $db->query('select title, SEOTitle, SEOKeywords, SEODescription from `?_product` where id=' . intval(Url::get('productID')) . ' and lang_id=' . $langID . ' limit 1')->fetch();
            $SEO['title']       = $SEO['productSEO']['SEOTitle'];
            $SEO['keywords']    = $SEO['productSEO']['SEOKeywords'];
            $SEO['description'] = $SEO['productSEO']['SEODescription'];
        } else {
            $SEO['productSEO'] = array(
                'title'    => '',
                'SEOTitle' => ''
            );
        }
        
        if(isset(self::$_storage['iblock'])) {
            $title              = $template['viewTitleIBlock'];
            $SEO['iblockSEO']   = self::$_storage['iblock'];
            $SEO['title']       = self::$_storage['iblock']['SEOTitle'];
            $SEO['keywords']    = self::$_storage['iblock']['SEOKeywords'];
            $SEO['description'] = self::$_storage['iblock']['SEODescription'];
        } else {
            $SEO['iblockSEO'] = array(
                'title'    => '',
                'SEOTitle' => ''
            );            
        }

        /*
        if (Url::get('tagID') > 0) {
            $title              = $template['viewTitleTag'];
            $SEO['tagSEO']      = DB::GetArray(DB::Query('select title, SEOTitle, SEOKeywords, SEODescription from `?_tag_lang` where id=' . intval(Url::get('tagID')) . ' and lang_id=' . $langID . ' limit 1'));
            $SEO['title']       = $SEO['tagSEO']['SEOTitle'];
            $SEO['keywords']    = $SEO['tagSEO']['SEOKeywords'];
            $SEO['description'] = $SEO['tagSEO']['SEODescription'];
        } else {
            $SEO['tagSEO'] = array(
                'title'    => '',
                'SEOTitle' => ''
            );
        }
        */
        
        $replacement  = array(
            '%s' => $SEO['mainSEO']['SEOTitle'],
            '%m' => $SEO['menuSEO']['SEOTitle'],
            '%c' => $SEO['contentSEO']['SEOTitle'],
            '%p' => $SEO['productSEO']['SEOTitle'],
            '%b' => $SEO['iblockSEO']['SEOTitle']
        );
        
        $SEO['title'] = strtr($title, $replacement);

        $tpl = new Template;

        $tpl->assign('data', $SEO);

        return $tpl->fetch(dirname(__FILE__) . '/seo.tpl');
    }

}
