<?php

/**
* Generate html from templates
*/
class View {

	private $template_path;

	function __construct($config) {
		$this->template_path = $config['path']['templates'];
	}

	/**
	 * Paste date into phtml-template and return string
	 * @param string 
	 * @return string
	 */
	public function render($template, $vars) {

		$path = $_SERVER['DOCUMENT_ROOT'] . $this->template_path . '/' . $template;

		if( file_exists($path) ) {
			ob_start();
			require $path;
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		} else {
			echo 'Template "'. $path .'" isnot exsits!';
		}
	}
}