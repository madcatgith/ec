<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Form 
{

	public static $Errors     = array();
	public static $Letter     = null;
	public static $Form_info  = null;
	public static $Form_items = null;

	public function GetCombobox($id, $item, $tplID)
	{
	
		$row = $item['row'];

		$tpl_item      = new Template();
		$tpl_name_item = BASEPATH . 'lib/wmpForm/template_combobox_' . $tplID . '.tpl'; 

		$size = (!empty($row['size'])) ? 
		$row['size'] : 3;

		$class = $row['size'];

		$required   = ($row['requred']) ? "true" : "false";
		$validation = ($row['requred'] && $row['validation']) ? 
		','.$row['validation'] : "";

		$all_values   = explode("\n", $row['value'] );
		$all_selected = explode("\n", $row['selected'] );
		
		// тримаем все значения, чтобы убрать странную фичу explode с пробелами и случайные пробелы			
		foreach($all_selected as $key => $val)
			$all_selected[$key] = trim($val);

		switch ($all_values[0]) {
			case 'feedbackField':

				$all_values = array();
				$fQuery     = DB::Query('select fl.id, fl.address
					from 
						?_feedback f
						, ?_feedback_lang fl
					where
						f.active=1 
						and f.id=fl.id 
						and fl.lang_id="' . Lang::getID() . '"
					order by f.place
				');
				
				if (mysql_num_rows($fQuery))
					while ($r = DB::GetArray($fQuery))
						$all_values[$r['id']] = $r['address'];	
						
				$tpl_item->assign('feedbackField', true);        

			break;
		}			
			
		$options = array();
		foreach($all_values as $key => $val) {
			//проверяем, есть ли данный элемент в выбранных значениях                            
			$isSelected    = in_array($val, $all_selected) ? true : false;
			$options[$key] = array(
				'value'      => $val
				, 'selected' => $isSelected
			);
		} 

		$tpl_item->assign('req', $row['requred']);
		
		$tpl_item->assign('id', $id);
		$tpl_item->assign('string', $row["string"]);
		$tpl_item->assign('multy', $row['multiple']);
		$tpl_item->assign('size', $size);

		$tpl_item->assign('hasDescription', (bool) trim($row['description']));
		$tpl_item->assign('description', trim($row['description']));
		
		$tpl_item->assign('required', $required);
		$tpl_item->assign('validation', $validation);        

		$tpl_item->assign('class', $class);        
		$tpl_item->assign('options', $options);                        

		return $tpl_item->fetch($tpl_name_item);

	}

	public function GetRadiobutton($id, $item){
		$row = $item['row'];

		$tpl_item = new Template();
		$tpl_name_item = BASEPATH . 'lib/wmpForm/template_radiobutton_1.tpl'; 

		$size = (!empty($row['size'])) ? 
		$row['size'] : 3;

		$class = $row['size'];

		$required = ($row['requred']) ? "true" : "false";
		$validation = ($row['requred'] && $row['validation']) ? 
		','.$row['validation'] : "";        

		$all_values   = explode("\n", $row['value'] ); 
		$all_selected = explode("\n", $row['selected'] );
		// тримаем все значения, чтобы убрать странную фичу explode с пробелами и случайные пробелы
		foreach($all_selected as $key => $val) {
			$all_selected[$key] = trim($val);
		}

		$options = array();
		foreach($all_values as $key => $val) {
			//проверяем, есть ли данный элемент в выбранных значениях                            
			$isSelected = in_array($val, $all_selected) ? 
			true : false;
			$options[$key] = array(
			'value' => $val
			, 'selected' => $isSelected
			);
		}         

		$tpl_item->assign('id', $id);
		
		$tpl_item->assign('hasDescription', (bool) trim($row['description']));
		$tpl_item->assign('description', trim($row['description']));
		
		$tpl_item->assign('string', $row["string"]);        

		$tpl_item->assign('required', $required);
		$tpl_item->assign('validation', $validation);        

		$tpl_item->assign('multy', $row['multiple']);
		$tpl_item->assign('size', $size);
		$tpl_item->assign('class', $class);        
		$tpl_item->assign('options', $options);                        

		return $tpl_item->fetch($tpl_name_item);        
	}          

	public function GetTextField($id, $item, $tplID = 1)
	{
	
		$row           = $item['row'];
		$tpl_item      = new Template();
		$tpl_name_item = BASEPATH . 'lib/wmpForm/template_textfield_' . $tplID . '.tpl'; 

		$maxlength  = ($row['maxlength']) ? $row['maxlength'] : 250;  
		$class      = $row['size'];
		$required   = ($row['requred']) ? "true" : "false";
		$validation = ($row['requred'] && $row['validation']) ?  ',' . $row['validation'] : '';

		$tpl_item->assign('req', $row['requred']);
		                                                                      
		$tpl_item->assign('id', $id);
		$tpl_item->assign('hasDescription', (bool) trim($row['description']));
		$tpl_item->assign('description', trim($row['description']));
		$tpl_item->assign('string', $row["string"]);
		$tpl_item->assign('maxlength', $maxlength);
		$tpl_item->assign('required', $required);
		$tpl_item->assign('validation', $validation);
		$tpl_item->assign('class', $class);
		$tpl_item->assign('value', $row['value']);

		if ( empty ($row['selected']) )
			$tpl_item->assign('selected', $row['value']);
		else 
			$tpl_item->assign('selected', $row['selected']);

		return $tpl_item->fetch($tpl_name_item);        

	}    

	public function GetCaptcha($id, $item){
		$row = $item['row'];

		$tpl_item = new Template();
		$tpl_name_item = BASEPATH . 'lib/wmpForm/template_captcha_1.tpl'; 

		$tpl_item->assign('id', $id);
		$tpl_item->assign('string', $row["string"]);
		$tpl_item->assign('value', $row['value']);
		$tpl_item->assign('hasDescription', (bool) trim($row['description']));

		return $tpl_item->fetch($tpl_name_item);        
	}

	public function GetFiles($id, $item, $tplID = 1)
	{
		
		$row           = $item['row'];
		$tpl_item      = new Template();
		$tpl_name_item = BASEPATH . 'lib/wmpForm/template_files_' . $tplID . '.tpl'; 

		$maxlength = ($row['maxlength']) ? $row['maxlength'] : 250;  
		$class     = $row['size'];        

		$required   = ($row['requred']) ? "true" : "false";
		$validation = ($row['requred'] && $row['validation']) ? ',' . $row['validation'] : "";

		$tpl_item->assign('req', $row['requred']);
		
		$tpl_item->assign('id', $id);
		$tpl_item->assign('string', $row["string"]);
		$tpl_item->assign('maxlength', $maxlength);    
		
		$tpl_item->assign('hasDescription', (bool) trim($row['description']));
		$tpl_item->assign('description', trim($row['description']));		           

		$tpl_item->assign('required', $required);
		$tpl_item->assign('validation', $validation);

		$tpl_item->assign('class', $class);
		$tpl_item->assign('value', $row['value']);

		return $tpl_item->fetch($tpl_name_item);         

	}    

	public function GetTextArea($id, $item, $tplID = 1)
	{
		
		$row           = $item['row'];
		$tpl_item      = new Template();
		$tpl_name_item = BASEPATH . 'lib/wmpForm/template_textarea_' . $tplID . '.tpl'; 

		$maxlength = ($row['maxlength']) ? $row['maxlength'] : 250;  
		$class     = $row['size'];        

		$required   = ($row['requred']) ? "true" : "false";
		$validation = ($row['requred'] && $row['validation']) ?  ',' . $row['validation'] : "";

		$tpl_item->assign('req', $row['requred']);
		
		$tpl_item->assign('id', $id);
		$tpl_item->assign('string', $row["string"]);
		$tpl_item->assign('maxlength', $maxlength);               

		$tpl_item->assign('required', $required);
		$tpl_item->assign('validation', $validation);
		
		$tpl_item->assign('hasDescription', (bool) trim($row['description']));
		$tpl_item->assign('description', trim($row['description']));		

		$tpl_item->assign('class', $class);
		$tpl_item->assign('value', $row['value']);

		if ( empty ($row['selected']) )
			$tpl_item->assign('selected', $row['value']);
		else 
			$tpl_item->assign('selected', $row['selected']);

		return $tpl_item->fetch($tpl_name_item);

	}    

	// получение кнопок на отправку данных
	public function GetSubbtns($form_id, $info, $tplID)
	{
		
		$tpl_item = new Template;
		
		$tpl_item->assign('form_id', $form_id);

		// если в базе не указано значение для нопки сабмита - то даем дефолтное
		if (empty($info['sub_title_id']))
			$tpl_item->assign('subTitle', Dictionary::GetWord(10075));
		else
			$tpl_item->assign('subTitle', Dictionary::GetWord($info['sub_title_id']));

		if (empty($info['subbtns']))
			$tpl_name_item = BASEPATH . 'lib/wmpForm/template_subbtn_' . $tplID . '.tpl';
		else
			$tpl_name_item = $info['subbtns'];

		return $tpl_item->fetch($tpl_name_item);
	}

	// выбираем поля формы
	public function RetrieveForm ( $id )
	{
		
		$que = "select * 
			FROM `?_form_order`
			WHERE 1=1
				AND active=1
				AND id=" . $id . "
				AND lang_id='" . Lang::getID() . "' 
				limit 1
		";

		return DB::GetArray(DB::Query($que));

	}    

	// выбираем поля формы
	public function RetrieveFormEditors ($id)
	{
		$que = "select * 
		FROM `?_form_order_control_element` 
		WHERE 1=1
		AND active=1
		AND form_id=".$id."
		AND lang_id='".Lang::getID()."' 
		ORDER BY place";
		$result  = DB::Query($que);

		$form_items = array();
		while ($row = DB::GetArray($result))
			$form_items [$row['id']] = array(
				'id'            => $row['id']
				, 'type'        => $row['type']
				, 'string'      => $row["string"]
				, 'requred'     => $row["requred"]
				, 'validate'    => (isset($row["validate"])) ? $row["validate"] : ""
				, 'row'         => $row
				, 'description' => $row['description']
			);

		return $form_items;

	}

	// возвращает сгенерированную форму.
	public function GenerateForm($info, $tplID = 1)
	{

		$tpl        = new Template();
		$tmpl_name  = BASEPATH . 'lib/wmpForm/template_' . $tplID . '.tpl';
		$form_id    = intval(rand(1, 9999));          
		$form_items = self::$Form_items;

		foreach ($form_items as $key => $item){
			switch( $item['type'] ) {
				case "combo_box":
					$html                      = self::GetCombobox($key, $item, 1);
					$form_items [$key]['type'] = "combo";
					$form_items [$key]['html'] = $html;
					break;
				case "text_field":
					$html                      = self::GetTextField($key, $item, 1);
					$form_items [$key]['type'] = "text";
					$form_items [$key]['html'] = $html;                    
					break;                
				case "text_area":
					$html                      = self::GetTextArea($key, $item, 1);
					$form_items [$key]['type'] = "textarea";
					$form_items [$key]['html'] = $html;                
					break;                
				case "label":                  
					$form_items [$key]['html'] = '';                
					break; 
				case "radiobutton":
					$html = self::GetRadiobutton($key, $item);
					$form_items [$key]['html'] = $html;                
					break;
				case "captcha":
					$html = self::GetCaptcha ($key, $item);                    
					$form_items [$key]['html'] = $html;                
					break; 
				case "files":
					$html                      = self::GetFiles($key, $item, 1);
					$form_items [$key]['html'] = $html;                 
					break;                                
			}            
		}

		$tpl->assign('action', $_SERVER['REQUEST_URI']);
		$tpl->assign('form_items', $form_items);
		$tpl->assign('content_id', Url::get("contentID"));
		$tpl->assign('form_id',    $info['id']);        
		$tpl->assign('formInfo',   self::$Form_info);

		$tpl->assign('subbtns', self::GetSubbtns($info['id'], $info, 1));               

		// есть ошибки и мы их выведим.
		if ( count(self::$Errors) > 0 ) {
			$tpl->assign('hasErrors', true );
			$tpl->assign('errorsMessage', self::$Errors );
		}        

		return $tpl->fetch($tmpl_name);        
	}
	
	// возвращает форму, если не была заполнена форма, или производит обработку формы
	public function GetForm($id, $tplID = 1)
	{
	
		$id              = intval($id);
		self::$Form_info = self::RetrieveForm($id);

		// формы нету или она не активна
		if ( empty(self::$Form_info) )
			return;

		// выберем все редакторы    
		self::$Form_items = self::RetrieveFormEditors( self::$Form_info['id'] );

		if (isset($_REQUEST['is_form_order']) && $_REQUEST['form_id'] == $id && self::ProcessForm($id)) {

			if (strlen(self::$Form_info['target'])) {

				$url = str_replace("www.", "", getenv('HTTP_HOST'));

				if (substr(self::$Form_info['target'], 0, 1) == "/")
					$url = "http://" . $url . self::$Form_info['target'];
				else if(substr($get['another_page'], 0, 1) == "i")
					$url= "http://" . $url . "/" . self::$Form_info['target'];

				if (! headers_sent())
					header("Location: {$url}");
				else 
					echo "\n" . '<meta http-equiv="Refresh" content="0;url=' . $url . '" />' . "\n" . '</head>' . "\n" . '</html>' . "\n";


			} else
				return '<br /><span class="suc_mess">' . Dictionary::GetWord(704) . '</span><br />';

		} else
			return self::GenerateForm(self::$Form_info, $tplID);

	}

	public function GiveForm($id, $tplID = 1)
	{
		echo self::GetForm($id, $tplID);
	}

	// обработка файлов на отправку по форме
	function ProcessFileUpload ($form_id, $item_id, $folder) {             
		$itemField = 'userfile'.$item_id;
		if ( !isset ($_FILES[$itemField]) || !($_FILES[$itemField]['name']) ){
			return 'не загружен файл.';
		}

		$t_tmp_filename = $_FILES[$itemField]['tmp_name'];
		$t_new_filename = $_FILES[$itemField]['name'];
		$t_new_filesize = $_FILES[$itemField]['size'];
		$t_upfile_error = $_FILES[$itemField]['error'];

		// нечего загружать нам всякие скрипты. - молча обрубаем и ничего не выводим
		if ( end( explode(".", $filename) ) == 'php' ){
			die();
		}

		$t_new_filename = $folder."f".$form_id.'_'.time().'_'.$t_new_filename;
		$upload = BASEPATH.$t_new_filename;

		//$upload = $path."/".$t_new_filename;
		if($t_new_filename != '' and $t_new_filename != 'none') {
			// пытаемся загрузить файл
			if(move_uploaded_file($t_tmp_filename, $upload)) {
				$x .= "Файл: ".$t_new_filename." загружен.";
				$x .= "( <a target='_blank' href='"._BASE_URL.'/'.$t_new_filename."' >ссылка</a> ).";
				chmod ($upload, 0644);
			} else {
				// 1 - размер загруженного файла превышает размер установленный параметром upload_max_filesize в php.ini
				if($t_upfile_error == 1) {
					$x .= 'Ошибка '.$t_upfile_error.' при загрузке файла: '.$t_tmp_filename.' в папку: '.$upload.' ! '.
					'<br />Размер загруженного файла '.$t_new_filesize.' превышает размер установленный параметром '.
					'upload_max_filesize в php.ini'."\n";
				}
				// 2 - размер загруженного файла превышает размер установленный параметром MAX_FILE_SIZE в HTML форме.
				if($t_upfile_error == 2) {
					$x .= 'Ошибка '.$t_upfile_error.' при загрузке файла: '.$t_tmp_filename.' в папку: '.$upload.' !'.
					'<br />Размер загруженного файла '.$t_new_filesize.' превышает размер установленный параметром '.
					'MAX_FILE_SIZE ('.$_POST['MAX_FILE_SIZE'].' байт) в HTML форме.'."\n";
				}
				// 3 - загружена только часть файла
				if($t_upfile_error == 3) {
					$x .= 'Ошибка '.$t_upfile_error.' при загрузке файла: '.$t_tmp_filename.' в папку: '.$upload.' !'.
					'<br />Загружена только часть файла !'."\n";
				}
				// 4 - файл не был загружен (Пользователь в форме указал неверный путь к файлу).
				if($t_upfile_error == 4) {
					$x .= 'Ошибка '.$t_upfile_error.' при загрузке файла: '.$t_tmp_filename.' в папку: '.$upload.' !'.
					'<br />Пользователем был указан неверный путь к файлу !'."\n";
				} else {
					$x .= 'Ошибка '.$t_upfile_error.' при загрузке файла: '.$t_tmp_filename.' в папку: '.$upload.' !'."\n";
				}
			}
		}

		return $x;
	}    

	// отправка почты при необходимости
	function ProcessEmailing ($form_id, $last_id, $letter)
	{        
	
		$data = "<table border=\"1\" style=\"border-color:#b6d3b4;border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\">";
		
		foreach($letter as $key => $values)
			$data .= "<tr><td><b>$values[0]</b>&nbsp;</td><td>&nbsp;$values[1]</td></tr>";
		
		$data .= "</table>";

		$message = $data;
		$from_le = Config::get('email');
		$subject = sprintf('Заполнена форма. %s № %d', self::$Form_info['title'], $last_id);

		if (isset($_POST['feedback']) && intval($_POST['feedback']) > 0) {
			
			$email = trim(array_shift($_POST['form_word'][intval($_POST['feedback'])]));
			$email = DB::GetArray(DB::Query('select email from ?_feedback_lang where address="' . mysql_real_escape_string($email) . '" limit 1'));

			if (is_array($email)) 
				$email = array_shift($email);
			else
				$email = Config::get('email');	
			
		} else if (strlen(self::$Form_info['email']))
			$email = self::$Form_info['email'];
		else
			$email = Config::get('email');

		self::SendMail($email, $from_le, $subject, $message);

	}

	// конвертер кодировок для email
	function SendMail ( $mail_to, $from_le, $subject, $message ) 
	{
		
		if (isset($incoding)) {
			if($incoding == "k") {
				$charset = "koi8-r";
				$from_le = convert_cyr_string($from_le, w, k);
				$subject = convert_cyr_string($subject, w, k);
				$message = convert_cyr_string($message, w, k);
			} elseif($incoding == "d") {
				$charset = "x-cp866";
				$from_le = convert_cyr_string($from_le, w, d);
				$subject = convert_cyr_string($subject, w, d);
				$message = convert_cyr_string($message, w, d);
			} elseif($incoding == "i") {
				$charset = "ISO8859-5";
				$from_le = convert_cyr_string($from_le, w, i);
				$subject = convert_cyr_string($subject, w, i);
				$message = convert_cyr_string($message, w, i);
			} elseif($incoding == "m") {
				$charset = "x-mac-cyrillic";
				$from_le = convert_cyr_string($from_le, w, m);
				$subject = convert_cyr_string($subject, w, m);
				$message = convert_cyr_string($message, w, m);
			}
		} else
			$charset = "utf-8";

		$subject    = str_replace(array('&lt;','&gt;','&quot;'),array('<','>','"','"'), $subject);
		$message    = str_replace(array('&lt;','&gt;','&quot;', '\\"', '\\\\\\"',"\\'", "\\\\\\'"),array('<','>','"','"','"', "'", "'"), $message);
		$add_header = "From: $from_le\nContent-Type: text/html; charset=\"$charset\"\nContent-Transfer-Encoding: 8bit";
		
		mail($mail_to, $subject, $message, $add_header); 

	}

	function ProcessForm ($form_id)
	{

		self::ProcessFormFields($form_id);

		// были ошибки - вызвращаем человека назад        
		if (count(self::$Errors) > 0)
			return false;

		$result_id = DB::Query("select * from `?_form_order_mail` order by order_id desc");
		$last_id   = DB::GetArray($result_id);
		$last_id   = $last_id['order_id'] + 1;         

		// если в поле send_to_email стоит 1, тогда отправляем письмо
		if (self::$Form_info['send_to_email'] == 1)
			self::ProcessEmailing($form_id, $last_id, self::$Letter);

		// складываем в бд всю инфу о заполненной форме        
		foreach(self::$Letter as $id => $values) {             
			$val_stred = ConvertHtmlToAlt( $values[1] );
			$que = "Insert into `?_form_order_mail`
				(form_id,order_id,label,order_message,lang_id) 
					values 
				('{$form_id}','{$last_id}','{$values[0]}','{$val_stred}','" . Lang::getID() . "')
			";

			DB::Query( $que );
		}

		return true;
	}

	// обработка входных параметровна валидность, присутствие и формирование выходных данных.
	function ProcessFormFields ($form_id){
		//ХХХ здесь еще нужна реальная проверка входных данных и тп по форме        
		// Это просто золотая жила для хакеров
		$rec_lbl          = '';
		Config::$ajaxPost = new ajaxPost();        
		self::$Errors     = array();                
		self::$Letter     = array();
		// пробегаемся и собираем значения из входных
		$form_items       = &self::$Form_items;  

		foreach ($form_items as $key => $value){
			if ($value['type'] == 'label')
				continue;

			$label = $value['string'];            
			
			if (isset($_REQUEST['form_word'][$key]))
				$rec = $_REQUEST['form_word'][$key];
			else 
				$rec = '';

			//обрабатываем загруженный файл
			if ($value['type'] == 'files'){
				$res = self::ProcessFileUpload($form_id, $key, $value['row']['value']);
				self::$Letter[] = array($label, $res);
				continue;    
			}            

			if ($value['row']['multiple']) {
				// множественное значение
				if ( ! empty($value) && is_array($value) && is_array($rec)) {

					$rec     = Config::$ajaxPost->escapeArray($rec, 'escapeArray');
					$rec     = implode("\n", $rec);
					$rec_lbl = str_replace("\n", ', ', $rec);

				} else
					self::$Errors[] = str_replace("{0}", $label, Dictionary::GetUniqueWord(181));
			} else {
				$rec = $rec_lbl = Config::$ajaxPost->escapeString ( $_REQUEST['form_word'][$key] );
			}

			if ($value ['requred']){
				$rec = trim($rec);
				if ( empty( $rec ) ){                    
					self::$Errors[] = Dictionary::GetUniqueWord(181).' "'.$label.'".';
				} 

				// капча
				if ($value['type'] == 'captcha'){                    
					// нужно слить значение, чтобы не было потом повторных обращений
					$captcha = $_SESSION['form_word['.$key.']'];
					$_SESSION['form_word['.$key.']'] = time();

					if ($rec != $captcha){
						self::$Errors[] = Dictionary::GetUniqueWord(182);    
					}
					continue;
				}                
			}

			$form_items[$key]['row']['selected'] = $rec;
			self::$Letter[]                      = array($label, $rec_lbl);         

		} 
		return self::$Errors;       
	}
}

class CForm 
{
	private $_storage 	= array();
	private $_table		= null;
	
	public function __construct($storage) 
	{
		$this->_storage = array_merge($this->_storage, $storage);
	}

	public function setTable($table)
	{
		$this->_table = $table;
		
		return $this;
	}
}
