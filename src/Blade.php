<?php

namespace Adimancifi\Blade;


use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class Blade 
{
	protected $viewPath;
	protected $compilerPath;
	
	public function __construct()
	{
		/**
		 * Conto : 
		 * $blade = new Blade(VIEWPATH."view/blade", VIEWPATH."view/blade/cache");
		 * 
		 * $blade->setup([
		 * 		'viewPath' => VIEWPATH."view/blade",
		 * 		'compilerPath' => VIEWPATH."view/blade/cache"
		 * ]);
		 * $blade->render('myview', ['nama' => 'adiman']);
		 * 
		*/

	}

	public function setup(array $varS)
	{
		foreach ($varS as $var) {
			$this->$var = $var;
		}
		return $this;
	}
	
	public function render($viewName, array $templateData)
	{
		// Configuration
		// Note that you can set several directories where your templates are located
		$pathsToTemplates = $this->viewPath;
		$pathToCompiledTemplates = $this->compilerPath;

		// Dependencies
		$filesystem = new Filesystem;
		$eventDispatcher = new Dispatcher(new Container);

		// Create View Factory capable of rendering PHP and Blade templates
		$viewResolver = new EngineResolver;
		$bladeCompiler = new BladeCompiler($filesystem, $pathToCompiledTemplates);

		$viewResolver->register('blade', function () use ($bladeCompiler) {
			return new CompilerEngine($bladeCompiler);
		});

		$viewResolver->register('php', function () {
			return new PhpEngine;
		});

		$viewFinder = new FileViewFinder($filesystem, $pathsToTemplates);
		$viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

		// Render template
		return $viewFactory->make($viewName, $templateData)->render();
	}
} // end class
