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

use \Lightbit\Base\Element;
use \Lightbit\Base\View;

use \Lightbit\Base\IContext;
use \Lightbit\Base\ITheme;
use \Lightbit\Base\IView;

/**
 * Theme.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
class Theme extends Element implements ITheme
{
	/**
	 * The context.
	 *
	 * @var IContext
	 */
	private $context;

	/**
	 * The identifier.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * The layout.
	 *
	 * @var string
	 */
	private $layout;

	/**
	 * The layout view.
	 *
	 * @var IView
	 */
	private $layoutView;

	/**
	 * The path.
	 *
	 * @var string
	 */
	private $path;

	/**
	 * This views base path.
	 *
	 * @var string
	 */
	private $viewsBasePath;

	/**
	 * Constructor.
	 *
	 * @param IContext $context
	 *	The theme context.
	 *
	 * @param string $id
	 *	The theme id.
	 */
	public function __construct(IContext $context, string $id, string $path, array $configuration = null)
	{
		parent::__construct($context);

		$this->context = $context;
		$this->id = $id;
		$this->layout = 'main';
		$this->path = $path;

		if ($configuration)
		{
			__object_apply($this, $configuration);
		}
	}

	/**
	 * Gets the context.
	 *
	 * @return IContext
	 *	The context.
	 */
	public function getContext() : IContext
	{
		return $this->context;
	}

	/**
	 * Gets the global identifier.
	 *
	 * @return string
	 *	The global identifier.
	 */
	public final function getGlobalID() : string
	{
		return $this->getContext()->getGlobalID() . '/' . $this->id;
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
	public final function getLayout() : string
	{
		return $this->layout;
	}

	/**
	 * Gets the layout view.
	 *
	 * @return IView
	 *	The layout view.
	 */
	public final function getLayoutView() : IView
	{
		if (!$this->layoutView)
		{
			$this->layoutView = new View
			(
				$this->getContext(),
				__asset_path_resolve
				(
					$this->path,
					'php',
					$this->layout
				)
			);
		}

		return $this->layoutView;
	}

	/**
	 * Gets the path.
	 *
	 * @return string
	 *	The path.
	 */
	public function getPath() : string
	{
		return $this->path;
	}

	/**
	 * Gets a view.
	 *
	 * @param string $view
	 *	The view identifier.
	 *
	 * @return IView
	 *	The view.
	 */
	public final function getView(string $view) : IView
	{
		$path = __asset_path_resolve($this->getViewsBasePath(), 'php', $view);

		if (!is_file($path))
		{
			throw new ContextViewNotFoundException
			(
				$this->context, 
				sprintf
				(
					'Context theme view not found: view %s, path %s, context %s, theme %s',
					$view,
					$path,
					$this->context->getGlobalID(),
					$this->id
				)
			);
		}

		return new View($this->context, $path);
	}

	/**
	 * Gets the views base path.
	 *
	 * @return string
	 *	The views base path.
	 */
	public final function getViewsBasePath() : string
	{
		if (!$this->viewsBasePath)
		{
			$this->viewsBasePath = $this->path . DIRECTORY_SEPARATOR . 'views';
		}

		return $this->viewsBasePath;
	}

	/**
	 * Checks if the theme has support for the given layout.
	 *
	 * @param string $layout
	 *	The layout.
	 *
	 * @return bool
	 *	The result.
	 */
	public final function hasLayout(string $layout) : bool
	{
		return is_file(__asset_path_resolve($this->path, 'php', $layout));
	}

	/**
	 * Checks if a view exists.
	 *
	 * @param string $view
	 *	The view identifier.
	 *
	 * @return bool
	 *	The result.
	 */
	public final function hasView(string $view) : bool
	{
		return is_file(__asset_path_resolve($this->getViewsBasePath(), 'php', $view));
	}

	/**
	 * Sets the layout.
	 *
	 * @param string $layout
	 *	The layout.
	 */
	public function setLayout(?string $layout) : void
	{
		$this->layout = $layout ?? 'main';
		$this->layoutView = null;
	}

	/**
	 * Runs the theme.
	 *
	 * @param string $content
	 *	The content to display, as generated by the controller view.
	 *
	 * @param bool $capture
	 *	When set, the generated content will be captured and returned
	 *	instead of flushed to the current output buffer.
	 *
	 * @return string
	 *	The content.
	 */
	public final function run(string $content, bool $capture = false) : ?string
	{
		return $this->getLayoutView()->run([ 'content' => $content ], $capture);
	}
}