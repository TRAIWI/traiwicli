#!/usr/bin/env php

<?php


new TraiwiInstallation(new Colorizer(), is_array($argv) ? $argv : array());

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
	 * @var string
	 */
	protected $core;
	
	/**
	 * 
	 * @var string
	 */
	protected $composer;
	
	/**
	 * 
	 * @var array
	 */
	protected $folders;
	
	/**
	 * 
	 * @var array
	 */
	protected $files;
	
	/**
	 * 
	 * @var array
	 */
	protected $argv;
	
	
	/**
	 * 
	 */
	public function __construct(Colorizer $colorizer, array $argv) {
		$this->colorizer = $colorizer;
		$this->argv = $argv;
		$this->folders = array(
			"client",
			"client/cache",
			"client/config",
			"client/cli",
			"client/uploads",
			"client/logs",
			"src",
			"src/core",
			"src/modules",
			"shell",
			"shell/css",
			"shell/js",
			"shell/images",
			"shell/fonts",
			"shell/templates",
		);
		
$config = '[mysql]
host="127.0.0.1"
dbname="' . @$this->argv[1] . '"
user="root"
password="123"

[system]
default_lang="de"
default_title="TRAIWI"
logging="on"
password_salt="123"
password_reset_salt="456"
lowest_role="GUEST"
custom_repository_factory=""
';

$htaccess = 'RewriteEngine On
RewriteBase /

RewriteRule ^uploads/ - [L]

RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.css$ core/$1/Shell/CSS/$2.css [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.js$ core/$1/Shell/JS/$2.js [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.gif$ core/$1/Shell/Images/$2.gif [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.png$ core/$1/Shell/Images/$2.png [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.jpg$ core/$1/Shell/Images/$2.jpg [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.eot$ core/$1/Shell/Fonts/$2.eot [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.eot?#iefix$ core/$1/Shell/Fonts/$2.eot [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.woff$ core/$1/Shell/Fonts/$2.woff [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.woff2$ core/$1/Shell/Fonts/$2.woff2 [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.ttf$ core/$1/Shell/Fonts/$2.ttf [L]
RewriteRule ^uploads/(.*)$ uploads/$1 [L]
RewriteRule ^([a-zA-Z0-9_]*)/(.*)\.svg#icomoon$ core/$1/Shell/Fonts/$2.svg [L]
RewriteRule ^(.*)\.ico$ - [L]
RewriteRule ^(.*)$ main.php?url=$1 [QSA,L]			
';

$main = '<?php

ob_start();

$ds = DIRECTORY_SEPARATOR;

ini_set("expose_php","Off");
ini_set("log_errors",TRUE);
ini_set("error_log",dirname(__FILE__).$ds."logs".$ds."custom_error_log.txt");
error_reporting(E_ALL ^ E_STRICT);
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

date_default_timezone_set("Europe/Berlin");

define("APP_ROOT", dirname(__FILE__).$ds."..".$ds."src".$ds);
define("CACHE_ROOT", dirname(__FILE__).$ds."cache".$ds);
define("VENDOR_ROOT", dirname(__FILE__).$ds."..".$ds."vendor".$ds);
define("USERDATA_ROOT", dirname(__FILE__).$ds."uploads".$ds);
define("TRAIWI_CORE", VENDOR_ROOT."traiwi".$ds."traiwi".$ds."src".$ds."Core".$ds);
define("CLIENT_DIR", basename(dirname(__FILE__)));

include_once TRAIWI_CORE."Classloader.php";

$loader = new Traiwi\Core\Classloader(APP_ROOT);
$loader->register();

if(file_exists(VENDOR_ROOT."autoload.php")) {
	require_once VENDOR_ROOT."autoload.php";
}

use Traiwi\Core\Server;
use Traiwi\Core\Services\Config;

$client_config = new Config(dirname(__FILE__).$ds."config");
$client_config->defineConstants();

$server = new Server($client_config);
$server->run();

?>			
';

$cliConfig = '<?php
		
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once "bootstrap.php";
		
return ConsoleRunner::createHelperSet($entityManager);
?>';

$bootstrap = '<?php

require_once "../../vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Traiwi\Core\Services\Config;

$paths = array(
	getcwd() . "/../../vendor/traiwi/traiwi/src/Core/Entities/",
);
$isDevMode = true;

$clientConfig = new Config("../config");

// the connection configuration
$dbParams = array(
	"driver"   => "pdo_mysql",
	"user"     => $clientConfig->get("user"),
	"password" => $clientConfig->get("password"),
	"dbname"   => $clientConfig->get("dbname"),
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

?>';
		
$composer = '{
    "require": {
		"traiwi/traiwi": "dev-master",
		"scipper/formfile": "dev-master"
    },
    "repositories": [
		{"type": "composer", "url": "http://toran.myscipper.de/repo/private/"}
    ]
}
		
';
		
		$this->files = array(
			"client/config/config.ini" => $config,
			"client/.htaccess" => $htaccess,
			"client/main.php" => $main,
			"client/cli/cli-config.php" => $cliConfig,
			"client/cli/bootstrap.php" => $bootstrap,
			"composer.json" => $composer,
		);
		
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;
		$this->colorizer->cecho("TRAIWI Installation", Colorizer::FG_ORANGE); echo PHP_EOL;
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;

		if(!isset($this->argv[1])) {
			$this->error("No project name given");
		}
		
		$this->core = trim($this->argv[1]) . "/";
		$this->composer = $this->core . "composer.phar";
		$this->targetDir = getcwd() . DIRECTORY_SEPARATOR . $this->core;
		
		$this->checkPermission();
		$this->createFolders();
		$this->installComposer();
		$this->createFiles();
		$this->loadVendors();
		$this->linkBinaries();
		$this->finish();
	}
	
	/**
	 * 
	 */
	public function checkPermission() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Traiwi will be installed in: " . $this->targetDir, Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		
		if(isset($this->argv[2]) && $this->argv[2] == "--force") {
			$this->rrmdir($this->targetDir);
		}
		
		if(!@mkdir($this->targetDir, 0750, true)){
			$msg = "You have no permission to install TRAIWI in " . $this->targetDir . PHP_EOL;
			$msg .= "Append --force to override the current installtion";
			$this->error($msg);
		}
	}
	
	/**
	 * 
	 */
	public function createFolders() {
		$process = 0;

		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Creating folder structure for " . $this->core . ": ", Colorizer::FG_LIGHT_GRAY); 
		$this->colorizer->cecho($process . " %", Colorizer::FG_GREEN);
		
		foreach($this->folders as $k => $folder) {
			$path = $this->targetDir . DIRECTORY_SEPARATOR . $folder;
			if(!file_exists($path)) {
				mkdir($path, 0750, true);
			}
			
			$process = round((100 / count($this->folders)) * ($k + 1));
			echo "\r";

			$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Creating folder structure for " . $this->core . ": ", Colorizer::FG_LIGHT_GRAY);
			$this->colorizer->cecho($process . " %", Colorizer::FG_GREEN);
		}
		
		echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	public function installComposer() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);;
		$this->colorizer->cecho("Installing composer ", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		
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
		
		if(!file_put_contents($this->composer, $output)) {
			$this->error($this->composer . " could not be created");
		}
		
		if(!chmod($this->composer, 0755)) {
			$this->error("Permission for " . $this->composer . " could not be set");
		}
		
		system("php " . $this->composer . " -- --install-dir=" . $this->core);
	}
	
	/**
	 * 
	 */
	public function createFiles() {
		$process = 0;

		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Creating default system files: ", Colorizer::FG_LIGHT_GRAY);
		$this->colorizer->cecho($process . " %", Colorizer::FG_GREEN);
	
		$k = 0;
		foreach($this->files as $filename => $content) {
			$path = $this->targetDir . DIRECTORY_SEPARATOR . $filename;
			if(!file_exists($path)) {
				if(!file_put_contents($path, $content)) {
					$this->error($path . " could not be created");
				}
				
				if(!chmod($path, 0644)) {
					$this->error("Permission for " . $path . " could not be set");
				}
			}
				
			$process = round((100 / count($this->files)) * ($k + 1));
			echo "\r";

			$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Creating default system files: ", Colorizer::FG_LIGHT_GRAY);
			$this->colorizer->cecho($process . " %", Colorizer::FG_GREEN);
			
			$k++;
		}
		
		echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	public function loadVendors() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Loading vendors", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		system("php " . $this->composer . " --working-dir=" . $this->core . " update --prefer-dist");
	}
	
	/**
	 * 
	 */
	public function linkBinaries() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Link binaries", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		symlink("vendor" . DIRECTORY_SEPARATOR . "bin", $this->core . "bin");
	}
	
	/**
	 * 
	 */
	public function finish() {
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;
		$this->colorizer->cecho("Congratulation!", Colorizer::FG_GREEN); echo PHP_EOL;
		$this->colorizer->cecho("TRAIWI was successfully installed to: " . $this->targetDir, Colorizer::FG_GREEN); echo PHP_EOL;echo PHP_EOL;
		$this->colorizer->cecho("Available public packages, you can install with 'php " . $this->composer . " require vendor/package', are: ", Colorizer::FG_GREEN); echo PHP_EOL;
		$this->colorizer->cecho(" - traiwi/traiwi: The Application Core, already installed", Colorizer::FG_GREEN); echo PHP_EOL;
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;
	}
	
	public function rrmdir($dir) {
		if(!is_dir($dir)) {
			return;			
		}
		
		$objects = scandir($dir);
		foreach($objects as $object) {
			if($object == "." || $object == "..") {
				continue;	
			}
			
			if(filetype($dir."/".$object) == "dir") {
				$this->rrmdir($dir."/".$object); 
			} else {
				unlink($dir."/".$object);
			}
		}
		
		reset($objects);
		rmdir($dir);
	}
	
	/**
	 * 
	 * @param string $error
	 */
	public function error($error) {
		$this->colorizer->cecho($error, Colorizer::FG_RED); echo PHP_EOL; echo PHP_EOL;
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
