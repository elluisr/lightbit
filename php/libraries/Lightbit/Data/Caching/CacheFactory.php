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

namespace Lightbit\Data\Caching;

use \Lightbit\Data\Caching\Apcu\ApcuCache;
use \Lightbit\Data\Caching\Op\OpCache;
use \Lightbit\Data\Caching\Simulation\SimulationCache;

use \Lightbit\Data\Caching\ICacheFactory;
use \Lightbit\Data\Caching\IFileCache;
use \Lightbit\Data\Caching\IMemoryCache;
use \Lightbit\Data\Caching\INetworkCache;

/**
 * CacheFactory.
 *
 * @author Datapoint — Sistemas de Informação, Unipessoal, Lda.
 * @since 2.0.0
 */
class CacheFactory implements ICacheFactory
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{

	}

	/**
	 * Creates the file cache.
	 *
	 * @throws CacheFactoryException
	 *	Thrown if the cache fails to be created.
	 *
	 * @return IFileCache
	 *	The file cache.
	 */
	public function createFileCache() : IFileCache
	{
		return new OpCache();
	}

	/**
	 * Creates the memory cache.
	 *
	 * @throws CacheFactoryException
	 *	Thrown if the cache fails to be created.
	 *
	 * @return IMemoryCache
	 *	The memory cache.
	 */
	public function createMemoryCache() : IMemoryCache
	{
		if (function_exists('apcu_store'))
		{
			return new ApcuCache();
		}

		return new SimulationCache();
	}

	/**
	 * Creates the network cache.
	 *
	 * @throws CacheFactoryException
	 *	Thrown if the cache fails to be created.
	 *
	 * @return INetworkCache
	 *	The network cache.
	 */
	public function createNetworkCache() : INetworkCache
	{
		return new SimulationCache();
	}
}
