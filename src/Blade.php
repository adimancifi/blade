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
	
	public function __construct($viewPath, $compilerPath)
	{
		/**
		 * Conto : 
		 * $blade = new Blade(VIEWPATH."view/blade", VIEWPATH."view/blade/cache");
		 * 
		*/

		$this->viewPath = [$viewPath];
		$this->compilerPath = $compilerPath;
	}

	public function render($viewName, array $varData)
	{
		// Dependencies
		$filesystem = new Filesystem;
		$eventDispatcher = new Dispatcher(new Container);

		// Create View Factory capable of rendering PHP and Blade templates
		$viewResolver = new EngineResolver;
		$bladeCompiler = new BladeCompiler($filesystem, $this->compilerPath);

		$viewResolver->register('blade', function () use ($bladeCompiler) {
			return new CompilerEngine($bladeCompiler);
		});

		$viewResolver->register('php', function () {
			return new PhpEngine;
		});

		$viewFinder = new FileViewFinder($filesystem, $this->viewPath);
		$viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

		// Render template
		return $viewFactory->make($viewName, $varData)->render();
	}
} // end class
