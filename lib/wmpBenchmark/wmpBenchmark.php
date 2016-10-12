<?

class Benchmark
{

	// Массив точек отсчета
	public $point = array();

	// Установить точку отсчета	
	public function setPoint($name)
	{
		$this->point[$name] = microtime();
	}

	// Получение времени между 2х точек	
	public function getElapsedTime($point1 = '', $point2 = '', $decimals = 4)
	{

		//если нет исходной точки возрвращает 0
		if ($point1 == '' || ! isset($this->point[$point1]))
			return 0;

		//если нет второй точки, берем текущее время
		if ( ! isset($this->point[$point2]))
			$this->point[$point2] = microtime();

		list($sm, $ss) = explode(' ', $this->point[$point1]);
		list($em, $es) = explode(' ', $this->point[$point2]);

		return number_format(($em + $es) - ($sm + $ss), $decimals);

	}
}