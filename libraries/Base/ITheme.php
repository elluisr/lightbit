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

use \Lightbit\Base\IContext;
use \Lightbit\Base\IElement;

/**
 * ITheme.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
interface ITheme extends IElement
{
	/**
	 * Constructor.
	 *
	 * @param IContext $context
	 *	The theme context.
	 *
	 * @param string $id
	 *	The theme id.
	 */
	public function __construct(IContext $context, string $id, string $path, array $configuration = null);

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
	public function getLayout() : string;

	/**
	 * Gets the path.
	 *
	 * @return string
	 *	The path.
	 */
	public function getPath() : string;

	/**
	 * Gets the views base path.
	 *
	 * @return string
	 *	The views base path.
	 */
	public function getViewsBasePath() : string;

	/**
	 * Gets a view.
	 *
	 * @param string $view
	 *	The view identifier.
	 *
	 * @return IView
	 *	The view.
	 */
	public function getView(string $view) : IView;

	/**
	 * Checks if a view exists.
	 *
	 * @param string $view
	 *	The view identifier.
	 *
	 * @return bool
	 *	The result.
	 */
	public function hasView(string $view) : bool;

	/**
	 * Sets the layout.
	 *
	 * @param string $layout
	 *	The layout.
	 */
	public function setLayout(?string $layout) : void;

	/**
	 * Runs the theme.
	 *
	 * @param string $content
	 *	The content to display, as generated by the controller view.
	 *
	 * @param bool $captrue
	 *	When set, the generated content will be captured and returned
	 *	instead of flushed to the current output buffer.
	 *
	 * @return string
	 *	The content.
	 */
	public function run(string $content, bool $capture = false) : ?string;
}