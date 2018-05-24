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

namespace Lightbit\Configuration;

use \Lightbit\Environment;
use \Lightbit\Exception;
use \Lightbit\AssetManagement\AssetProvider;
use \Lightbit\Configuration\Configuration;
use \Lightbit\Configuration\ConfigurationFactoryException;
use \Lightbit\Configuration\IConfigurationFactory;

/**
 * ConfigurationFactoryException.
 *
 * @author Datapoint — Sistemas de Informação, Unipessoal, Lda.
 * @since 2.0.0
 */
class ConfigurationFactory implements IConfigurationFactory
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{

	}

	/**
	 * Creates a configuration.
	 *
	 * @throws ConfigurationFactoryException
	 *	Thrown if the configuration fails to be created, regardless of the
	 *	actual reason, which should be defined in the exception chain.
	 *
	 * @param string $configuration
	 *	The configuration identifier.
	 */
	public final function createConfiguration(string $configuration) : IConfiguration
	{
		$properties = [];
		$environment = Environment::getInstance();
		$provider = AssetProvider::getInstance();

		// Base
		foreach ($provider->getAssetList('php', ('settings://' . $configuration)) as $i => $asset)
		{
			$subject = $asset->include([ 'environment' => $environment ]);

			if (is_array($subject))
			{
				$properties = $subject + $properties;
			}
		}

		// Environment
		foreach ($provider->getAssetList('php', ('settings://' . $environment->getName() . '/' . $configuration)) as $i => $asset)
		{
			$subject = $asset->include([ 'environment' => $environment ]);

			if (is_array($subject))
			{
				$properties = $subject + $properties;
			}
		}

		return new Configuration($properties);
	}
}
