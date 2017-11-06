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

use \Lightbit\Base\Action;
use \Lightbit\Base\Context;
use \Lightbit\Base\IComponent;
use \Lightbit\Base\Module;
use \Lightbit\Data\SlugManager;
use \Lightbit\Globalization\Locale;
use \Lightbit\Globalization\MessageSource;
use \Lightbit\Security\Cryptography\PasswordDigest;
use \Lightbit\Exception;

/**
 * IModule.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
interface IModule extends IContext
{
	/**
	 * Constructor.
	 *
	 * @param Context $context
	 *	The module context.
	 *
	 * @param string $id
	 *	The module identifier.
	 *
	 * @param string $path
	 *	The module path.
	 *
	 * @param array $configuration
	 *	The module configuration.
	 */
	public function __construct(IContext $context, string $id, string $path, array $configuration = null);

	/**
	 * Gets the application.
	 *
	 * @return IApplication
	 *	The application.
	 */
	public function getApplication() : IApplication;

	/**
	 * Generates the proper response to a throwable caught by the global
	 * exception handler during an action implemented by a child controller.
	 *
	 * If the module can not generate the proper response, false should
	 * be returned in order to delegate control to its parent, the application
	 * and the global exception handler.
	 *
	 * @param Throwable $throwable
	 *	The throwable object.
	 *
	 * @return bool
	 *	The result.
	 */
	public function throwable(\Throwable $throwable) : bool;
}
