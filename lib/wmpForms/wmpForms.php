<?php

class Forms {

    protected $_ID = null;
    protected $_langID = null;
    protected $_tpl = null;
    protected $_types = array('text', 'textarea', 'radio', 'checkbox', 'select', 'file');
    protected $_form = array();
    protected $_elements = array();
    protected $_errors = array();
    protected $_extensions = array('gif','png' ,'jpg', 'bmp', 'doc', 'docx', 'pdf', 'txt', 'rtf');

    public function __construct($id, $langID) {
        $this->_ID = intval($id);
        $this->_langID = intval($langID);
        $this->_form = $this->getForm();
        $this->_elements = $this->getElements();

        if(!count($this->_form)) {
            throw new Exception('Form doesn\'t available.');
        }
    }

    public function setTpl($tplID) {
        $this->_tpl = strval($tplID);
        return $this;
    }

    private function getForm() {

        $data = Registry::get('db')->query('select f.*, fl.* '
                . ' from ?_form f left join ?_form_lang fl on f.id = fl.id '
                . ' where f.id = ' . intval($this->_ID) . ' and fl.langID = ' . intval($this->_langID) 
                . ' limit 1')->fetch();

        return $data;
    }

    private function getElements() {
        $data = array();

        $query = Registry::get('db')->query('select f.*, fl.* '
                . 'from ?_form_element f left join ?_form_element_lang fl on f.id = fl.id '
                . 'where f.formID = ' . intval($this->_ID) . ' and fl.langID = ' . intval($this->_langID) . ' and fl.isActive = 1 '
                . 'order by f.sort');

        foreach($query->fetchAll() as $get) {
            if (empty($get['type']) || !in_array($get['type'], $this->_types)) {
                continue;
            }
            if (in_array($get['type'], array('radio', 'checkbox', 'select'))) {
                $get['value'] = unserialize($get['value']);
            }
            $data[$get['id']] = $get;
        }

        return $data;
    }

    public function showForm() {
        $data = array();
        
        foreach ($this->_elements as $key => $element) {
            $eTpl = new Template;
            $eTpl->assign('formID', $this->_ID);
            $eTpl->assign('element', $element);

            $this->_elements[$key]['tpl'] = $eTpl->fetch(__DIR__ . '/elements/' . $element['type'] . '.tpl');
        }
        
        if($this->_form['hasCaptcha']) {
            $eTpl = new Template;
            $eTpl->assign('formID', $this->_ID);
            $this->_elements['captcha'] = array(
                'id' => 'captcha',
                'title' => Dictionary::GetUniqueWord(67),
                'isRequired' => 1, 
                'tpl' => $eTpl->fetch(__DIR__ . '/elements/captcha.tpl')
            );
        }

        $tpl = new Template;
        $tpl->assign('formID', $this->_ID);
        $tpl->assign('form', $this->_form);
        $tpl->assign('elements', $this->_elements);
        $tpl->assign('data', $data);

        if (filter_input(INPUT_POST, 'formID', FILTER_VALIDATE_INT) && filter_input(INPUT_POST, 'formID', FILTER_VALIDATE_INT) === $this->_ID && filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)) {
            $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            if ($this->validate($data) && $this->save($data)) {
                $tpl->assign('success', 1);
            } else {
                $tpl->assign('errors', $this->_errors);
                $tpl->assign('data', $data);
            }
        }

        return $tpl->fetch(__DIR__ . '/' . __FUNCTION__ . '_' . $this->_tpl . '.tpl');
    }
    
    public function displayForm() {
        echo $this->showForm();
    }
    
    public function ajax($data) {
        if ($this->validate($data) && $this->save($data)) {
            $result = array('message' => $this->_form['message'], 'success' => 1);
        } else {
            $result = array('message' => $this->_errors, 'success' => 0);
        }
        
        return $result;
    }

    private function validate(&$_data) {

        if(!check_wmp_sessid()) {
            $this->_errors[] = Dictionary::GetUniqueWord(22);
            return false;
        }
        
        if($this->_form['hasCaptcha']) {
            if(!strlen($_data['captcha' . $this->_ID]) || $_data['captcha' . $this->_ID] !== $_SESSION['data[captcha'. $this->_ID .']']) {
                $this->_errors[] = Dictionary::GetUniqueWord(68);
                return false;
            }
            unset($this->_elements['captcha']);
        }
        
        foreach ($this->_elements as $key => $value) {
            if ($value['isRequired'] && $value['type'] !== 'file' && (!isset($_data[$key]) || empty($_data[$key]))) {
                $this->_errors[] = Dictionary::GetUniqueWord(21) .' "' . $value['title'] . '"';
            } elseif($value['validation']) {
                switch($value['validation']) {
                    case 'email':
                        if(!filter_var($_data[$key], FILTER_VALIDATE_EMAIL)) {
                            $this->_errors[] = Dictionary::GetUniqueWord(21) .' "' . $value['title'] . '"';
                        }
                        break;
                }
            } elseif($value['type'] === 'file') {
                $file = $_FILES['data'];
                if($value['isRequired'] && (!empty($file) || !isset($file['name'][$value['id']]))) {
                    $this->_errors[] = Dictionary::GetUniqueWord(21) .' "' . $value['title'] . '"';
                } elseif(intval($file['error'][$value['id']])) {
                    switch(intval($file['error'][$value['id']])) {
                        case 1:
                        case 2:
                            $this->_errors[] = $value['title'] .' - "File size exceeds the allowable size."';
                            break;
                        case 3:
                             $this->_errors[] = $value['title'] .' - "The uploaded file was only partially uploaded."';
                            break;
                        case 4:
                        default:
                             $this->_errors[] = $value['title'] .' - "No file was uploaded."';
                            break;
                    }
                } else {
                    if(!in_array(pathinfo($file['name'][$value['id']], PATHINFO_EXTENSION), $this->_extensions)) {
                        $this->_errors[] = $value['title'] .' - "Unallowed extension."';
                    } else {
                        $uploadFile = 'files/form/'. transliterate(basename($file['name'][$value['id']]));
                        if (move_uploaded_file($file['tmp_name'][$value['id']], BASEPATH . $uploadFile)) {
                            $_data[$key] = '/'. $uploadFile;
                        }   
                    }
                }
            }
        }

        return (count($this->_errors) ? false : true);
    }

    private function save($_data) {
        $data = array();
        $db = Registry::get('db');
        
        foreach ($this->_elements as $key => $value) {
            if (!isset($_data[$key])) {
                continue;
            }
            if (is_array($_data[$key])) {
                $val = implode(', ', $_data[$key]);
            } else {
                $val = htmlspecialchars($_data[$key]);
            }

            if(!$val) {
                continue;
            }
            
            $data[] = array(
                'title' => $value['title'],
                'value' => $val
            );
        }

        if (empty($data)) {
            return false;
        }

        $recordInfo = array(
            'formID' => $this->_ID,
            'langID' => $this->_langID,
            'date' => date("Y-m-d H:i:s"),
            'ip' => getenv('REMOTE_ADDR'),
            'country' => getCountryInfo()
        );

        $db->exec('insert into ?_form_values (' . implode(',', array_keys($recordInfo)) . ') values ("' . implode('", "', $recordInfo) . '")');
        $insertID = $db->lastInsertId();

        $query = $db->prepare('insert into ?_form_values_element (id, title, value) values (:ID, :TITLE, :VALUE)');
        foreach($data as $item) {
            $query->bindValue(':ID', $insertID);
            $query->bindValue(':TITLE', $item['title'], PDO::PARAM_STR);
            $query->bindValue(':VALUE', $item['value'], PDO::PARAM_STR);
            $query->execute();
        }
        
        if($this->_form['isSend']) {
            $this->notifyAdmin($_data, $recordInfo);
        }
        
        return true;
    }
    
    private function notifyAdmin($_data, $recordInfo) {

        $mail = new PHPMailer;
        $tpl = new Template;

        $host = str_replace('www.', '', getenv('HTTP_HOST'));
        
        $subject = 'Конструктор форм (' . $host . ')';
        $mail->CharSet = 'utf-8';
        $mail->Subject = $subject;

        $mail->SetFrom('no-reply@' . $host, $subject);
        foreach (explode(',', Config::get('email')) as $email) {
            $mail->AddAddress($email, $subject);
        }
        
        $data = array();

        foreach ($this->_elements as $key => $value) {
            if (!isset($_data[$key])) {
                continue;
            }
            if (is_array($_data[$key])) {
                $val = implode(', ', $_data[$key]);
            } elseif($value['type'] === 'file') {
                $val = '<a target="_blank" href="'. BASE_URL . $_data[$key] .'">'. $_data[$key] .'</a>';
            } else {
                $val = $_data[$key];                
            }

            $data[] = array('title' => $value['title'], 'value' => $val);
        }
        
        $tpl->assign('form', $this->_form);
        $tpl->assign('data', $data);
        $tpl->assign('recordInfo', $recordInfo);
        
        $mail->MsgHTML($tpl->fetch(dirname(__FILE__) . '/'. __FUNCTION__ .'.tpl'));
        $mail->Send();

        return true;
    }

}