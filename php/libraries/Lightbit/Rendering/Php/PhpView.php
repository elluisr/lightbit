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

namespace Lightbit\Rendering\Php;

use \Throwable;

use \Lightbit\IO\AssetManagement\AssetProvider;
use \Lightbit\IO\AssetManagement\Php\IPhpAsset;
use \Lightbit\Rendering\IView;
use \Lightbit\Rendering\Php\PhpViewScope;

/**
 * PhpView.
 *
 * @author Datapoint — Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
class PhpView implements IView, IPhpView
{
	/**
	 * The asset.
	 *
	 * @var IPhpAsset
	 */
	private $asset;

	/**
	 * The scope.
	 *
	 * @var PhpViewScope
	 */
	private $scope;

	/**
	 * Constructor.
	 *
	 * @param string $view
	 *	The view asset identifier.
	 */
	public function __construct(string $view)
	{
		$this->asset = AssetProvider::getInstance()->getPhpAsset($view);
		$this->scope = new PhpViewScope($this);
	}

	/**
	 * Creates a base view.
	 *
	 * @param string $view
	 *	The base view asset identifier.
	 *
	 * @return IPhpView
	 *	The base view.
	 */
	public function createBaseView(string $view) : IPhpView
	{
		return new PhpView($view);
	}

	/**
	 * Renders the view.
	 *
	 * @param array $variables
	 *	The view variables.
	 *
	 * @return string
	 *	The content.
	 */
	public function render(array $variables = null) : string
	{
		if (!ob_start())
		{
			throw new PhpViewException($this, sprintf('Can not render view, output buffer creation failure: "%s"', $this->asset->getPath()));
		}

		$level = ob_get_level();

		try
		{
			$this->asset->includeAs($this->scope, $variables);
		}
		catch (Throwable $e)
		{
			for ($i = ob_get_level() + 1; $i > $level; --$i)
			{
				ob_end_clean();
			}

			throw new PhpViewException($this, sprintf('Can not render view, execution failure: "%s"', $this->asset->getPath()), $e);
		}

		for ($i = ob_get_level(); $i > $level; --$i)
		{
			ob_end_flush();
		}

		$content = ob_get_clean();

		if ($base = $this->scope->getBaseView())
		{
			$content = $base->render([ 'content' => $content, 'view' => $this ]);
		}

		return $content;
	}
}
