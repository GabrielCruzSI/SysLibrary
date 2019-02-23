<?php

namespace core;

use app\classes\Uri;
use app\exceptions\ControllerNotExistsException;

class Controller {

	private $uri;

	private $folders = [
		"app\controllers\portal",
		"app\controllers\admin"
	];

	private $controller;

	private $namespace;

	public function __construct()
	{
		$this->uri = Uri::uri();
	}

	public function load()
	{
		if ($this->isHome()) {
			return $this->controllerHome();
		}

		return $this->controllerNotHome();
	}

	private function controllerHome()
	{
		if (!$this->controllerExists('HomeController')) {
			throw new ControllerNotExistsException("Esse controller não existe");
		}

		return $this->instatiateController();
	}

	private function controllerNotHome()
	{
		$controller = $this->getControllerNotHome();

		if(!$this->controllerExists($controller)) {
			throw new ControllerNotExistsException("Esse controller não existe");
		}

		return $this->instatiateController($controller);
	}

	private function getControllerNotHome()
	{
		$explode = explode("/", $this->uri);

		if(substr_count($this->uri, "/") > 1) {
			list($controller) = array_values(array_filter(explode("/", $this->uri)));
			return ucfirst($controller) . "Controller";
		} 

		return ucfirst(ltrim($this->uri, "/")) . "Controller";
	}

	private function isHome()
	{
		return ($this->uri == "/");
	}

	private function controllerExists($controller)
	{
		$controllerExists = false;

		foreach ($this->folders as $folder) {
			if (class_exists($folder . "\\" . $controller)) {
				$controllerExists = true;
				$this->namespace = $folder;
				$this->controller = $controller;
			}
		}

		return $controllerExists;
	}

	private function instatiateController()
	{
		$controller = $this->namespace . "\\" . $this->controller;

		return new $controller;
	}
}