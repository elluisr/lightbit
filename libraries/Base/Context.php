<?php

// -----------------------------------------------------------------------------
// Lightbit
//
// Copyright (c) 2017 Datapoint — Sistemas de Informação, Unipessoal, Lda.
// https://www.datapoint.pt/
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.
// -----------------------------------------------------------------------------

namespace Lightbit\Base;

use \Lightbit;
use \Lightbit\Base\Action;
use \Lightbit\Base\ControllerNotFoundException;
use \Lightbit\Base\IComponent;
use \Lightbit\Base\IElement;
use \Lightbit\Base\IEnvironment;
use \Lightbit\Base\ModuleNotFoundException;
use \Lightbit\Data\Caching\ICache;
use \Lightbit\Data\Caching\IFileCache;
use \Lightbit\Data\Caching\IMemoryCache;
use \Lightbit\Data\Caching\INetworkCache;
use \Lightbit\Data\ISlugManager;
use \Lightbit\Data\Sql\ISqlConnection;
use \Lightbit\Exception;
use \Lightbit\Globalization\ILocale;
use \Lightbit\Globalization\IMessageSource;
use \Lightbit\Globalization\Locale;
use \Lightbit\Html\IHtmlAdapter;
use \Lightbit\Html\IHtmlDocument;
use \Lightbit\Http\IHttpAssetManager;
use \Lightbit\Http\IHttpQueryString;
use \Lightbit\Http\IHttpRequest;
use \Lightbit\Http\IHttpResponse;
use \Lightbit\Http\IHttpRouter;
use \Lightbit\Http\IHttpSession;
use \Lightbit\IllegalStateException;
use \Lightbit\Security\Cryptography\IPasswordDigest;

/**
 * Context.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
abstract class Context extends Object
{
	/**
	 * The components.
	 *
	 * @type array
	 */
	private $components;

	/**
	 * The components configuration.
	 *
	 * @type array
	 */
	private $componentsConfiguration;

	/**
	 * The context.
	 *
	 * @type Context
	 */
	private $context;

	/**
	 * The controllers.
	 *
	 * @type array
	 */
	private $controllers;

	/**
	 * The event listeners.
	 *
	 * @type array
	 */
	private $eventListeners;

	/**
	 * The identifier.
	 *
	 * @type string
	 */
	private $id;

	/**
	 * The layout.
	 *
	 * @type string
	 */
	private $layout;

	/**
	 * The layout path.
	 *
	 * @type string
	 */
	private $layoutPath;

	/**
	 * The context locale.
	 *
	 * @type ILocale
	 */
	private $locale;

	/**
	 * The modules.
	 *
	 * @type string
	 */
	private $modules;

	/**
	 * The modules base path.
	 *
	 * @type string
	 */
	private $modulesBasePath;

	/**
	 * The modules configuration.
	 *
	 * @type array
	 */
	private $modulesConfiguration;

	/**
	 * The path.
	 *
	 * @type string
	 */
	private $path;

	/**
	 * Constructor.
	 *
	 * @param string $path
	 *	The application path.
	 *
	 * @param array $configuration
	 *	The application configuration.
	 */
	protected function __construct(?Context $context, string $id, string $path, array $configuration = null)
	{
		$this->context = $context;
		$this->id = $id;
		$this->path = $path;

		$this->components = [];
		$this->componentsConfiguration = [];
		$this->controllers = [];
		$this->eventListeners = [];
		$this->modules = [];

		// Scans the modules base path for installations, loads the
		// configuration, creates and registers each module.
		$modulesBasePath = $this->getModulesBasePath();

		if (is_dir($modulesBasePath))
		{
			foreach (scandir($modulesBasePath) as $i => $id)
			{
				if ($id[0] === '.' || $id[0] === '_' || $id[0] === '~')
				{
					continue;
				}

				$this->getModule($id);
			}
		}
	}

	/**
	 * Loads the dependencies.
	 *
	 * @param array $dependencies
	 *	The dependencies schema.
	 */
	private function _loadDependencies(array $dependencies) : void
	{
		foreach ($dependencies as $type => $subjects)
		{
			if ($type === 'module')
			{
				foreach ($subjects as $i => $module)
				{
					$this->getModule($module);
				}
			}
		}
	}

	/**
	 * Creates a controller default class name.
	 *
	 * @param string $id
	 *	The controller identifier.
	 *
	 * @return string
	 *	The controller class name.
	 */
	protected function controllerClassName(string $id) : string
	{
		return $this->getNamespaceName()
			. '\\Controllers\\'
			. strtr(ucwords(strtr($id, [ '/' => ' \\ ', '-' => ' ' ])), [ ' ' => '' ])
			. 'Controller';
	}

	/**
	 * Disposes the context.
	 */
	public function dispose() : void
	{
		foreach (array_reverse($this->modules) as $id => $module)
		{
			$module->dispose();
		}

		$this->modules = [];
	}

	/**
	 * Gets the cache.
	 *
	 * @return ICache
	 *	The cache.
	 */
	public final function getCache() : ICache
	{
		return $this->getComponent('data.cache');
	}

	/**
	 * Gets a component.
	 *
	 * @param string $id
	 *	The component identifier.
	 *
	 * @return IComponent
	 *	The component.
	 */
	public final function getComponent(string $id) : IComponent
	{
		if (!isset($this->components[$id]))
		{
			if (!isset($this->componentsConfiguration[$id]))
			{
				if ($this->context)
				{
					return $this->context->getComponent($id);
				}

				throw new ComponentNotFoundException($this, $id, sprintf('Component configuration not found: "%s"', $id));
			}

			if (!isset($this->componentsConfiguration[$id]['@class']))
			{
				throw new ComponentConfigurationException($this, $id, sprintf('Component class name is undefined: "%s"', $id));
			}

			$className = $this->componentsConfiguration[$id]['@class'];

			$component
				= $this->components[$id]
					= new $className($this, $id, $this->componentsConfiguration[$id]);

			if (($component instanceof IChannel) && $component->isClosed())
			{
				$component->start();
			}
		}

		return $this->components[$id];
	}

	/**
	 * Gets the context.
	 *
	 * @return Context
	 *	The context.
	 */
	public final function getContext() : ?Context
	{
		return $this->context;
	}

	/**
	 * Gets a controller.
	 *
	 * @param string $id
	 *	The controller identifier.
	 *
	 * @return IController
	 *	The controller.
	 */
	public final function getController(string $id) : IController
	{
		if (!isset($this->controllers[$id]))
		{
			if (!$this->hasController($id))
			{
				throw new ControllerNotFoundException($this, $id, sprintf('Controller not found: "%s", at context "%s"', $id, $this->getPrefix()));
			}

			$className = $this->getControllerClassName($id);
			return $this->controllers[$id] = new $className($this, $id, null);
		}

		return $this->controllers[$id];
	}

	/**
	 * Gets a controller class name.
	 *
	 * @param string $id
	 *	The controller identifier.
	 *
	 * @return string
	 *	The controller class name.
	 */
	public final function getControllerClassName(string $id) : string
	{
		static $results = [];

		if (!isset($results[$id]))
		{
			$results[$id] = $this->controllerClassName($id);
		}

		return $results[$id];
	}

	/**
	 * Gets the default route.
	 *
	 * @return array
	 *	The default route.
	 */
	public function getDefaultRoute() : array
	{
		return [ '@/default/index' ];
	}

	/**
	 * Gets the environment.
	 *
	 * @return IEnvironment
	 *	The environment.
	 */
	public final function getEnvironment() : IEnvironment
	{
		return $this->getComponent('environment');
	}

	/**
	 * Gets the file cache.
	 *
	 * @return IFileCache
	 *	The file cache.
	 */
	public function getFileCache() : IFileCache
	{
		return $this->getComponent('data.cache.file');
	}

	/**
	 * Gets the global identifier.
	 *
	 * @return string
	 *	The global identifier.
	 */
	public final function getGlobalID() : string
	{
		static $globalID;

		if (!$globalID)
		{
			$tokens = [];
			$context = $this;

			do
			{
				$tokens[] = $context->getID();
			}
			while ($context = $context->getContext());

			$globalID = implode('/', array_reverse($tokens));
		}

		return $globalID;
	}

	/**
	 * Gets the html adapter.
	 *
	 * @return IHtmlAdapter
	 *	The html adapter.
	 */
	public function getHtmlAdapter() : IHtmlAdapter
	{
		return $this->getComponent('html.adapter');
	}

	/**
	 * Gets the html document.
	 *
	 * @return IHtmlDocument
	 *	The html document.
	 */
	public function getHtmlDocument() : IHtmlDocument
	{
		return $this->getComponent('html.document');
	}

	/**
	 * Gets the http asset manager.
	 *
	 * @return IHttpAssetManager
	 *	The http asset manager.
	 */
	public function getHttpAssetManager() : IHttpAssetManager
	{
		return $this->getComponent('http.asset.manager');
	}

	/**
	 * Gets the http query string.
	 *
	 * @return IHttpQueryString
	 *	The http query string.
	 */
	public function getHttpQueryString() : IHttpQueryString
	{
		return $this->getComponent('http.query.string');
	}

	/**
	 * Gets the http request component.
	 *
	 * @param IHttpRequest
	 *	The http request component.
	 */
	public function getHttpRequest() : IHttpRequest
	{
		return $this->getComponent('http.request');
	}

	/**
	 * Gets the http response component.
	 *
	 * @param IHttpResponse
	 *	The http response component.
	 */
	public function getHttpResponse() : IHttpResponse
	{
		return $this->getComponent('http.response');
	}

	/**
	 * Gets the http router.
	 *
	 * @return IHttpRouter
	 *	The http router.
	 */
	public function getHttpRouter() : IHttpRouter
	{
		return $this->getComponent('http.router');
	}

	/**
	 * Gets the http session.
	 *
	 * @return IHttpSession
	 *	The http session.
	 */
	public function getHttpSession() : IHttpSession
	{
		return $this->getComponent('http.session');
	}

	/**
	 * Gets the identifier.
	 *
	 * @return string
	 *	The identifier.
	 */
	public final function getID() : string
	{
		return $this->id;
	}

	/**
	 * Gets the layout.
	 *
	 * @return string
	 *	The layout.
	 */
	public final function getLayout() : ?string
	{
		return $this->layout;
	}

	/**
	 * Gets the layout path.
	 *
	 * @return string
	 *	The layout path.
	 */
	public final function getLayoutPath() : ?string
	{
		if (!$this->layoutPath)
		{
			if (!$this->layout)
			{
				if ($this->context)
				{
					return $this->context->getLayoutPath();
				}

				return null;
			}

			$this->layoutPath = __asset_path_resolve($this->getPath(), 'php', $this->layout);
		}

		return $this->layoutPath;
	}

	/**
	 * Gets the locale.
	 *
	 * @return Locale
	 *	The locale.
	 */
	public final function getLocale() : ILocale
	{
		if ($this->locale)
		{
			return $this->locale;
		}

		if ($this->context)
		{
			return $this->context->getLocale();
		}

		throw new Exception(sprintf('Locale is not defined for base context: "%s"', $this->getPrefix));
	}

	/**
	 * Gets the memory cache.
	 *
	 * @return IMemoryCache
	 *	The memory cache.
	 */
	public function getMemoryCache() : IMemoryCache
	{
		return $this->getComponent('data.cache.memory');
	}

	/**
	 * Gets the message source.
	 *
	 * @return IMessageSource
	 *	The message source.
	 */
	public function getMessageSource() : IMessageSource
	{
		return $this->getComponent('globalization.message.source');
	}

	/**
	 * Gets the namespace name.
	 *
	 * @return string
	 *	The namespace name.
	 */
	public final function getNamespaceName() : string
	{
		static $result;

		if (!$result)
		{
			$result = __class_namespace(static::class);
		}

		return $result;
	}

	/**
	 * Gets the network cache.
	 *
	 * @return INetworkCache
	 *	The network cache.
	 */
	public function getNetworkCache() : INetworkCache
	{
		return $this->getComponent('data.cache.network');
	}

	/**
	 * Gets a module.
	 *
	 * @param string $id
	 *	The module identifier.
	 *
	 * @return Module
	 *	The module.
	 */
	public final function getModule(string $id) : Module
	{
		if (!isset($this->modules[$id]))
		{
			// Ensure the module install path exists and matches a directory
			// to throw an exception with a matching description.
			$installPath = $this->getModulesBasePath() . DIRECTORY_SEPARATOR . $id;

			if (!is_dir($installPath))
			{
				throw new ModuleNotFoundException
				(
					$this,
					$id,
					sprintf
					(
						'Context module not found, not available: module "%s", at context "%s"',
						$id,
						$this->getGlobalID()
					)
				);
			}

			// Get the module configuration and ensure it's an array with the
			// applicable magic properties set as needed.
			$configurationPath = $installPath . DIRECTORY_SEPARATOR . 'module.php';

			if (!is_file($configurationPath))
			{
				throw new ModuleNotFoundException
				(
					$this,
					$id,
					sprintf
					(
						'Context module not found, missing configuration: module "%s", at context "%s"',
						$id,
						$this->getGlobalID()
					)
				);
			}

			$configuration = __include($configurationPath);

			if (!is_array($configuration) || !isset($configuration['@class']))
			{
				throw new ModuleNotFoundException
				(
					$this,
					$id,
					sprintf
					(
						'Context module not found, bad configuration: module "%s", at context "%s"',
						$id,
						$this->getGlobalID()
					)
				);
			}

			// If the configuration defines any dependencies, we'll have to
			// load them before the instance can be created.
			if (isset($configuration['@require']) && is_array($configuration['@require']))
			{
				$this->_loadDependencies($configuration['@require']);
			}

			$this->modules[$id] = new $configuration['@class']
			(
				$this,
				$id,
				$installPath,
				$configuration
			);
		}

		return $this->modules[$id];
	}

	/**
	 * Gets the modules base path.
	 *
	 * @return string
	 *	The modules base path.
	 */
	public final function getModulesBasePath() : string
	{
		if (!$this->modulesBasePath)
		{
			$this->modulesBasePath = $this->getPath() . DIRECTORY_SEPARATOR . 'modules';
		}

		return $this->modulesBasePath;
	}

	/**
	 * Gets the password digest.
	 *
	 * @return IPasswordDigest
	 *	The password digest.
	 */
	public function getPasswordDigest() : IPasswordDigest
	{
		return $this->getComponent('security.cryptography.password.digest');
	}

	/**
	 * Gets the path.
	 *
	 * @return string
	 *	The path.
	 */
	public final function getPath() : string
	{
		return $this->path;
	}

	/**
	 * Gets the prefix.
	 *
	 * @return string
	 *	The prefix.
	 */
	public final function getPrefix() : string
	{
		static $prefix;

		if (!$prefix)
		{
			$tokens = [];
			$context = $this;

			while ($previous = $context->getContext())
			{
				$tokens[] = $context->getID();
				$context = $previous;
			}

			$prefix = '/' . implode('/', array_reverse($tokens));
		}

		return $prefix;
	}

	/**
	 * Gets the slug manager.
	 *
	 * @return ISlugManager
	 *	The slug manager.
	 */
	public function getSlugManager() : ISlugManager
	{
		return $this->getComponent('data.slug.manager');
	}

	/**
	 * Gets the sql connection.
	 *
	 * @return ISqlConnection
	 *	The sql connection.
	 */
	public function getSqlConnection() : ISqlConnection
	{
		return $this->getComponent('data.sql.connection');
	}

	/**
	 * Gets the views base paths.
	 *
	 * @return array
	 *	The views base paths.
	 */
	public final function getViewsBasePaths() : array
	{
		static $viewsBasePaths;

		if (!$viewsBasePaths)
		{
			$viewsBasePaths = $this->viewsBasePaths();
		}

		return $viewsBasePaths;
	}

	/**
	 * Checks a component availability.
	 *
	 * @param string $id
	 *	The component identifier.
	 *
	 * @return bool
	 *	The result.
	 */
	public final function hasComponent(string $id) : bool
	{
		return (isset($this->components[$id]) || isset($this->componentsConfiguration[$id]['@class']));
	}

	/**
	 * Checks a controller availability.
	 *
	 * @param string $id
	 *	The controller identifier.
	 *
	 * @return bool
	 *	The result.
	 */
	public final function hasController(string $id) : bool
	{
		static $results = [];

		if (!isset($results[$id]))
		{
			return $results[$id] = __class_exists($this->getControllerClassName($id));
		}

		return $results[$id];
	}

	/**
	 * Checks for a module availability.
	 *
	 * @param string $id
	 *	The module identifier.
	 *
	 * @return bool
	 *	The result.
	 */
	public final function hasModule(string $id) : string
	{
		return isset($this->modules[$id]);
	}

	/**
	 * Resolves a route.
	 *
	 * @param array $route
	 *	The route.
	 *
	 * @return Action
	 *	The action.
	 */
	public final function resolve(?array $route) : Action
	{
		$context = $this;

		_resolve0:

		// If a route is not given, we'll use the default route as defined
		// by this context.
		if (!$route)
		{
			$route = $context->getDefaultRoute();
		}

		// If the route path is null, we'll extend the default route with
		// the one provided
		else if (!isset($route[0]) || !$route[0])
		{
			$route = $context->getDefaultRoute() + $route;
		}

		// If the route path starts with "~/" the whole behaviour is skipped,
		// as the route is resolved directly by the controller
		$path = $route[0];
		$parameters = $route;
		unset($parameters[0]);

		if (strpos($path, '~/') === 0)
		{
			return __action()->getController()->resolve
			(
				substr($path, 2),
				$parameters
			);
		}

		// Change the target context and path based on the path prefix,
		// acording to the following rules.
		if ($path[0] == '/')
		{
			$context = __application();
			$path = substr($path, 1);
		}

		else if (strpos($path, '@/') === 0)
		{
			try
			{
				$context = __action()->getContext();
			}
			catch (IllegalStateException $e) {}

			$path = substr($path, 2);
		}

		$i;
		_resolve1:

		// If the route path contains a slash, we'll first check against
		// for an existing controller, as they have priority.
		if ($i = strrpos($path, '/'))
		{
			try
			{
				return $context->getController(substr($path, 0, $i))
					->resolve(substr($path, $i + 1), $parameters);
			}
			catch (ControllerNotFoundException $e) { }
		}

		// Since we now know it has to be related to a module, we'll get that
		// module and resolve from it
		if ($i = strpos($path, '/'))
		{
			try
			{
				$context = $context->getModule(substr($path, 0, $i));
				$path = substr($path, $i + 1);
				goto _resolve1;
			}
			catch (ModuleNotFoundException $e)
			{
				$controllerID = substr($path, 0, strrpos($path, '/'));

				throw new ControllerNotFoundException
				(
					$context,
					$controllerID,
					sprintf
					(
						'Context controller not found: controller "%s", at context "%s"',
						$controllerID,
						$context->getGlobalID()
					)
				);
			}
		}

		$context = $context->getModule($path);
		$route = $context->getDefaultRoute();
		goto _resolve0;
	}

	/**
	 * Sets a component configuration.
	 *
	 * @param string $id
	 *	The component identifier.
	 *
	 * @param array $configuration
	 *	The component configuration.
	 */
	public function setComponentConfiguration(string $id, array $configuration) : void
	{
		$this->componentsConfiguration[$id]
			= (isset($this->componentsConfiguration[$id]))
			? array_replace_recursive($this->componentsConfiguration[$id], $configuration)
			: $configuration;
	}

	/**
	 * Sets the components configuration.
	 *
	 * @param array $componentsConfiguration
	 *	The components configuration.
	 */
	public function setComponentsConfiguration(array $componentsConfiguration) : void
	{
		foreach ($componentsConfiguration as $id => $configuration)
		{
			$this->setComponentConfiguration($id, $configuration);
		}
	}

	/**
	 * Sets the layout.
	 *
	 * @param string $layout
	 *	The layout.
	 */
	public function setLayout(?string $layout) : void
	{
		$this->layout = $layout;
		$this->layoutPath = null;
	}

	/**
	 * Sets the locale.
	 *
	 * @param string $id
	 *	The locale identifier.
	 */
	public final function setLocale(string $id) : void
	{
		$this->locale = Locale::getLocale($id);
	}

	/**
	 * Sets a module configuration.
	 *
	 * @param string $id
	 *	The module identifier.
	 *
	 * @param array $configuration
	 *	The module configuration.
	 */
	public final function setModuleConfiguration(string $id, array $configuration) : void
	{
		$this->getModule($id)->configure($configuration);
	}

	/**
	 * Sets the modules configuration.
	 *
	 * @param array $modulesConfiguration
	 *	The modules configuration.
	 */
	public final function setModulesConfiguration(array $modulesConfiguration) : void
	{
		foreach ($modulesConfiguration as $id => $configuration)
		{
			$this->setModuleConfiguration($id, $configuration);
		}
	}

	/**
	 * Creates the views base paths collection.
	 *
	 * @return array
	 *	The views base paths collection.
	 */
	protected function viewsBasePaths() : array
	{
		$result = [ $this->getPath() . DIRECTORY_SEPARATOR . 'views' ];

		$layoutPath = $this->getLayoutPath();

		if ($layoutPath)
		{
			array_unshift($result, dirname($layoutPath) . DIRECTORY_SEPARATOR . 'views');
		}

		return $result;
	}
}
