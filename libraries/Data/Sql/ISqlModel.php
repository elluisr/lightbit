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

namespace Lightbit\Data\Sql;

use \Lightbit\Data\IModel;

/**
 * ISqlModel.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
interface ISqlModel extends IModel
{
	/**
	 * Creates, prepares and executes a query statement that's meant to fetch
	 * a single result as an instance of this model.
	 *
	 * @param string $statement
	 *	The statement to create, prepare and execute, as a string.
	 *
	 * @param array $arguments
	 *	The statement arguments.
	 *
	 * @return ISqlModel
	 *	The result.
	 */
	public function single(string $statement, array $arguments = null) : ?ISqlModel;

	/**
	 * Creates, prepares and executes a query statement to fetch all results
	 * as instances of this model.
	 *
	 * @param string $statement
	 *	The statement to prepare, execute and read from.
	 *
	 * @param array $arguments
	 *	The statement arguments.
	 *
	 * @return array
	 *	The results.
	 */
	public function query(string $statement, array $arguments = null) : array;
}