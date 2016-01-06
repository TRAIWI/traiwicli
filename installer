#!/usr/bin/env php

<?php

$cliArgs = is_array($argv) ? $argv : array();
$traiwiCli = new TraiwiCli(
	new Colorizer(), 
	new TraiwiFileContainer(),
	$cliArgs
);
$traiwiCli->start();

/**
 * 
 * @author Steffen Kowalski <sk@traiwi.de>
 * 
 * @since 02.12.2015
 * @package TRAIWICLI
 *
 */
class TraiwiCli {
	
	/**
	 * 
	 * @var string
	 */
	protected $version;
	
	/**
	 * 
	 * @var string
	 */
	protected $installerTitle;
	
	/**
	 * 
	 * @var Colorizer
	 */
	protected $colorizer;
	
	/**
	 * 
	 * @var TraiwiFileContainer
	 */
	protected $fileContainer;
	
	/**
	 * 
	 * @var string
	 */
	protected $targetDir;
	
	/**
	 * 
	 * @var string
	 */
	protected $projectname;
	
	/**
	 * 
	 * @var string
	 */
	protected $vendor;
	
	/**
	 * 
	 * @var string
	 */
	protected $package;
	
	/**
	 * 
	 * @var string
	 */
	protected $namespace;
	
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
	 * @var boolean
	 */
	protected $isWindows;
	
	/**
	 * 
	 * @var string
	 */
	protected $symbolOk;
	
	/**
	 * 
	 * @var string
	 */
	protected $symbolError;
	
	/**
	 * 
	 * @var string
	 */
	protected $ds;
	
	/**
	 * 
	 * @var string
	 */	
	protected $toranUser;
	
	/**
	 * 
	 * @var string
	 */	
	protected $toranPass;
	
	/**
	 * 
	 * @var string
	 */	
	protected $mysqlUser;
	
	/**
	 * 
	 * @var string
	 */	
	protected $mysqlPass;
	
	/**
	 * 
	 * @var string
	 */
	protected $command;
	
	/**
	 * 
	 * @var array
	 */
	protected $availableCommands;
	
	
	/**
	 * 
	 */
	public function __construct(Colorizer $colorizer, TraiwiFileContainer $container, array $argv) {
		$this->version = "1.1.1";
		$this->installerTitle = "TRAIWI CLI";
		
		$this->isWindows = strtoupper(substr(PHP_OS, 0, 3)) === "WIN";
		$this->ds = DIRECTORY_SEPARATOR;
		
		$this->colorizer = $colorizer;
		$this->colorizer->setIsWindows($this->isWindows);
		$this->fileContainer = $container;
		
		if(!$this->isWindows) {
			$this->symbolOk = "✔";
			$this->symbolError = "✘";
		} else {
			$this->symbolOk = "OK";
			$this->symbolError = "X";
		}
		
		$this->argv = $argv;
		$this->verbose = false;
		$this->targetDir = getcwd() . $this->ds;
		$this->composer = $this->targetDir . "composer.phar";
		$this->projectname = @$argv[2];
		
		$this->files = array(
			".htaccess" => $this->fileContainer->getHtacces(),
			"client" . $this->ds . "main.php" => $this->fileContainer->getMain(),
			"client" . $this->ds . "main_dev.php" => $this->fileContainer->getMainDev(),
			"client" . $this->ds . "cli" . $this->ds . "cli-config.php" => $this->fileContainer->getCliConfig(),
			"client" . $this->ds . "cli" . $this->ds . "bootstrap.php" => $this->fileContainer->getBootstrap(),
		);
		
		$this->folders = array(
			"client",
			"client" . $this->ds . "cache",
			"client" . $this->ds . "config",
			"client" . $this->ds . "cli",
			"client" . $this->ds . "uploads",
			"client" . $this->ds . "logs",
			"src",
			"src" . $this->ds . "Core",
			"src" . $this->ds . "Modules",
			"shell",
			"shell" . $this->ds . "CSS",
			"shell" . $this->ds . "JS",
			"shell" . $this->ds . "Images",
			"shell" . $this->ds . "Fonts",
			"shell" . $this->ds . "Templates",
		);
		
		$this->command = "";
		$this->availableCommands = array(
			"create-new" => "create a new empty project.",
			"install" => "install an existing project.",
// 			"self-update" => "update this installer to the current version",
		);
	}
	
	/**
	 * 
	 */
	public function start() {
		$this->checkParameter();
		
		$command = $this->command;
		$this->$command();
	}
	
	/**
	 * 
	 */
	protected function welcomeMessage() {
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;
		$this->colorizer->cecho($this->installerTitle, Colorizer::FG_ORANGE); echo PHP_EOL;
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	protected function commandCreateNew() {
		$this->checkPermission();
		$this->createFolders();
		$this->installComposer();
		$this->addingPrivateRepo();
		$this->createComposerJson();
		$this->enterCredentials();
		$this->createFiles();
		$this->loadVendors();
		$this->linkBinaries();
		$this->finishCreateNew();
	}
	
	/**
	 * 
	 */
	protected function commandInstall() {
		$this->checkPermission();
		$this->installComposer();
		$this->addingPrivateRepo();
		$this->createComposerJson();
		$this->enterCredentials();
		$this->createProject();
		$this->loadVendors();
		$this->linkBinaries();
		$this->finishInstall();
	}
	
	/**
	 * 
	 */
	protected function commandGetcwd() {
		$this->colorizer->cecho(getcwd(), Colorizer::FG_LIGHT_GRAY); echo PHP_EOL; echo PHP_EOL;
			
		exit();
	}
	
	/**
	 * 
	 */
	protected function checkPermission() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Project will be installed in: " . $this->targetDir, Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		
		$force = in_array("-f", $this->argv) || in_array("--force", $this->argv);
		if($force) {
			$this->rrmdir($this->targetDir);
		}
		
		if(count(scandir($this->targetDir)) > 2 && !$force) {
			$msg = "This directory is not empty." . PHP_EOL;
			$msg .= "Append -f or --force to override the current installation";
			$this->error($msg);
		}
	}
	
	/**
	 * 
	 */
	protected function createFolders() {
		$process = 0;

		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Creating folder structure for " . $this->projectname . ": ", Colorizer::FG_LIGHT_GRAY); 
		
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
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Created folder structure for " . $this->projectname . ": ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho($this->symbolOk, Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	protected function installComposer() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);;
		$this->colorizer->cecho("Installing composer: ", Colorizer::FG_LIGHT_GRAY);
		
		if(!function_exists('curl_init')){
			$this->error("The php cURL extension is not installed");
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://getcomposer.org/installer");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($ch);
		
		if(curl_errno($ch)) {
			$this->colorizer->cecho($this->symbolError, Colorizer::FG_RED); echo PHP_EOL;
			$this->error(curl_error($ch));
		}
		
		curl_close($ch);
		
		if(!file_put_contents($this->composer, $output)) {
			$this->colorizer->cecho($this->symbolError, Colorizer::FG_RED); echo PHP_EOL;
			$this->error($this->composer . " could not be created");
		}
		
		if(!chmod($this->composer, 0755)) {
			$this->colorizer->cecho($this->symbolError, Colorizer::FG_RED); echo PHP_EOL;
			$this->error("Permission for " . $this->composer . " could not be set");
		}

		$this->execCommand(
			"php " . $this->composer . " -- --install-dir=" . $this->targetDir, 
			"Installing composer: "
		);
		
		if($this->verbose) {
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Installed composer: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho($this->symbolOk, Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 *
	 */
	protected function addingPrivateRepo() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Adding private repo: ", Colorizer::FG_LIGHT_GRAY);
	
		if($this->verbose) {
			echo PHP_EOL;
		}
	
		$this->execCommand(
			"php " . $this->composer . " config -g repositories.myscipper composer http://toran.myscipper.de/repo/private/",
			"Adding private repo"
		);
	
		if($this->verbose) {
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Added private repo: ", Colorizer::FG_LIGHT_GRAY);
		}
	
		$this->colorizer->cecho($this->symbolOk, Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	protected function createComposerJson() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Creating composer json: ", Colorizer::FG_LIGHT_GRAY);
		
		if($this->verbose) {
			echo PHP_EOL;
		}
	
		$filename = "composer.json";
		$path = $this->targetDir . $filename;
		$content = $this->fileContainer->getComposerJson($this->projectname);
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

		if($this->verbose) {
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Created composer json: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho($this->symbolOk, Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	protected function enterCredentials() {
		$handle = fopen("php://stdin","r");
		
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Enter toran private repository credentials: ", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("    - username: ", Colorizer::FG_LIGHT_GRAY);
		$this->toranUser = escapeshellcmd(trim(fgets($handle)));
		echo PHP_EOL;
		
		$this->colorizer->cecho("    - password: ", Colorizer::FG_LIGHT_GRAY);
		$this->colorizer->hide();
		$this->toranPass = escapeshellcmd(trim(fgets($handle)));
		$this->colorizer->restore();
		echo PHP_EOL;
		
		$composerHome = $this->execCommand(
			"php " . $this->composer . " --working-dir=" . $this->targetDir . " config home", 
			"Storing credentials"
		);
		
		if(!file_exists($composerHome . $this->ds . "auth.json")) {
			touch($composerHome . $this->ds . "auth.json");
		}
		
		$authJson = file_get_contents($composerHome . $this->ds . "auth.json");
		$authArray = json_decode($authJson, JSON_OBJECT_AS_ARRAY);
		
		$authArray["http-basic"]["toran.myscipper.de"] = array(
			"username" => $this->toranUser,
			"password" => $this->toranPass
		);
		
		$newAuthJson = json_encode($authArray, JSON_FORCE_OBJECT);
		file_put_contents($composerHome . $this->ds . "auth.json", $newAuthJson);
		
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Enter mysql credentials: ", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("    - username: ", Colorizer::FG_LIGHT_GRAY);
		$this->mysqlUser = escapeshellcmd(trim(fgets($handle)));
		echo PHP_EOL;
		
		$this->colorizer->cecho("    - password: ", Colorizer::FG_LIGHT_GRAY);
		$this->colorizer->hide();
		$this->mysqlPass = escapeshellcmd(trim(fgets($handle)));
		$this->colorizer->restore();
		echo PHP_EOL;
		
		fclose($handle);
	}
	
	/**
	 * 
	 */
	protected function createFiles() {
		$process = 0;

		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Creating default system files: ", Colorizer::FG_LIGHT_GRAY);
		
		if($this->verbose) {
			echo PHP_EOL;
		}
		
		$path = $this->targetDir . "client" . $this->ds . "config" . $this->ds . "config.ini";
		$content = $this->fileContainer->getConfig($this->vendor, $this->package, $this->mysqlUser, $this->mysqlPass);
		$this->createFile($path, $content);
	
		$k = 0;
		foreach($this->files as $filename => $content) {
			$path = $this->targetDir . $filename;
			$this->createFile($path, $content);
			
			$process = round((100 / count($this->files)) * ($k + 1));

			$k++;
		}

		$path = $this->targetDir . "src" . $this->ds . ucfirst(strtolower($this->package)) . "Bundle.php";
		$content = $this->fileContainer->getBundle(ucfirst(strtolower($this->vendor)), ucfirst(strtolower($this->package)));
		$this->createFile($path, $content);
		
		if($this->verbose) {
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Created default system files: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho($this->symbolOk, Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 * @param string $path
	 * @param string $content
	 */
	private function createFile($path, $content) {
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
	}
	
	/**
	 *
	 */
	protected function createProject() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Creating project: ", Colorizer::FG_LIGHT_GRAY); 
		
		if($this->verbose) {
			echo PHP_EOL;
		}

		$this->execCommand(
			"php " . $this->composer . " --working-dir=" . $this->targetDir . " create-project " . $this->projectname . " tmp --stability dev --keep-vcs",
			"Checking out package with composer"
		);
		
		$this->rcopy($this->targetDir . "tmp", $this->targetDir);
		$this->rrmdir($this->targetDir . "tmp");
	
		if($this->verbose) {
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Created project: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho($this->symbolOk, Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	protected function loadVendors() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Loading vendors: ", Colorizer::FG_LIGHT_GRAY); 
		
		$this->execCommand(
			"php " . $this->composer . " --working-dir=" . $this->targetDir . ($this->isWindows ? " install" : " update --prefer-dist"), 
			"Loading vendors: "
		);
		
		if($this->verbose) {
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Loaded vendors: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho($this->symbolOk, Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	protected function linkBinaries() {
		$this->colorizer->cecho("$ ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Generate Symlinks: ", Colorizer::FG_LIGHT_GRAY);

		if($this->verbose) {
			echo PHP_EOL;
		}
		
		if($this->verbose) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("symlink(vendor" . $this->ds . "bin, bin)", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		}
		if(!file_exists($this->targetDir . "vendor/bin")) {
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_RED);
			$this->colorizer->cecho("Linking to vendor/bin not possible, folder does not exist.", Colorizer::FG_LIGHT_RED); echo PHP_EOL;
		} else {
			if(!symlink("vendor" . $this->ds . "bin", "bin")) {
				$this->colorizer->cecho($this->symbolError, Colorizer::FG_RED); echo PHP_EOL;
				$this->error("binaries could not be linked");
			}
		}

		if($this->verbose) {
			echo PHP_EOL;
			$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
			$this->colorizer->cecho("Generated Symlinks: ", Colorizer::FG_LIGHT_GRAY);
		}
		
		$this->colorizer->cecho($this->symbolOk, Colorizer::FG_GREEN); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	protected function finishCreateNew() {
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;
		$this->colorizer->cecho("Congratulation!", Colorizer::FG_GREEN); echo PHP_EOL;
		$this->colorizer->cecho("Your project was successfully installed to: " . $this->targetDir, Colorizer::FG_GREEN); echo PHP_EOL;echo PHP_EOL;
		$this->colorizer->cecho("Available packages, you can install with 'php " . $this->composer . " require vendor/package', are: ", Colorizer::FG_GREEN); echo PHP_EOL;
		$this->colorizer->cecho(" - traiwi/traiwicore: The Application Core, already installed", Colorizer::FG_GREEN); echo PHP_EOL;
		$this->colorizer->cecho(" - scipper/haushalt: Haushaltsbuch", Colorizer::FG_GREEN); echo PHP_EOL;
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;

		$this->colorizer->cecho("What's next?", Colorizer::FG_ORANGE); echo PHP_EOL;
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;

		$this->colorizer->cecho("To let your installation work, there are 4 simple steps to do:", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL; echo PHP_EOL;
		
		$step2 = "mysql -u " . $this->mysqlUser . " -p" . $this->mysqlPass . " -e 'CREATE DATABASE " . $this->package . " CHARACTER SET utf8 COLLATE utf8_general_ci';";
		$this->colorizer->cecho("1. ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Create a database with: ", Colorizer::FG_LIGHT_GRAY);
		$this->colorizer->cecho($step2, Colorizer::FG_LIGHT_BLUE); echo PHP_EOL;
		
		$step3 = "php bin/traiwicli orm:schema-tool:update --force";
		$this->colorizer->cecho("2. ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Create the database scheme with the doctrine wrapper 'traiwicli': ", Colorizer::FG_LIGHT_GRAY);
		$this->colorizer->cecho($step3, Colorizer::FG_LIGHT_BLUE); echo PHP_EOL;
		
		$step4 = "php -S localhost:8080 client/main_dev.php";
		$this->colorizer->cecho("3. ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Run the php server to start development: ", Colorizer::FG_LIGHT_GRAY);
		$this->colorizer->cecho($step4, Colorizer::FG_LIGHT_BLUE); echo PHP_EOL;
		
		$this->colorizer->cecho("4. ", Colorizer::FG_LIGHT_BLUE);
		$this->colorizer->cecho("Log in with the following data: ", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL; 
		$this->colorizer->cecho("    - email: ", Colorizer::FG_LIGHT_GRAY); 
		$this->colorizer->cecho("user@traiwi.de", Colorizer::FG_LIGHT_BLUE); echo PHP_EOL; 
		$this->colorizer->cecho("    - password: ", Colorizer::FG_LIGHT_GRAY); 
		$this->colorizer->cecho("secure", Colorizer::FG_LIGHT_BLUE); echo PHP_EOL;  echo PHP_EOL;
		
		$this->colorizer->cecho("OR: all in one: ", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;

		$this->colorizer->cecho($step2 . " \\", Colorizer::FG_LIGHT_BLUE); echo PHP_EOL;
		$this->colorizer->cecho($step3 . " ;\\", Colorizer::FG_LIGHT_BLUE); echo PHP_EOL;
		$this->colorizer->cecho($step4, Colorizer::FG_LIGHT_BLUE); echo PHP_EOL;
	}
	
	/**
	 * 
	 */
	protected function finishInstall() {
		$this->colorizer->cecho("______________________________________________________________________________", Colorizer::FG_DARK_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("                   "); echo PHP_EOL;
		$this->colorizer->cecho("Congratulation!", Colorizer::FG_GREEN); echo PHP_EOL;
		$this->colorizer->cecho("Your project '" . $this->projectname . "' was successfully cloned to: " . $this->targetDir, Colorizer::FG_GREEN); echo PHP_EOL;echo PHP_EOL;
	}
	
	/**
	 * 
	 * @param string $dir
	 */
	protected function rrmdir($dir) {
		if(!is_dir($dir)) {
			return;			
		}
		
		$objects = scandir($dir);
		foreach($objects as $object) {
			if($object == "." || 
					$object == "..") {
				continue;	
			}
			
			if(filetype($dir . $this->ds . $object) == "dir") {
				$this->rrmdir($dir . $this->ds . $object); 
			} else {
				unlink($dir . $this->ds . $object);
			}
		}
		
		reset($objects);
		
		if($dir == $this->targetDir) {
			return;
		}
		
		rmdir($dir);
	}
	
	/**
	 * 
	 * @param string $src
	 * @param string $dst
	 */
	protected function rcopy($src, $dst) {
		$dir = opendir($src);
		@mkdir($dst, 0750, true);
		while(false !== ($file = readdir($dir))) {
			if(($file != ".") && ($file != "..")) {
				if(is_dir($src . "/" . $file)) {
					$this->rcopy($src . "/" . $file, $dst . "/" . $file);
				} else {
					if($this->verbose) {
						$this->colorizer->cecho(" > ", Colorizer::FG_LIGHT_BLUE);
						$this->colorizer->cecho("copy(" . $src . "/" . $file, $dst . "/" . $file .")", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
					}
					
					copy($src . "/" . $file, $dst . "/" . $file);
				}
			}
		}
		closedir($dir);
	}
	
	/**
	 * 
	 * @param string $error
	 * @param boolean $exit
	 */
	protected function error($error, $exit = true) {
		$this->colorizer->cecho($error, Colorizer::FG_RED); echo PHP_EOL; echo PHP_EOL;
		
		if($exit) {
			exit;
		}
	}
	
	/**
	 * 
	 * @param string $cmd
	 * @param string $action
	 * @return string
	 */
	protected function execCommand($cmd, $action) {
		$result = array();
		$status = NULL;
		
		if($this->verbose) {
			if($this->isWindows) {
// 				$cmd .= " 1> " . getcwd() . $this->ds . "install.log 2>&1";
				$cmd .= " 2> " . getcwd() . $this->ds . "install.log";
			} else {
// 				$cmd .= " 2>&1 | tee " . getcwd() . $this->ds . "install.log";
				$cmd .= " 2> " . getcwd() . $this->ds . "install.log";
			}
			
			$this->colorizer->cecho("exec(" . $cmd . ")", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		} else {
			if($this->isWindows) {
				$cmd .= " 2> " . getcwd() . $this->ds . "install.log";
			} else {
				$cmd .= " 2> " . getcwd() . $this->ds . "install.log";
			}
		}
		
		exec($cmd, $result, $status);
		
		if($status > 0) {
			$this->colorizer->cecho($this->symbolError, Colorizer::FG_RED); echo PHP_EOL;
			$this->error("See install.log for more details. ");
		}
		
		if(isset($result[0])) {
			return $result[0];
		}
		
		return "";
	}
	
	/**
	 * 
	 */
	protected function checkParameter() {
		if(in_array("-h", $this->argv) || in_array("--help", $this->argv)) {
			$this->welcomeMessage();
			
			$this->displayHelp();
		}
		
		if(in_array("-V", $this->argv)) {
			$this->colorizer->cecho($this->installerTitle, Colorizer::FG_ORANGE);
			$this->colorizer->cecho(" version", Colorizer::FG_LIGHT_GRAY);
			$this->colorizer->cecho(" " . $this->version, Colorizer::FG_GREEN); echo PHP_EOL; echo PHP_EOL;
			
			exit();
		}
		
		$this->welcomeMessage();
		
		if(!isset($this->argv[1]) || substr($this->argv[1], 0, 1) == "-") {
			$this->error("No command given.", false);
			
			$this->displayHelp();
		}
		
		if(!array_key_exists($this->argv[1], $this->availableCommands)) {
			$this->error("Invalid command.", false);
				
			$this->displayHelp();
		}
		
		$parts = explode("-", $this->argv[1]);
		$command = "command";
		foreach($parts as $part) {
			$command .= ucfirst(strtolower($part));
		}
		$this->command = $command;
		
		if(!isset($this->argv[2]) || substr($this->argv[2], 0, 1) == "-") {
			$this->error("No project name given. vendor/package", false);
			
			$this->displayHelp();
		}
		
		if(!preg_match("/([^A-Za-z0-9]?)(\/)([^A-Za-z0-9]?)/", $this->argv[2])) {
			$this->error("Invalid project name. vendor/package.", false);
			
			$this->displayHelp();
		}
		
		$parts = explode("/", $this->argv[2]);
		$this->vendor = $parts[0];
		$this->package = $parts[1];
		$this->projectname = $this->argv[2];
		$this->namespace = ucfirst(strtolower($this->vendor)) . "\\" . ucfirst(strtolower($this->package));
		
		if(in_array("-v", $this->argv) || in_array("--verbose", $this->argv)) {
			$this->verbose = true;
		}	
	}
	
	/**
	 * 
	 */
	protected function displayHelp() {
		$this->colorizer->cecho("Usage:", Colorizer::FG_ORANGE); echo PHP_EOL;
		$this->colorizer->cecho(" command vendor/project [options]", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL; echo PHP_EOL;
		
		$this->colorizer->cecho("Commands:", Colorizer::FG_ORANGE); echo PHP_EOL;
		foreach($this->availableCommands as $command => $description) {
			$this->colorizer->cecho("  " . $command, Colorizer::FG_GREEN);
			$this->colorizer->cecho("\t " . $description, Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		}
		echo PHP_EOL;
		
		$this->colorizer->cecho("Options:", Colorizer::FG_ORANGE); echo PHP_EOL;
		$this->colorizer->cecho("  -V", Colorizer::FG_GREEN);
		$this->colorizer->cecho("\t\t Display this installer version", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("  -h, --help", Colorizer::FG_GREEN);
		$this->colorizer->cecho("\t Display this help", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("  -f, --force", Colorizer::FG_GREEN);
		$this->colorizer->cecho("\t Overwrites the given project folder", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		$this->colorizer->cecho("  -v, --verbose", Colorizer::FG_GREEN);
		$this->colorizer->cecho("\t Detailed information about every step", Colorizer::FG_LIGHT_GRAY); echo PHP_EOL;
		
		
		exit;
	}

}


/**
 * 
 * @author Steffen Kowalski <sk@traiwi.de>
 *
 * @since 21.12.2015
 * @package TRAIWICLI
 *
 */
class TraiwiFileContainer {
	
	/**
	 * 
	 * @param string $package
	 * @param string $mysqlUser
	 * @param string $mysqlPass
	 * @param string $namespace
	 * @return string
	 */
	public function getConfig($vendor, $package, $mysqlUser, $mysqlPass) {
			return '[mysql]
host="127.0.0.1"
dbname="' . $package . '"
user="' . $mysqlUser . '"
password="' . $mysqlPass . '"
		
[system]
default_lang="de"
default_title="TRAIWI"
logging="on"
password_salt="123"
password_reset_salt="456"
lowest_role="GUEST"
is_dev_mode="true"
custom_repository_factory=""
user_resolve_target="Traiwi\Traiwicore"
		
[bundles]
traiwi="Traiwi\Traiwicore\TraiwiBundle"
' . strtolower($vendor . $package) . '="' . ucfirst(strtolower($vendor)) . '\\' . ucfirst(strtolower($package)) . '\\' . ucfirst(strtolower($package)) . 'Bundle"
';
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getHtacces() {
		return 'RewriteEngine On
RewriteEngine On
RewriteBase /
		
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.css$ vendor/$1/$2/shell/CSS/$3.css [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.js$ vendor/$1/$2/shell/JS/$3.js [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.gif$ vendor/$1/$2/shell/Images/$3.gif [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.png$ vendor/$1/$2/shell/Images/$3.png [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.jpg$ vendor/$1/$2/shelll/Images/$3.jpg [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.eot$ vendor/$1/$2/shell/Fonts/$3.eot [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.eot?#iefix$ vendor/$1/$2/shell/Fonts/$3.eot [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.woff$ vendor/$1/$2/shell/Fonts/$3.woff [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.woff2$ vendor/$1/$2/shell/Fonts/$3.woff2 [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.ttf$ vendor/$1/$2/shell/Fonts/$3.ttf [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.svg#icomoon$ vendor/$1/$2/shell/Fonts/$3.svg [L]
	
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.css$ shell/CSS/$3.css [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.js$ shell/JS/$3.js [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.gif$ shell/Images/$3.gif [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.png$ shell/Images/$3.png [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.jpg$ shelll/Images/$3.jpg [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.eot$ shell/Fonts/$3.eot [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.eot?#iefix$ shell/Fonts/$3.eot [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.woff$ shell/Fonts/$3.woff [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.woff2$ shell/Fonts/$3.woff2 [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.ttf$ shell/Fonts/$3.ttf [L]
RewriteRule ^([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)/([a-zA-Z0-9_]*)\.svg#icomoon$ shell/Fonts/$2.svg [L]
		
RewriteRule ^uploads/(.*)$ client/uploads/$1 [L]
RewriteRule ^(.*)\.ico$ shell/Images/$1.ico [L]
RewriteRule ^(.*)$ client/main.php?url=$1 [QSA,L]
			
';
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getMain() {
		return '<?php
		
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
define("TRAIWI_CORE", VENDOR_ROOT."traiwi".$ds."traiwicore".$ds."src".$ds."Core".$ds);
define("CLIENT_DIR", basename(dirname(__FILE__)));
		
include_once TRAIWI_CORE."Classloader.php";
		
$loader = new Traiwi\Traiwicore\Core\Classloader(SRC_ROOT);
$loader->register();
		
if(file_exists(VENDOR_ROOT."autoload.php")) {
	require_once VENDOR_ROOT."autoload.php";
}
		
use Traiwi\Traiwicore\Core\Server;
use Traiwi\Traiwicore\Core\Services\Config;
		
$client_config = new Config(dirname(__FILE__).$ds."config");
$client_config->defineConstants();
$client_config->initBundles();
		
$server = new Server($client_config);
$server->run();
		
?>
';
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getMainDev() {
		return '<?php
		
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
define("SHELL_ROOT", dirname(__FILE__).$ds."..".$ds."shell".$ds);
define("CACHE_ROOT", dirname(__FILE__).$ds."cache".$ds);
define("VENDOR_ROOT", dirname(__FILE__).$ds."..".$ds."vendor".$ds);
define("USERDATA_ROOT", dirname(__FILE__).$ds."uploads".$ds);
define("TRAIWI_CORE", VENDOR_ROOT."traiwi".$ds."traiwicore".$ds."src".$ds."Core".$ds);
define("CLIENT_DIR", basename(dirname(__FILE__)));
		
		
$extensions = array(
	"css" => "text/css",
	"js" => "text/javascript",
	"png" => "image/png",
	"jpg" => "image/jpeg",
	"jpeg" => "image/jpeg",
	"gif" => "image/gif",
	"eot" => "application/vnd.ms-fontobject",
	"woff" => "application/font-woff",
	"woff2" => "application/font-woff",
	"ttf" => "application/font-ttf",
	"svg" => "image/svg+xml",
);
		
$folders = array(
	"css" => "CSS",
	"js" => "JS",
	"png" => "Images",
	"jpg" => "Images",
	"jpeg" => "Images",
	"gif" => "Images",
	"eot" => "Fonts",
	"woff" => "Fonts",
	"woff2" => "Fonts",
	"ttf" => "Fonts",
	"svg" => "Fonts",
);
		
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = pathinfo($path, PATHINFO_EXTENSION);
if(array_key_exists($ext, $extensions)) {
	$vendorFile = VENDOR_ROOT . pathinfo($path, PATHINFO_DIRNAME) . $ds . "shell" . $ds . $folders[$ext] . $ds . pathinfo($path, PATHINFO_BASENAME);
	$clientFile = SHELL_ROOT . $folders[$ext] . $ds . pathinfo($path, PATHINFO_BASENAME);
	if(is_readable($vendorFile)) {
		header("Content-Type: " . $extensions[$ext]);
		readfile($vendorFile);
	} elseif(is_readable($clientFile)) {
		header("Content-Type: " . $extensions[$ext]);
		readfile($clientFile);
	}
	
    return;  
}
		
		
include_once TRAIWI_CORE."Classloader.php";
		
$loader = new Traiwi\Traiwicore\Core\Classloader(SRC_ROOT);
$loader->register();
		
if(file_exists(VENDOR_ROOT."autoload.php")) {
	require_once VENDOR_ROOT."autoload.php";
}
		
use Traiwi\Traiwicore\Core\Server;
use Traiwi\Traiwicore\Core\Services\Config;
		
$client_config = new Config(dirname(__FILE__).$ds."config");
$client_config->defineConstants();
$client_config->initBundles();
		
$server = new Server($client_config);
$server->run();
		
?>
';
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getCliConfig() {
		return '<?php
		
use Doctrine\ORM\Tools\Console\ConsoleRunner;
		
require_once "bootstrap.php";
		
return ConsoleRunner::createHelperSet($entityManager);
?>';
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBootstrap() {
		return  '<?php
		
$ds = DIRECTORY_SEPARATOR;

require_once ".." . $ds . ".." . $ds . "vendor" . $ds . "autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\ResolveTargetEntityListener;
use Doctrine\ORM\Events;
use Traiwi\Traiwicore\Core\Services\Config;

$paths = array(
	getcwd() . $ds . ".." . $ds . ".." . $ds . "vendor" . $ds . "traiwi" . $ds . "traiwicore" . $ds . "src" . $ds . "Core" . $ds . "Entities" . $ds,
);
$isDevMode = true;

$clientConfig = new Config(".." . $ds . "config");

// the connection configuration
$dbParams = array(
	"driver"   => "pdo_mysql",
	"user"     => $clientConfig->get("mysql", "user"),
	"password" => $clientConfig->get("mysql", "password"),
	"dbname"   => $clientConfig->get("mysql", "dbname"),
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
		
$evm  = new EventManager();
$rtel = new ResolveTargetEntityListener();

$rtel->addResolveTargetEntity(
	"Traiwi\Traiwicore\Core\Entities\BaseUserInterface", 
	$clientConfig->get("system", "user_resolve_target") . "\Core\Entities\MysUser", 
	array()
);
$evm->addEventListener(Events::loadClassMetadata, $rtel);
		
$entityManager = EntityManager::create($dbParams, $config, $evm);

?>';
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getComposerJson($projectname) {
		return '{
	"name": "' . $projectname . '",
    "require": {
		"traiwi/traiwicore": "dev-develop",
		"scipper/formfile": "dev-master"
    }
}
		
';
	}
	
	public function getBundle($vendor, $package) {
		return '<?php

namespace ' . $vendor . "\\" . $package . ';

use Traiwi\Traiwicore\Core\Interfaces\TraiwiBundleInterface;

class ' . $package . 'Bundle implements TraiwiBundleInterface {
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \Traiwi\Traiwicore\Core\Interfaces\TraiwiBundleInterface::getPath()
	 */
	public function getPath() {
		return dirname(__FILE__) . DIRECTORY_SEPARATOR;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \Traiwi\Traiwicore\Core\Interfaces\TraiwiBundleInterface::getTemplatePath()
	 */
	public function getTemplatePath() {
		return $this->getPath() . ".." . DIRECTORY_SEPARATOR . "shell" . DIRECTORY_SEPARATOR . "Templates" . DIRECTORY_SEPARATOR;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \Traiwi\Traiwicore\Core\Interfaces\TraiwiBundleInterface::getModulePath()
	 */
	public function getModulePath() {
		return $this->getPath() . "Modules" . DIRECTORY_SEPARATOR;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \Traiwi\Traiwicore\Core\Interfaces\TraiwiBundleInterface::getModuleNamespace()
	 */
	public function getModuleNamespace() {
		return __NAMESPACE__ . "\\\\Modules\\\\";
	}
	
}

?>';
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
	 * @var boolean
	 */
	protected $isWindows;


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
		if(!is_null($fgConst) && !$this->isWindows) {
			$coloredString .= "\033[" . constant("self::" . $fgConst) . "m";
		}

		$bgConst = $this->getConstName($bgColor);
		if(!is_null($bgConst) && !$this->isWindows) {
			$coloredString .= "\033[" . constant("self::" . $bgConst) . "m";
		}

		$coloredString .=  $string;
		
		if(!$this->isWindows) {
			$coloredString .=  "\033[0m";
		}

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
	
	/**
	 * 
	 * @param boolean $value
	 */
	public function setIsWindows($value) {
		$this->isWindows = (boolean) $value;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function isWindows() {
		return $this->isWindows;
	}
	
	/**
	 * 
	 */
	public function hide() {
		if(!$this->isWindows) {
			system("stty -echo");
		}
	}
	
	/**
	 * 
	 */
	public function restore() {
		if(!$this->isWindows) {
			system("stty echo");
		}
	}
	
}

?>
