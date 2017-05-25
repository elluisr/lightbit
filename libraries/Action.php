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

namespace Lightbit;

use \Lightbit\Base\IController;
use \Lightbit\Base\Object;

/**
 * Action.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
final class Action extends Object
{
	/**
	 * The arguments.
	 *
	 * @type array
	 */
	private $arguments;

	/**
	 * The controller.
	 *
	 * @type IController
	 */
	private $controller;

	/**
	 * The name.
	 *
	 * @type string
	 */
	private $name;

	/**
	 * Constructor.
	 *
	 * @param IController $controller
	 *	The controller.
	 *
	 * @param string $name
	 *	The name.
	 *
	 * @param array $arguments
	 *	The arguments.
	 */
	public function __construct(IController $controller, string $name, array $arguments)
	{
		$this->arguments = $arguments;
		$this->controller = $controller;
		$this->name = $name;
	}

	/**
	 * Gets the arguments.
	 *
	 * @return array
	 *	The arguments.
	 */
	public function getArguments() : array
	{
		return $this->arguments;
	}

	/**
	 * Gets the controller.
	 *
	 * @return IController
	 *	The controller.
	 */
	public function getController() : IController
	{
		return $this->controller;
	}

	/**
	 * Gets the identifier.
	 *
	 * @return string
	 *	The identifier.
	 */
	public function getID() : string
	{
		static $id;

		if (!$id)
		{
			$id = $this->controller->getID() . '/' . $this->name;
		}

		return $id;
	}

	/**
	 * Gets the name.
	 *
	 * @return string
	 *	The name.
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * Runs the action.
	 *
	 * @return mixed
	 *	The result.
	 */
	public function run() // : mixed
	{
		return $this->controller->{$this->controller->getActionMethodName($this->name)}(...array_values($this->arguments));
	}
}