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

namespace Lightbit\Data;

use \Lightbit\Data\IMap;
use \Lightbit\Data\MapException;

/**
 * MapValueNotFoundException.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
class MapValueNotFoundException extends MapException
{
	/**
	 * The value key.
	 *
	 * @type string
	 */
	private $key;

	/**
	 * Constructor.
	 *
	 * @param IMap $map
	 *	The map.
	 *
	 * @param string $message
	 *	The exception message.
	 *
	 * @param Throwable $previous
	 *	The previous throwable.
	 */
	public function __construct(IMap $map, $key, string $message, \Throwable $previous = null)
	{
		parent::__construct($map, $message, $previous);

		$this->key = $key;
	}

	/**
	 * Gets the value key.
	 *
	 * @return mixed
	 *	The value key.
	 */
	public final function getKey() : IMap
	{
		return $this->key;
	}
}