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

namespace Lightbit\Html\Rendering;

use \Throwable;
use \Lightbit\Exception;

use \Lightbit\Html\Rendering\IHtmlViewFactory;

/**
 * HtmlViewFactoryException.
 *
 * @author Datapoint - Sistemas de Informação, Unipessoal, Lda.
 * @since 2.0.0
 */
class HtmlViewFactoryException extends Exception
{
	/**
	 * The view factory.
	 *
	 * @var IHtmlViewFactory
	 */
	private $viewFactory;

	/**
	 * Constructor.
	 *
	 * @param IHtmlViewFactory $viewFactory
	 *	The exception view factory.
	 *
	 * @param string $message
	 *	The exception message.
	 *
	 * @param Throwable $previous
	 *	The exception previous throwable.
	 */
	public function __construct(IHtmlViewFactory $viewFactory, string $message, Throwable $previous = null)
	{
		parent::__construct($message, $previous);

		$this->viewFactory = $viewFactory;
	}

	/**
	 * Gets the view factory.
	 *
	 * @return IHtmlViewFactory
	 *	The view factory.
	 */
	public final function getViewFactory() : IHtmlViewFactory
	{
		return $this->viewFactory;
	}
}
