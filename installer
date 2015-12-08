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
	 * @var boolean
	 */
	protected $verbose;
	
	
	/**
	 * 
	 */
	public function __construct(Colorizer $colorizer, array $argv) {
		$this->colorizer = $colorizer;
		$this->argv = $argv;
		$this->verbose = false;
		$this->folders = array(
			"client",
			"client/cache",
			"client/config",
			"client/cli",
			"client/uploads",
			"client/logs",
			"src",
			"src/Core",
			"src/Modules",
			"shell",
			"shell/CSS",
			"shell/JS",
			"shell/Images",
			"shell/Fonts",
			"shell/Templates",
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
is_dev_mode="true"
custom_repository_factory=""
user_resolve_target="Traiwi"
';

$htaccess = 'RewriteEngine On
RewriteBase /

RewriteRule ^uploads/ - [L]

RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.css$ vendor/$1/$2/shell/CSS/$3.css [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.js$ vendor/$1/$2/shell/JS/$3.js [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.gif$ vendor/$1/$2/shell/Images/$3.gif [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.png$ vendor/$1/$2/shell/Images/$3.png [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.jpg$ vendor/$1/$2/shelll/Images/$3.jpg [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.eot$ vendor/$1/$2/shell/Fonts/$3.eot [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.eot?#iefix$ vendor/$1/$2/shell/Fonts/$3.eot [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.woff$ vendor/$1/$2/shell/Fonts/$3.woff [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.woff2$ vendor/$1/$2/shell/Fonts/$3.woff2 [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.ttf$ vendor/$1/$2/shell/Fonts/$3.ttf [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/(.*)\.svg#icomoon$ vendor/$1/$2/shell/Fonts/$2.svg [L]

RewriteRule ^(.*)\.css$ own/shell/CSS/$3.css [L]
RewriteRule ^(.*)\.js$ own/shell/JS/$3.js [L]
RewriteRule ^(.*)\.gif$ own/shell/Images/$3.gif [L]
RewriteRule ^(.*)\.png$ own/shell/Images/$3.png [L]
RewriteRule ^(.*)\.jpg$ own/shelll/Images/$3.jpg [L]
RewriteRule ^(.*)\.eot$ own/shell/Fonts/$3.eot [L]
RewriteRule ^(.*)\.eot?#iefix$ own/shell/Fonts/$3.eot [L]
RewriteRule ^(.*)\.woff$ own/shell/Fonts/$3.woff [L]
RewriteRule ^(.*)\.woff2$ own/shell/Fonts/$3.woff2 [L]
RewriteRule ^(.*)\.ttf$ own/shell/Fonts/$3.ttf [L]
RewriteRule ^(.*)\.svg#icomoon$ own/shell/Fonts/$2.svg [L]		
		
RewriteRule ^uploads/(.*)$ uploads/$1 [L]
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

define("APP_ROOT", dirname(__FILE__).$ds."..".$ds);
define("SRC_ROOT", dirname(__FILE__).$ds."..".$ds."src".$ds);
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

$mainDev = '<?php

ob_start();

$ds = DIRECTORY_SEPARATOR;

ini_set("expose_php","Off");
ini_set("log_errors",TRUE);
ini_set("error_log",dirname(__FILE__).$ds."logs".$ds."custom_error_log.txt");
error_reporting(E_ALL ^ E_STRICT);
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

date_default_timezone_set("Europe/Berlin");

define("APP_ROOT", dirname(__FILE__).$ds."..".$ds);
define("SRC_ROOT", dirname(__FILE__).$ds."..".$ds."src".$ds);
define("CACHE_ROOT", dirname(__FILE__).$ds."cache".$ds);
define("VENDOR_ROOT", dirname(__FILE__).$ds."..".$ds."vendor".$ds);
define("USERDATA_ROOT", dirname(__FILE__).$ds."uploads".$ds);
define("TRAIWI_CORE", VENDOR_ROOT."traiwi".$ds."traiwi".$ds."src".$ds."Core".$ds);
define("CLIENT_DIR", basename(dirname(__FILE__)));
		
		
$extensions = array("css");

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = pathinfo($path, PATHINFO_EXTENSION);
if (in_array($ext, $extensions)) {
	include VENDOR_ROOT . pathinfo($path, PATHINFO_DIRNAME) . "/shell/CSS/" . pathinfo($path, PATHINFO_BASENAME);
    // let the server handle the request as-is
    exit();  
}
		

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
use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\ResolveTargetEntityListener;
use Doctrine\ORM\Events;
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
		
$evm  = new EventManager();
$rtel = new ResolveTargetEntityListener();

$rtel->addResolveTargetEntity(
	"Traiwi\Core\Entities\BaseUserInterface", 
	$clientConfig->get("user_resolve_target") . "\Core\Entities\MysUser", 
	array()
);
$evm->addEventListener(Events::loadClassMetadata, $rtel);
		
$entityManager = EntityManager::create($dbParams, $config, $evm);

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
			"client/main_dev.php" => $mainDev,
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
		
		if(in_array("-v", $this->argv) || in_array("--verbose", $this->argv)) {
			$this->verbose = true;
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
		
		if(in_array("-f", $this->argv) || in_array("--force", $this->argv)) {
			$this->rrmdir($this->targetDir);
		}
		
		if(!@mkdir($this->targetDir, 0750, true)){
			$msg = "You have no permission to install TRAIWI in " . $this->targetDir . PHP_EOL;
			$msg .= "Append -f or --force to override the current installtion";
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
		
		if($this->verbose) {
			echo PHP_EOL;
		}
		
		foreach($this->folders as $k => $folder) {
			$path = $this->targetDir . $folder;
			if(!file_exists($path)) {
				if($this->verbose) {
					$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
					$this->colorizer->cecho("mkdir(" . $path . ", 0750, true)", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
				}
				mkdir($path, 0750, true);
			}
			
			$process = round((100 / count($this->folders)) * ($k + 1));
		}
		
		if($this->verbose) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Created folder structure for " . $this->core . ": ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho("✔", Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	public function installComposer() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);;
		$this->colorizer->cecho("Installing composer: ", Colorizer::FG_LIGHT_GRAY);
		
		if(!function_exists('curl_init')){
			$this->error("The php cURL extension is not installed");
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://getcomposer.org/installer");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		
		if(curl_errno($ch)) {
			$this->colorizer->cecho("✘", Colorizer::FG_RED); echo PHP_EOL;
			$this->error(curl_error($ch));
		}
		
		curl_close($ch);
		
		if(!file_put_contents($this->composer, $output)) {
			$this->colorizer->cecho("✘", Colorizer::FG_RED); echo PHP_EOL;
			$this->error($this->composer . " could not be created");
		}
		
		if(!chmod($this->composer, 0755)) {
			$this->colorizer->cecho("✘", Colorizer::FG_RED); echo PHP_EOL;
			$this->error("Permission for " . $this->composer . " could not be set");
		}

		$this->execCommand(
			"php " . $this->composer . " -- --install-dir=" . $this->core, 
			"Installing composer: "
		);
		
		if($this->verbose) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Installed composer: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho("✔", Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	public function createFiles() {
		$process = 0;

		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Creating default system files: ", Colorizer::FG_LIGHT_GRAY);
		
		if($this->verbose) {
			echo PHP_EOL;
		}
	
		$k = 0;
		foreach($this->files as $filename => $content) {
			$path = $this->targetDir . $filename;
			if(!file_exists($path)) {
				if(!file_put_contents($path, $content)) {
					$this->error($path . " could not be created");
				}
				
				if($this->verbose) {
					$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
					$this->colorizer->cecho("chmod(" . $path . ", 0644)", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
				}
				
				if(!chmod($path, 0644)) {
					$this->error("Permission for " . $path . " could not be set");
				}
			}
				
			$process = round((100 / count($this->files)) * ($k + 1));

			$k++;
		}
		
		if($this->verbose) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Created default system files: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho("✔", Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	public function loadVendors() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Loading vendors: ", Colorizer::FG_LIGHT_GRAY); 
		
		$this->execCommand(
			"php " . $this->composer . " --working-dir=" . $this->core . " update --prefer-dist", 
			"Loading vendors: "
		);
		
		if($this->verbose) {
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Loaded vendors: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho("✔", Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	public function linkBinaries() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Generate Symlinks: ", Colorizer::FG_LIGHT_GRAY);

		if($this->verbose) {
			echo PHP_EOL;
		}
		
		if($this->verbose) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("symlink(vendor" . DIRECTORY_SEPARATOR . "bin, bin)", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		}
		if(!symlink("vendor" . DIRECTORY_SEPARATOR . "bin", $this->core . "bin")) {
			$this->colorizer->cecho("✘", Colorizer::FG_RED); echo PHP_EOL;
			$this->error("binaries could not be linked");
		}

		if($this->verbose) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("symlink(.." . DIRECTORY_SEPARATOR . "vendor, client" . DIRECTORY_SEPARATOR . "vendor)", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		}
		if(!symlink(".." . DIRECTORY_SEPARATOR . "vendor", $this->core . "client" . DIRECTORY_SEPARATOR . "vendor")) {
			$this->colorizer->cecho("✘", Colorizer::FG_RED); echo PHP_EOL;
			$this->error("vendors could not be linked to client");
		}

		if($this->verbose) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("symlink(.." . DIRECTORY_SEPARATOR . "shell, client" . DIRECTORY_SEPARATOR . "own)", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		}
		if(!symlink(".." . DIRECTORY_SEPARATOR . "shell", $this->core . "client" . DIRECTORY_SEPARATOR . "own")) {
			$this->colorizer->cecho("✘", Colorizer::FG_RED); echo PHP_EOL;
			$this->error("own shell could not be linked to client");
		}
		
		if($this->verbose) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Generated Symlinks: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho("✔", Colorizer::FG_GREEN); echo PHP_EOL;
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
	
	/**
	 * 
	 * @param string $cmd
	 * @param string $action
	 */
	public function execCommand($cmd, $action) {
		$result = array();
		$status = NULL;
		
		if($this->verbose) {
			$cmd .= " 2>&1 | tee " . getcwd() . "/traiwi_install.log";
		} else {
			$cmd .= " 1> /dev/null 2>> " . getcwd() . "/traiwi_install.log";
		}
		
		system($cmd, $status);
		
		if($status > 0) {
			$this->colorizer->cecho("✘", Colorizer::FG_RED); echo PHP_EOL;
			$this->error("See traiwi_install.log for more details. ");
		}
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
