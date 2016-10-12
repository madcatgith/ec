<?

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Subscribe extends Singleton
{

    protected static $_instance = null;
    private $_errors            = array();
    private $_success           = array();

    protected function _clear()
    {
        $this->_errors  = array();
        $this->_success = array();
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function getSuccess()
    {
        return $this->_success;
    }

    public function getForm($tplID = 1)
    {
        $tpl = new Template();

        $tpl->assign('langID', Lang::getID());

        return $tpl->fetch(__DIR__ . '/' . __FUNCTION__ . '_' . $tplID . '.tpl');
    }

    public function add($data)
    {

        $this->_clear();

        $data = array_merge(array(
            'name'  => '',
            'email' => ''
                ), $data);

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $this->_errors[] = Dictionary::GetUniqueWord(537);
            return false;
        }

        $emailQuery = DB::GetArray(DB::Query('select * from `?_subscribers` where email="' . mysql_real_escape_string($data['email']) . '" limit 1'));

        if (is_array($emailQuery) === true) {
            $this->_errors[] = Dictionary::GetUniqueWord(286);
            return false;
        }

        $tpl          = new Template;
        $mail         = new PHPMailer;
        $subject      = Dictionary::GetWord(155) . ' (' . getenv('HTTP_HOST') . ')';
        $approvedLink = md5(rand(0, 999999999) . time());

        $mail->CharSet = 'utf-8';
        $mail->Subject = $subject;

        $vals = array(
            'name'         => mysql_real_escape_string(clearVal($data['name'])),
            'email'        => mysql_real_escape_string($data['email']),
            'active'       => 0,
            'approved'     => 0,
            'approvedLink' => $approvedLink,
            'ip'           => getenv('REMOTE_ADDR'),
            'lang_id'      => Lang::getID(),
            'date'         => date('Y-m-d H:i:s')
        );

        DB::Query("replace into ?_subscribers (`" . implode('`, `', array_keys($vals)) . "`) values ('" . implode("', '", $vals) . "')");

        $tpl->assign('link', 'http://' . getenv('HTTP_HOST') . Url::setUrl(array('lang' => Lang::getID())) . '?action=confirmEmail&app=' . $approvedLink);

        $mail->SetFrom(SUBSCRIBE_EMAIL, $subject);
        $mail->AddAddress($data['email'], $subject);
        $mail->MsgHTML(Controller::run('email', array('body' => $tpl->fetch(dirname(__FILE__) . '/add.tpl')), true));
        $mail->Send();

        $this->_success[] = Dictionary::GetUniqueWord(615);

        return true;
    }

    public function confirmEmail($app, $tplID = 1)
    {
        $tpl = new Template;

        if (DB::Query("update `?_subscribers` set `approved`=1 where `approvedLink`='" . mysql_real_escape_string(clearVal($app)) . "'") && mysql_affected_rows() == 1) {
            $tpl->assign('title', Dictionary::GetUniqueWord(536));
            $tpl->assign('message', Dictionary::GetWord(101));
        } else {
            $tpl->assign('title', Dictionary::GetUniqueWord(539));
            $tpl->assign('message', Dictionary::GetWord(171));
        }

        $tpl->display(__DIR__ . '/' . __FUNCTION__ . '_' . $tplID . '.tpl');
    }

}
