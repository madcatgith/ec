<?

$isApproved = 0;
$isActive   = 0;
$isChanged  = 0;

function sendMail($from, $to, $context = array())
{

	$langID   = (int) $_POST['data'][0]['lang_id'];
	$email    = array_shift(DB::GetArray(DB::Query('select publicEmail from ?_config where lang_id=' . $langID . ' limit 1')));	
	$subject  = $context['header'] . " (". $_SERVER['HTTP_HOST'] .")";
	$header   = "From: \"" . mysql_real_escape_string($from) . "\" <" . $email . ">\n";
	$header  .= "To: " . $to . "\n";
	$header  .= "Content-type: text/html; charset=\"utf-8\"\n";
	$header  .= "Subject: " . $subject . "\n";
	$body     = "<body>" . $context['title'] . "<br />" . $context["message"] . "</body>";

	return mail($from, $subject, $body, $header);

}

// сравниваем старый и новый пароль
function isChanged($val, $data)
{

	global $isChanged;
	
	if (strlen($val) == 0)
		return false;

	$password = array_shift(mysql_fetch_assoc(mysql_query("select password from wmp_client where id='{$data['id']}' limit 1")));
	
	if ($password === md5(trim($val)) || $password === trim($val))
		return false;
	else
		return $isChanged = true;

}

// Заносим старые значения активности и подтверждения мыла
function beforeUpdate($id) 
{

	global $isApproved, $isActive, $isChanged;

	$data       = mysql_fetch_assoc(mysql_query("select * from wmp_client where id='{$id}' limit 1"));
	$isApproved = intval($data['approved']);
	$isActive   = intval($data['active']);

}

function onInsert($id, $data)
{
	
	if (get_magic_quotes_gpc()) {  
		$data['data'][0]['login']    = mysql_real_escape_string(stripslashes($data['data'][0]['login']));   
		$data['data'][0]['password'] = mysql_real_escape_string(stripslashes($data['data'][0]['password']));   
	} else {
		$data['data'][0]['login']    = mysql_real_escape_string($data['data'][0]['login']);   
		$data['data'][0]['password'] = mysql_real_escape_string($data['data'][0]['password']);  
	}

	if ($data['data'][0]['active'] && intval($data['data'][0]['approved'])) {		
		sendMail(array_shift(mysql_fetch_assoc(mysql_query("select title from wmp_config limit 1"))), $data['data'][0]['emailAddress'], array(
				"header"    => Dictionary::GetUniqueWord(430, $data['data'][0]['lang_id'])
				, "title"   => Dictionary::GetUniqueWord(431, $data['data'][0]['lang_id'])
				, "message" => "<b>Login</b> : " . trim($data['data'][0]['login']) . "<br /><b>Password</b> : " . trim($data['data'][0]['password']) . "<br /><a href=\"http://" . $_SERVER['HTTP_HOST'] . "/\">http://" . $_SERVER['HTTP_HOST'] . "/</a>"
			)
		);
	}	
	
    
    $phpbb = new phpbbClass($_SERVER['DOCUMENT_ROOT'] . '/forum/');

	// Adding user
	$phpbb->user_add(array(		
		"username"      => $data['data'][0]['login'],
		"user_password" => $data['data'][0]['password'],
		"user_email"    => $data['data'][0]['emailAddress'],
		"group_id"      => 2
	));

}

function onUpdate($id, $data) 
{
	
	global $isApproved, $isActive, $isChanged;

	if (get_magic_quotes_gpc()) {  
		$data['data'][0]['login']    = mysql_real_escape_string(stripslashes($data['data'][0]['login']));   
		$data['data'][0]['password'] = mysql_real_escape_string(stripslashes($data['data'][0]['password']));   
	} else {
		$data['data'][0]['login']    = mysql_real_escape_string($data['data'][0]['login']);   
		$data['data'][0]['password'] = mysql_real_escape_string($data['data'][0]['password']);  
	}
	
	if ($isApproved == 0 && intval($data['data'][0]['approved']) == 1) {		
		sendMail(array_shift(mysql_fetch_assoc(mysql_query("select title from wmp_config limit 1"))), $data['data'][0]['emailAddress'], array(
				"header"    => Dictionary::GetUniqueWord(430, $data['data'][0]['lang_id'])
				, "title"   => Dictionary::GetUniqueWord(431, $data['data'][0]['lang_id'])
				, "message" => "<b>Login</b> : " . trim($data['data'][0]['login']) . "<br /><b>Password</b> : " . trim($data['data'][0]['password']) . "<br /><a href=\"http://" . $_SERVER['HTTP_HOST'] . "/\">http://" . $_SERVER['HTTP_HOST'] . "/</a>"
			)
		);
	} else if ($isActive == false && intval($data['data'][0]['active']) == true) {
		sendMail(array_shift(mysql_fetch_assoc(mysql_query("select title from wmp_config limit 1"))), $data['data'][0]['emailAddress'], array(
				"header"  => Dictionary::GetUniqueWord(432, $data['data'][0]['lang_id'])
				, "title" => Dictionary::GetUniqueWord(433, $data['data'][0]['lang_id'])
			)
		);		
	} else if ($isActive == true && intval($data['data'][0]['active']) == false) {
		sendMail(array_shift(mysql_fetch_assoc(mysql_query("select title from wmp_config limit 1"))), $data['data'][0]['emailAddress'], array(
				"header"  => Dictionary::GetUniqueWord(432, $data['data'][0]['lang_id'])
				, "title" => Dictionary::GetUniqueWord(434, $data['data'][0]['lang_id'])
			)
		);
	} else if ($isChanged == true && intval($data['data'][0]['approved']) == 1) {
		sendMail(array_shift(mysql_fetch_assoc(mysql_query("select title from wmp_config limit 1"))), $data['data'][0]['emailAddress'], array(
				"header"    => Dictionary::GetUniqueWord(435, $data['data'][0]['lang_id'])
				, "title"   => Dictionary::GetUniqueWord(431, $data['data'][0]['lang_id'])
				, "message" => "<b>Login</b> : " . trim($data['data'][0]['login']) . "<br /><b>Password</b> : " . trim($data['data'][0]['password']) . "<br /><a href=\"http://" . $_SERVER['HTTP_HOST'] . "/\">http://" . $_SERVER['HTTP_HOST'] . "/</a>"
			)
		);
	}

	
	if ($isChanged == true) {
		
		$phpbb = new phpbbClass($_SERVER['DOCUMENT_ROOT'] . '/forum/');	

		$phpbb->user_update(array(		
			"username"      => $data['data'][0]['login'],
			"user_password" => $data['data'][0]['password'],
			"user_email"    => $data['data'][0]['emailAddress'],
		));

	}

}

function beforeDelete($id)
{
	$phpbb = new phpbbClass($_SERVER['DOCUMENT_ROOT'] . '/forum/');
	$phpbb->user_delete(array(
		'username' => array_shift(DB::GetArray(DB::Query('select login from ?_client where id=' . $id . ' limit 1')))
	));
}

$GridTitle = "Пользователи";
$from      = array(
	"table"           => "client"
	, "as"            => "clt"
	, "limit"         => 500
	, "nonlang"       => true 
	, "style"         => "width:100%"
	, "lang"          => 1
	, "callback"      => array(
		'beforeUpdate'   => 'beforeUpdate'
		, 'onInsert'     => 'onInsert'
		, 'onUpdate'     => 'onUpdate'
		//, 'beforeDelete' => 'beforeDelete'
	)
	, "nonlang_field" => array(
		"id" => array(
			"title"      => "id",
			"tablestyle" => "width: 40px;",
			"colType"    => "lbl"
		)
		, 'reg'	=> array(
			'title'	=> 'Дата регистрации',
			'colType' => 'lbl'
		)
		, "name" => array(
			"title"   => "Имя",
			"colType" => "text"
		)
		, "surname" => array(
			"title"   => "Фамилия",
			"colType" => "text"
		)	
		, "patronymic" => array(
			"title"   => "Отчество",
			"colType" => "text"
		)
		, "region" => array(
			'title' => 'Область',
			"colType" => "text"
		)
		, "email" => array(
			"title"   => "Email",
			"colType" => "text"
		)							
        , "phone" => array(
            "title"   => "Телефон",
            "colType" => "text"
        )      
        /*, "photo" => array(
		    "title"       => "Фото",
		    "style"       => "width: 20px;",
		    'imagesizing' => array('height', 50, 'width', 50),
		    "tablestyle"  => "text-align: left;",
		    "colType"     => "image"
        )*/    
        , "login" => array(
            "title"   => "Логин [Уникальное поле]",
            "colType" => "text"
        )      
        , "password" => array(
            "title"   => "Пароль",
            "colType" => "password",
            "callback" => array(
            	'beforePrepeareValue' => 'isChanged'
            	, 'prepareValue' => create_function('$val', 'return md5($val);')
            )
        )	
		, "approved" => array(
			"title"      => "Подтвержден",
			"tablestyle" => "width: 90px;",
			"colType"    => "check"
		)		
		, "active" => array(
			"title"      => "Активный",
			"tablestyle" => "width: 70px;",
			"colType"    => "check"
		)		
	)
	, "row_seq"	=> array("id", 'login', 'name', 'surname', 'patronymic', 'phone', 'approved', 'active')
);
