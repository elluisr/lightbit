<?php

// -----------------------------------------------------------------------------
// Lightbit
//
// Copyright (c) 2018 Datapoint — Sistemas de Informação, Unipessoal, Lda.
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
use \Lightbit\Base\IController;
use \Lightbit\Base\ThemeView;

/**
 * Layout.
 *
 * @author Datapoint — Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
class Layout extends ThemeView
{
	/**
	 * Constructor.
	 *
	 * @param ITheme $theme
	 *	The layout theme.
	 *
	 * @param IController $controller
	 *	The layout controller.
	 *
	 * @param string $id
	 *	The layout identifier.
	 *
	 * @param string $path
	 *	The layout path.
	 */
	public function __construct(ITheme $theme, IController $controller, string $id, string $path)
	{
		parent::__construct($theme, $controller, $id, $path);
	}

	/**
	 * Gets a view.
	 *
	 * @param string $id
	 *	The view identifier.
	 *
	 * @return IView
	 *	The view.
	 */
	public function getView(string $id) : IView
	{
		return new ThemeView
		(
			$this->getTheme(),
			$this->getController(),
			$id
			((new Asset(dirname($this->path), $id))->getPath())
		);
	}
}