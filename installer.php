#!/usr/bin/env php

<?php

new TraiwiInstallation(new Colorizer());

/**
 * 
 * @author Steffen Kowalski <sk@traiwi.de>
 * 
 * @since 02.12.2015
 * @package TRAIWICLI
 *
 */
class TraiwiInstallation {
	
	/**
	 * 
	 * @var Colorizer
	 */
	protected $colorizer;
	
	/**
	 * 
	 * @var string
	 */
	protected $targetDir;
	
	/**
	 * 
	 * @var array
	 */
	protected $folders;
	
	
	/**
	 * 
	 */
	public function __construct(Colorizer $colorizer) {
		$this->colorizer = $colorizer;
		$this->targetDir = getcwd();
		$this->folders = array(
			"traiwi",
			"traiwi/client",
			"traiwi/client/cache",
			"traiwi/client/config",
			"traiwi/client/cli",
			"traiwi/client/uploads",
			"traiwi/client/logs",
			"traiwi/src",
			"traiwi/src/core",
			"traiwi/src/modules",
			"traiwi/shell",
			"traiwi/shell/css",
			"traiwi/shell/js",
			"traiwi/shell/images",
			"traiwi/shell/fonts",
			"traiwi/shell/templates",
		);
		
		$this->colorizer->cecho("___________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;
		$this->colorizer->cecho("TRAIWI Installation", Colorizer::FG_ORANGE); echo PHP_EOL;
		$this->colorizer->cecho("___________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;

		$this->checkPermission();
		$this->createFolders();
		$this->installComposer();
		$this->createFiles();
		$this->setPermission();
	}
	
	/**
	 * 
	 */
	public function checkPermission() {
		if(!is_writable($this->targetDir)){
			$this->error("You have no permission to install TRAIWI in " . $this->targetDir);
		}
	}
	
	/**
	 * 
	 */
	public function createFolders() {
		$process = 0;
		
		$this->colorizer->cecho("Creating folder structure for traiwi: ", Colorizer::FG_LIGHT_GRAY); 
		$this->colorizer->cecho($process . " %", Colorizer::FG_GREEN);
		
		foreach($this->folders as $k => $folder) {
			$path = $this->targetDir . DIRECTORY_SEPARATOR . $folder;
			if(!file_exists($path)) {
				mkdir($path, 0750, true);
			}
			
			$process = round((100 / count($this->folders)) * ($k + 1));
			echo "\r";
			
			$this->colorizer->cecho("Creating folder structure for traiwi: ", Colorizer::FG_LIGHT_GRAY);
			$this->colorizer->cecho($process . " %", Colorizer::FG_GREEN);
		}
		
		echo PHP_EOL;
	
	}
	
	/**
	 * 
	 */
	public function installComposer() {
		if(!function_exists('curl_init')){
			$this->error("The php cURL extension is not installed");
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://getcomposer.org/installer");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		
		if(curl_errno($ch)) {
			$this->error(curl_error($ch));
		}
		
		curl_close($ch);
		
		$composer = "composer.phar";
		if(!file_put_contents($composer, $output)) {
			$this->error("composer.phar could not be created");
		}
		
		if(!chmod($composer, 0755)) {
			$this->error("Permission for composer.phar could not be set");
		}
		
		system("php composer.phar");
	}
	
	public function createFiles() {
		$this->colorizer->cecho("Creating default system files: ", Colorizer::FG_LIGHT_GRAY);
	
	}
	
	public function setPermission() {
		$this->colorizer->cecho("setPermission", Colorizer::FG_LIGHT_GRAY);
	
	}
	
	/**
	 * 
	 * @param string $error
	 */
	public function error($error) {
		$this->colorizer->cecho($error . ". exit", Colorizer::FG_RED); echo PHP_EOL; echo PHP_EOL;
		exit;
	}
	
}


/**
 * 
 * @author Steffen Kowalski <sk@traiwi.de>
 * 
 * @since 02.12.2015
 * @package TRAIWICLI
 *
 */
class Colorizer {

	const FG_BLACK = "0;30";
	const FG_DARK_GRAY = "1;30";
	const FG_BLUE = "0;34";
	const FG_LIGHT_BLUE = "1;34";
	const FG_GREEN = "0;32";
	const FG_LIGHT_GREEN = "1;32";
	const FG_CYAN = "0;36";
	const FG_LIGHT_CYAN = "1;36";
	const FG_RED = "0;31";
	const FG_LIGHT_RED = "1;31";
	const FG_PURPLE = "0;35";
	const FG_LIGHT_PURPLE = "1;35";
	const FG_BROWN = "0;33";
	const FG_YELLOW = "1;33";
	const FG_LIGHT_GRAY = "0;37";
	const FG_WHITE = "1;37";
	const FG_ORANGE = "38;5;208;48";

	const BG_BLACK = "40";
	const BG_RED = "41";
	const BG_GREEN = "42";
	const BG_YELLOW = "43";
	const BG_BLUE = "44";
	const BG_MAGENTA = "45";
	const BG_CYAN = "46";
	const BG_LIGHT_GRAY = "47";


	/**
	 *
	 * @param string $string
	 * @param string $fgColor
	 * @param string $bgColor
	 * @return string
	 */
	public function cecho($string, $fgColor = NULL, $bgColor = NULL) {
		$coloredString = "";
		$fgConst = $this->getConstName($fgColor);
		if(!is_null($fgConst)) {
			$coloredString .= "\033[" . constant("self::" . $fgConst) . "m";
		}

		$bgConst = $this->getConstName($bgColor);
		if(!is_null($bgConst)) {
			$coloredString .= "\033[" . constant("self::" . $bgConst) . "m";
		}

		$coloredString .=  $string . "\033[0m";

		echo $coloredString;
	}

	/**
	 *
	 * @param string $search
	 * @return string|NULL
	 */
	public function getConstName($search) {
		$class = new \ReflectionClass(__CLASS__);
		$constants = $class->getConstants();

		$constName = NULL;
		foreach($constants as $name => $value) {
			if($value == $search) {
				$constName = $name;
				break;
			}
		}

		return $constName;
	}
}

?>
