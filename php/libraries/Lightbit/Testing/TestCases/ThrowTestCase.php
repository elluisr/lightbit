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

namespace Lightbit\Testing\TestCases;

use \Closure;
use \Throwable;

use \Lightbit\Testing\ITestCase;
use \Lightbit\Testing\ITestSuite;
use \Lightbit\Testing\TestCase;

/**
 * ThrowTestCase.
 *
 * @author Datapoint — Sistemas de Informação, Unipessoal, Lda.
 * @since 2.0.0
 */
class ThrowTestCase extends TestCase implements ITestCase
{
	/**
	 * The constraint.
	 *
	 * @var mixed
	 */
	private $constraint;

	/**
	 * Constructor.
	 *
	 * @param string $description
	 *	The test case description.
	 *
	 * @param Closure $closure
	 *	The test case closure.
	 *
	 * @param string $constraint
	 *	The test case constraint.
	 */
	public function __construct(string $description, Closure $closure, string $constraint)
	{
		parent::__construct($description, $closure, $constraint);

		$this->constraint = $constraint;
	}

	/**
	 * Validate.
	 *
	 * When called, it invokes the test case closure and performs the
	 * applicable validation on its result, failing if it does not meet
	 * expectation or if an uncaught throwable is detected.
	 *
	 * @return bool
	 *	The success status.
	 */
	public function validate() : bool
	{
		try
		{
			($this->getClosure())();
		}
		catch (Throwable $e)
		{
			if ($e instanceof $this->constraint)
			{
				return true;
			}

			$this->addThrowable($e);
		}

		return false;
	}
}
