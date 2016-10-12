<?php 

class AForm
{

	protected $_htmlField = '';
	protected $_form      = array(
		'method'   => 'post'
		, 'action' => 'javascript:void(0)'
	);
	
	public function setMethod($method = '')
	{
		$this->_form['method'] = (string) $method;
	}
	
	public function setAction($action = '')
	{
		$this->_form['action'] = (string) $action;
	}

	public function addInputText(array $context = array()) 
	{

		$tpl = new Template;
		
		$tpl->assign('el', array_merge(array(
			'style'   => ''
			, 'class' => ''
			, 'value' => ''
			, 'id'    => str_replace(array('[', ']'), array('_', ''), $context['name'])
		), $context));

		$this->_htmlField .= $tpl->fetch('admin/lib/wmpForm/input_text.tpl');

		return $this;

	}
	
	public function addInputCheck(array $context = array()) 
	{

		/*
		$tpl = new Template;

		$tpl->assign('el', array_merge(array(
			'style'   => ''
			, 'class' => ''
			, 'value' => ''
			, 'id'    => str_replace(array('[', ']'), array('_', ''), $context['name'])
		), $context));

		$this->_htmlField .= $tpl->fetch('admin/lib/wmpForm/input_text.tpl');
		*/
		return $this;

	}

	public function display()
	{
	
		$tpl = new Template;

		$tpl->assign('form', $this->_form);
		$tpl->assign('els', $this->_htmlField);

		return $tpl->fetch('admin/lib/wmpForm/form.tpl');

	}
}

// $tpl->assign('lang', Lang::getLanguages());