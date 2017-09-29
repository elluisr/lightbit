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

/**
 * Gets an attribute.
 *
 * @param array $map
 *	The map.
 *
 * @param string $type
 *	The property data type (e.g.: "int", "?int", "MyClass").
 *
 * @param string $property
 *	The property name.
 *
 * @return mixed
 *	The result.
 */
function __map_get(?array $map, ?string $type, string $property) // : mixed
{
	if ($map)
	{
		$token;

		// When attempting to access deeper properties, we'll have to go through
		// each "node" to ensure the expected types are encountered.
		if ($i = strrpos($property, '/'))
		{
			foreach (explode('/', substr($property, 0, $i)) as $k => $token)
			{
				if (!isset($map[$token]) || !is_array($map[$token]))
				{
					if ($type && $type[0] !== '?')
					{
						__throw('Can not get property from map, it is undefined: property "%s", expect "%s", "%s"', $property, $type);
					}

					return null;
				}

				$map = $map[$token];
			}

			$token = substr($property, $i + 1);
		}
		else
		{
			$token = $property;
		}

		if (!isset($map[$token]))
		{
			if ($type && $type[0] !== '?')
			{
				__throw('Can not get property from map, it is undefined: property "%s", expect "%s"', $property, $type);
			}

			return null;
		}

		if ($type && !__type_match($type, $map[$token]))
		{
			__throw('Can not get property from map, type mismatch: property "%s", expect "%s", got "%s"', $property, $type, __type_of($map[$token]));
		}

		return $map[$token];
	}

	if ($type && $type[0] !== '?')
	{
		__throw('Can not property from map, it is undefined: property "%s", expect "%s"', $property, $type);
	}

	return null;
}