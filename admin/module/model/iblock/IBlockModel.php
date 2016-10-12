<?php

namespace IBlock;

class IBlockModel extends \AModel {
    
    public function findInMenu($isJson = true) {
        
        $db = \Registry::get('db');
        
        $menu = array();
        $content = array();
        $iblockFind = array();
        
        $query = $db->query('select id from ?_menu where active = 1 and cnc!=""');
        foreach($query->fetchAll() as $row) {
            $menu[] = $row['id'];
        }
        
        $menu = array_unique($menu);
        
        $query = $db->query('select id, menu_id, text from ?_content where active = 1 and lang_id = 1 and menu_id in ('. implode(', ', $menu) .')');
        foreach($query->fetchAll() as $row) 
        {
            $iblocks = array();
            
            preg_match_all('/\[iblock:(.*?)\]/', $row['text'], $iblocks);
            if(count($iblocks[1])) {
                foreach($iblocks[1] as $iblockController) {
                    $iblocksParts = explode('/', $iblockController);
                    if($iblocksParts[1] === 'main' && \IBlocks::tableHasAlias($iblocksParts[0])) {
                        $iblock = new \IBlock($iblocksParts[0], 1);
                        foreach($iblock->getList() as $item) {
                            $iblockFind[$row['menu_id']][$iblocksParts[0] . '/' . $item->get('id')] = array(
                                'id' => $iblocksParts[0] . '/' . $item->get('id'),
                                'title' => \IBlocks::getLabel($iblocksParts[0]) . '/' . $item->get('title')
                            );    
                        }
                    }
                }
            }    
        }
        
        return $isJson ? json_encode($iblockFind) : $iblockFind;
    }
}
