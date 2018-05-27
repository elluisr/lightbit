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

namespace Lightbit\Html;

// Classes
use \Lightbit\Html\HtmlViewFactory;
use \Lightbit\Html\HtmlViewNotFoundException;

// Interfaces
use \Lightbit\Html\IHtmlView;
use \Lightbit\Html\IHtmlViewFactory;

final class HtmlViewProvider
{
	private static $instance;

	public final static function getInstance() : HtmlViewProvider
	{
		return (self::$instance ?? (self::$instance = new HtmlViewProvider()));
	}

	private $viewFactory;

	private $views;

	private function __construct()
	{
		$this->views = [];
	}

	public final function getView(string $view) : IHtmlView
	{
		strpos($view, '://') || $view = ('views://' . $view);

		return ($this->views[$view] ?? ($this->views[$view] = $this->getViewFactory()->createView($view)));
	}

	public final function getViewByPreference(string ...$views) : IHtmlView
	{
		if (!$views)
		{
			throw new ArgumentException(sprintf(
				'Argument exception, preferred views list can not be empty: at class, "%s", at function, "%s", at parameter "%s"',
				__CLASS__,
				__FUNCTION__,
				'views'
			));
		}

		$htmlViewNotFoundException = null;

		foreach ($views as $i => $view)
		{
			try
			{
				return $this->getView($view);
			}
			catch (HtmlViewNotFoundException $e)
			{
				$htmlViewNotFoundException ?? ($htmlViewNotFoundException = $e);
			}
		}

		throw $htmlViewNotFoundException;
	}

	public final function getViewFactory() : IHtmlViewFactory
	{
		return ($this->viewFactory ?? ($this->viewFactory = new HtmlViewFactory()));
	}

	public final function setViewFactory(IViewFactory $viewFactory) : void
	{
		$this->viewFactory = $viewFactory;
	}
}
