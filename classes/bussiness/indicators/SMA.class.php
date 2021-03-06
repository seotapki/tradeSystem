<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SMA extends BaseIndicator
	{
		private $period = null;

		private $values = null;

		/**
		 * @return SMA
		 */
		public static function create($period)
		{
			return new self($period);
		}

		public function __construct($period)
		{
			$this->period = $period;
		}

		public function getPeriod()
		{
			return $this->period;
		}

		/**
		 * @return SMA
		 */
		public function handle($value)
		{
			$this->values[] = $value;

			$canCalculate = true;

			if (
				$this->hasValue()
				&& count($this->values) > $this->period
			)
				array_shift($this->values);
			else if(count($this->values) != $this->period)
				$canCalculate = false;

			if ($canCalculate) {
				$this->setValue(
					Math::div(Math::summArray($this->values), $this->period)
				);
			}

			return parent::handle($value);
		}

		public function rollbackLastValue()
		{
			if ($this->values)
				array_pop($this->values);

			return parent::rollbackLastValue();
		}
	}
?>