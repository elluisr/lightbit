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

use \Lightbit\Base\IComponent;
use \Lightbit\Base\IController;
use \Lightbit\Globalization\ILocale;
use \Lightbit\Globalization\IMessageSource;

/**
 * IContext.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
interface IContext
{
	/**
	 * Disposes the context.
	 */
	public function dispose() : void;

	/**
	 * Gets a component.
	 *
	 * @param string $id
	 *	The component identifier.
	 *
	 * @return IComponent
	 *	The component.
	 */
	public function getComponent(string $id) : IComponent;

	/**
	 * Gets the context.
	 *
	 * @return IContext
	 *	The context.
	 */
	public function getContext() : ?IContext;

	/**
	 * Gets a controller.
	 *
	 * @param string $id
	 *	The controller identifier.
	 *
	 * @return IController
	 *	The controller.
	 */
	public function getController(string $id) : IController;

	/**
	 * Gets the default route.
	 *
	 * @return array
	 *	The default route.
	 */
	public function getDefaultRoute() : array;

	/**
	 * Gets the global identifier.
	 *
	 * @return string
	 *	The global identifier.
	 */
	public function getGlobalID() : string;

	/**
	 * Gets the identifier.
	 *
	 * @return string
	 *	The identifier.
	 */
	public function getID() : string;

	/**
	 * Gets the layout.
	 *
	 * @return string
	 *	The layout.
	 */
	public function getLayout() : ?string;

	/**
	 * Gets the layout path.
	 *
	 * @return string
	 *	The layout path.
	 */
	public function getLayoutPath() : ?string;

	/**
	 * Gets the locale.
	 *
	 * @return ILocale
	 *	The locale.
	 */
	public function getLocale() : ILocale;

	/**
	 * Gets a module.
	 *
	 * @param string $id
	 *	The module identifier.
	 *
	 * @return IModule
	 *	The module.
	 */
	public function getModule(string $id) : IModule;

	/**
	 * Gets the namespace name.
	 *
	 * @return string
	 *	The namespace name.
	 */
	public function getNamespaceName() : string;

	/**
	 * Gets the path.
	 *
	 * @return string
	 *	The path.
	 */
	public function getPath() : string;

	/**
	 * Gets a plugin.
	 *
	 * @param string $id
	 *	The plugin identifier.
	 *
	 * @return IPlugin
	 *	The plugin.
	 */
	public function getPlugin(string $id) : IPlugin;

	/**
	 * Gets the prefix.
	 *
	 * @return string
	 *	The prefix.
	 */
	public function getPrefix() : string;

	/**
	 * Gets the views base paths.
	 *
	 * @return array
	 *	The views base paths.
	 */
	public function getViewsBasePaths() : array;

	/**
	 * Checks for a component availability.
	 *
	 * @param string $id
	 *	The component identifier.
	 *
	 * @return bool
	 *	The result.
	 */
	public function hasComponent(string $id) : bool;

	/**
	 * Checks a controller availability.
	 *
	 * @param string $id
	 *	The controller identifier.
	 *
	 * @return bool
	 *	The result.
	 */
	public function hasController(string $id) : bool;

	/**
	 * Checks for a module availability.
	 *
	 * @param string $id
	 *	The module identifier.
	 *
	 * @return bool
	 *	The result.
	 */
	public function hasModule(string $id) : string;

	/**
	 * Sets a component configuration.
	 *
	 * @param string $id
	 *	The component identifier.
	 *
	 * @param array $configuration
	 *	The component configuration.
	 *
	 * @param bool $merge
	 *	The components configuration merge flag.
	 */
	public function setComponentConfiguration(string $id, array $configuration, bool $merge = true) : void;

	/**
	 * Sets the components configuration.
	 *
	 * @param array $componentsConfiguration
	 *	The components configuration.
	 *
	 * @param bool $merge
	 *	The components configuration merge flag.
	 */
	public function setComponentsConfiguration(array $setComponentsConfiguration, bool $merge = true) : void;

	/**
	 * Sets the layout.
	 *
	 * @param string $layout
	 *	The layout.
	 */
	public function setLayout(?string $layout) : void;

	/**
	 * Sets the locale.
	 *
	 * @param string $id
	 *	The locale identifier.
	 */
	public function setLocale(string $id) : void;

	/**
	 * Sets a module configuration.
	 *
	 * @param string $id
	 *	The module identifier.
	 *
	 * @param array $configuration
	 *	The module configuration.
	 *
	 * @param bool $merge
	 *	The module configuration merge flag.
	 */
	public function setModuleConfiguration(string $id, array $configuration, bool $merge = true) : void;

	/**
	 * Sets the modules configuration.
	 *
	 * @param array $modulesConfiguration
	 *	The modules configuration.
	 *
	 * @param bool $merge
	 *	The module configuration merge flag.
	 */
	public function setModulesConfiguration(array $modulesConfiguration, bool $merge = true) : void;

	/**
	 * Sets a plugin configuration.
	 *
	 * @param string $id
	 *	The plugin identifier.
	 *
	 * @param array $configuration
	 *	The plugin configuration.
	 *
	 * @param bool $merge
	 *	The plugin configuration merge flag.
	 */
	public function setPluginConfiguration(string $id, array $configuration, bool $merge = true) : void;

	/**
	 * Sets the plugins configuration.
	 *
	 * @param array $modulesConfiguration
	 *	The plugins configuration.
	 *
	 * @param bool $merge
	 *	The plugins configuration merge flag.
	 */
	public function setPluginsConfiguration(array $modulesConfiguration, bool $merge = true) : void;
}