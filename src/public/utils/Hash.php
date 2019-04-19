<?php


class Hash {
	private $word;
	private $first_salt;
	private $second_salt;
	private $config;
	private $type;
	private $nested_algorithm;
	private $algorithm;
	private $rounds;

	public function __construct(){
		require 'utils/Config.php';
		$this->config = $config;

		$this->word = '';
		$this->nested_algorithm = 'sha512';
		$this->algorithm = 'sha256';
		$this->rounds = 5;
		$this->type = 'password';
		$this->set_info_salt();
	}

	public function set_type($type){
		$this->type = $type;
		switch($type){
			case 'password':
				$this->set_password_salt();
				break;
			case 'info':
				$this->set_info_salt();
				break;
		}
	}

	public function set_word($word){
		$this->word = $word;
	}

	private function set_info_salt(){
		$this->salt = $this->config['info_salt'];
	}

	private function set_password_salt(){
		$this->first_salt 	= $this->config['password_salt_1'];
		$this->second_salt 	= $this->config['password_salt_2'];
	}

	public function hash(){

		switch($this->type){
			case 'password':
				return $this->hash_password();
				break;
			case 'info':
				return $this->hash_info();
				break;
		}

	}

	private function hash_password(){
		$return = $this->word;
		//var_dump( $this->word );

		// First rounds
		for($k=0; $k<$this->rounds; $k++){
			$return = 
				hash($this->nested_algorithm, $return . $this->first_salt);
			//var_dump( $return );
		}


		// Second rounds
		for($i=0; $i<$this->rounds; $i++){
			$return = 
				hash($this->algorithm, $return . $this->second_salt);
			//var_dump( $return );

		}

		return $return;

	}


	private function hash_info(){
		$return = $this->word;

		
		for($k=0; $k<$this->rounds; $k++){
			$return = 
				hash($this->algorithm, $return . $this->first_salt);
		}

		return $return;
	}
}

?>