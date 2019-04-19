<?php


// Echoeing Translation
function t($string){
	$translate_request = new Translate();
	echo $translate_request->t($string);
}

// Returning Translation
function tr($string){
	$translate_request = new Translate();
	return $translate_request->t($string);
}


class Translate {
	//require_once 'utils/Translate_el_gr.php';

	private $default_locale;

	public function __construct() {
		$this->default_locale = 'en-us';
	}

	public function t($request) {
		if(isset($_COOKIE['locale'])){
			$locale=$_COOKIE['locale'];
			return($this->request($locale, $request));
		}else{
			return($this->request($this->default_locale, $request));
		}
	}

	private function request($locale, $string){
		switch($locale){
			case 'el_gr':
				return($this->request_el_gr($string));
				break;
			case 'en_us':
				return($this->request_en_us($string));
				break;
		}
	}

	private function request_en_us($string){
		return $string;
	}

	private function request_el_gr($string){
		include('utils/Translate_el_gr.php');
		if(isset($$locale)){
			$locale_table = $$locale;
			if (isset($locale_table[$string])){
				return $locale_table[$string];
			}else{
				return $string;
			}
		}else{
			return $string;
		}


	}

}
