<?php 

/**
 * Root class of application
 */
class App {

	private $date_start;

	private $date_end;

	private $message;

	private $status = true;
	
	// Кол-во лет между датами
	private $years; 

	// Кол-во месяцев между датами
	private $months;

	// Кол-во дней между датами
	private $days; 
	
	// Общее кол-во дней между двумя датами 
	private $total_days;
	
	// true — если дата старта > дата конца
	private $invert = false;

	function __construct($date_start, $date_end) {
		$this->date_start = $this->parseString($date_start);
		$this->date_end = $this->parseString($date_end);
		$this->datediff();
	}

	/**
	 * Parse string to date parts
	 * @param string
	 * @return array
	 */
	public function parseString($string) {
		
		$date_array = preg_split('/\D/', $string);

		if( count($date_array) == 3 ) {
			$date = [];
			$date['year'] 	= intval($date_array[0]);
			$date['month'] 	= intval($date_array[1]);
			$date['day'] 		= intval($date_array[2]);			
		} else {
			$this->status = false;
			$this->message = 'Wrong format of date!';
		}

		return $date;
	}

	/**
	 * Calculate difference between dates
	 */
	public function datediff() {
		if( $this->status ) {

			$temp = $this->order_dates();
			$date_from = $temp['date_from'];
			$date_to = $temp['date_to'];

			$this->years = $date_to['year'] - $date_from['year'];
			$this->months = $date_to['month'] - $date_from['month'];
			
			// If dates in same year
			if( $this->years == 0 ) {
				$this->days = $date_to['day'] - $date_from['day'];
				
				// If dates in same month
				if( $this->months == 0 ) {	
					$this->total_days = $this->days;
				} else {
					$this->total_days = $this->get_month_days($date_from['month'], $date_from['year']) - $date_from['day'] + $date_to['day'];

					if( $this->days < 0 ) {
						$this->months--;
						$this->days = $this->total_days;
					}

					if( $this->months > 1 ) {
						for( $i = $date_from['month'] + 1; $i < $date_to['month']; $i++ ) {
							$this->total_days += $this->get_month_days($i, $date_from['year']);
						}
					}
				}

			// if dates in different years
			} else {
				$this->total_days = $this->get_month_days($date_from['month'], $date_from['year']) - $date_from['day'] + $date_to['day'];

				if( $date_from['month'] < 12 ) {
					for( $i = $date_from['month'] + 1; $i <= 12; $i++ ) {
						$this->total_days += $this->get_month_days($i, $date_from['year']);
					}
				}

				if( $date_to['month'] > 1 ) {
					for( $i = 1; $i < $date_to['month']; $i++ ) {
						$this->total_days += $this->get_month_days($i, $date_to['year']);
					}
				}

				for( $i = $date_from['year'] + 1; $i < $date_to['year']; $i++ ) {
					$this->total_days += 365 + $this->check_leap_year($i);
				}

				$months_in_years = $this->years * 12;
				
				$this->months = $date_to['month'] - $date_from['month'];	
				$months_in_years += $this->months;
				
				$this->days = $date_to['day'] - $date_from['day'];
				if( $this->days < 0 ) {
					$months_in_years--;
					$days_in_to_month = 0;
				} else {
					$days_in_to_month = $this->get_month_days($date_to['month'], $date_to['year']);
				}
			
				if( $date_to['month'] == 1 ) {
					$days_in_prev_month = CONFIG['settings']['month'][12];
				} else {
					$prev_month = $date_to['month'] - 1;
					if( $prev_month == 2 ) {
						$days_in_prev_month = $this->get_month_days($date_to['month'], $date_to['year']);
					} else {
						$days_in_prev_month = CONFIG['settings']['month'][$prev_month];
					}
				}
				$this->days = $days_in_prev_month - $date_from['day'] + $date_to['day'] - $days_in_to_month;
			

				if( $months_in_years >= 12 ) {
					$this->years = intval($months_in_years / 12);
					$this->months = $months_in_years % 12;
				} else {
					$this->years = 0;
					$this->months = $months_in_years;
				}
			}

		}
	}

	/**
	 * Sort dates in order
	 * @return array
	 */
	public function order_dates() {
		if( $this->date_end['year'] < $this->date_start['year'] 
			|| $this->date_end['year'] == $this->date_start['year'] 
				&& $this->date_end['month'] < $this->date_start['month'] 
			|| $this->date_end['year'] == $this->date_start['year'] 
				&& $this->date_end['month'] == $this->date_start['month'] 
				&& $this->date_end['day'] < $this->date_start['day'] ) {
			$date_from = $this->date_end;
			$date_to = $this->date_start;
			$this->invert = true;
		} else {
			$date_from = $this->date_start;
			$date_to = $this->date_end;
		}

		return compact('date_from', 'date_to');		
	}

	/**
	 * Get number days of month
	 * @param type $month 
	 * @param type $year 
	 * @return number
	 */
	public function get_month_days($month, $year) {
		$days = CONFIG['settings']['month'][$month];
		// var_info($this->check_leap_year($year));
		
		if( $month == 2 ) {
			$days += $this->check_leap_year($year);
		} else {
			return $days;
		}

		return $days;
	}

	/**
	 * Check if year is leap-year for addition one day
	 * @param type $year 
	 * @return number
	 */
	public function check_leap_year($year) {
		$addition = 0;

		$remain_4   = $year % 4;
		$remain_100 = $year % 100;
		$remain_400 = $year % 400;
		
		if( (!$remain_4 && $remain_100) || !$remain_400 ) {
			$addition = 1;
		}
		
		return $addition;
	}

	/**
	 * Return datediff data
	 * @return array
	 */
	public function getDiff() {

		$datediff['data'] = [
			'status' 			=> $this->status,
			'message' 		=> $this->message,
			'years' 			=> $this->years,
			'months' 			=> $this->months,
			'days' 				=> $this->days,
			'total_days' 	=> $this->total_days,
			'invert' 			=> $this->invert
		];

		return $datediff;
	}

}