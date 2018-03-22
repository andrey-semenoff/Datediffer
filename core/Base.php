<?php

/**
* Root class
*/
class Base {

	protected $config;
	protected $view;
	
	function __construct($config)
	{
		$this->config = $config;
		$this->view = new View($this->config);
	}

	/**
	 * Runs application
	 */
	function run() {
		if( !empty($_POST) ) {

			if( isset($_POST['date_start']) && !empty($_POST['date_start'])
				&& isset($_POST['date_end']) && !empty($_POST['date_end']) ) {

				$app = new App($_POST['date_start'], $_POST['date_end']);

				$data = $app->getDiff();
				$vars['result'] = $this->view->render('result.phtml', $data);
			
			} else {
				$data = [
					'status' => false,
					'message' => 'Data is not anough!'
				];
				
				$vars['result'] = $this->view->render('result.phtml', $data);
			}
		}

		if( $_POST['ajax'] ) {
			echo json_encode($vars['result']);
		} else {
			echo $this->view->render('index.phtml', $vars);
		}


	}
}