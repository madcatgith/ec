<?php
  
class JQGallery
{
	
	private $_ID = null;
	
	public function __construct($ID = 0)
	{
		$this->_ID = $ID;
	}
	
	public function show($tplID = 'gallery')
	{

		$tpl     = new Template;
		$gallery = array();

		$galleryQuery = DB::Query('select gl.*, g.* 
			from 
				?_gallery g
				left join ?_gallery_lang gl on g.id=gl.id and gl.lang_id=' . Lang::getID() . '
			where g.category_id=' . $this->_ID . '
			order by g.place
		');		
		
		while ($image = DB::GetArray($galleryQuery))
			$gallery[] = $image;
			
		$tpl->assign('ID', $this->_ID);
		$tpl->assign('gallery', $gallery);
		
		return $tpl->fetch(BASEPATH . 'admin/lib/wmpGallery/' . $tplID . '.tpl');
	}

}