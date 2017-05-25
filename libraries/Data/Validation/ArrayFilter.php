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

namespace Lightbit\Data\Validation;

use \Lightbit\Data\Validation\Filter;
use \Lightbit\Data\Validation\FilterException;
use \Lightbit\Helpers\TypeHelper;

/**
 * ArrayFilter.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @version 1.0.0
 */
class ArrayFilter extends Filter
{
	/**
	 * The filter value type name.
	 *
	 * @type string
	 */
	private $typeName;

	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The filter configuration.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}

	/**
	 * Runs the filter.
	 *
	 * @param mixed $value
	 *	The value to run the filter on.
	 *
	 * @return array
	 *	The value.
	 */
	public function run($value) : array
	{
		if (!is_array($value))
		{
			throw new FilterException($this, sprintf('Bad filter value data type: expecting "%s", found "%s"', 'array', TypeHelper::getNameOf($value)));
		}

		if ($this->typeName)
		{
			foreach ($value as $i => $subject)
			{
				if (TypeHelper::getNameOf($subject) !== $this->typeName)
				{
					throw new FilterException($this, sprintf('Bad array value data type: expecting %s at position %s, got %s', $this->typeName, $i, TypeHelper::getNameOf($subject)));
				}
			}
		}

		return $value;
	}

	/**
	 * Returns the filter object class.
	 *
	 * @return string
	 *	The filter object class.
	 */
	public final function getTypeName() : ?string
	{
		return $this->typeName;
	}

	/**
	 * Defines the filter value type name.
	 *
	 * @param array $type
	 *	The filter value type name.
	 */
	public final function setTypeName(?string $type) : void
	{
		$this->typeName = $type;
	}
}