<?

class Upload 
{

	private $_file = null;

	public function __construct($file) {
		$this->_file = $file;
	}

	// массив допустимых расширений
	private $_allovedExtension = array('jpg', 'gif', 'png', 'jpeg', 'bmp');

	// добавляем расширение
	public function addExtension()
	{
		if (func_num_args() > 0) {
			
			$args = func_get_args();
			
			foreach ($args as $ext) {
				$this->_allovedExtension[] = (string) $ext;
			}
		}
	}

	public static $errors = array();

	// массив ошибок
	private $_errors = array();

	// добавляем ошибку
    private function _addError($error)
    {
        $this->_errors[] = $error;
    }

    // получаем массив ошибок
    public function getErrors()
    {
		return $this->_errors;
    }

    // путь для складывания файлов
    private $_dir  = '';

    // устанавливаем путь
    public function setDir($dir = '')
    {

    	// добавляем рут
        $this->_dir  = $dir;

        // проверка существования директории
        if (is_dir($this->_dir)) {
            if (is_writable($this->_dir)) {
                return true;
            } else {
                $this->_addError(strtr(self::$errors['d1'], '%f', $this->_dir));
                return false;
            }
        } else {
            $this->_addError(strtr(self::$errors['d0'], '%f', $this->_dir));
            return false;
		}

    }

    // имя файла под которім будет сохраннен файл
    private $_sFileName = '';

    public function setName($name = '')
    {
		$this->_sFileName = (string) $name;
    }
    
    public function getName()
    {
		return $this->_sFileName;
    }
    
    public function upload()
    {
		
		if (isset($this->_file['error']) && $this->_file['error'] != 0) {
        	$this->_addError(strtr(self::$errors[$this->_file['error']], '%f', $this->_file['name']));
            return false;
        } else if (isset($this->_file['name']) == false) {
        	$this->_addError(self::$errors['f0']);
        	return false;
        }

		// лучше єто делать по майму
        $fileExtension = strtolower(pathinfo($this->_file['name'], PATHINFO_EXTENSION));

        if (array_search($fileExtension, $this->_allovedExtension) === false) {
            $this->_addError(Upload::$errors['f1']);
            return false;
        }

        if (strlen($this->_sFileName) == 0) { 
        	// новое имя файла
        	$this->_sFileName = $this->_file["name"];
		}
        
        if (move_uploaded_file($this->_file['tmp_name'], $this->_dir . $this->_sFileName) == false) {
            $this->_addError(strtr(self::$errors[4], '%f', $this->_file['name']));
            return false;
        }

        return true;
    }
}

Upload::$errors = array(
	'f0' => 'Отсутствует имя файла', // there is no file name
	'f1' => 'Недопустимое расширение', // Invalid file extension
	1    => 'Файл "%f" превышает максимальнодопустимый размер',
	2    => 'Файл "%f" превышает максимальнодопустимый размер',
	3    => 'Файл "%f" загружен частично',
	4    => 'Файл "%f" не удалось загрузить',
	6    => 'Отсутствует времення директория', // 
	'd0' => 'Директория "%f" не существует',
	'd1' => 'Невозможно записать в директорию "%f"'
);