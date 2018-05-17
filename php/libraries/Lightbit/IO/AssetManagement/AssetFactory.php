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

namespace Lightbit\IO\AssetManagement;

use \Lightbit\IO\AssetManagement\ConstructionAssetFactoryException;
use \Lightbit\IO\AssetManagement\IAsset;
use \Lightbit\IO\AssetManagement\Directory\DirectoryAsset;
use \Lightbit\IO\AssetManagement\Php\PhpAsset;

/**
 * AssetFactory.
 *
 * @author Datapoint — Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
final class AssetFactory implements IAssetFactory
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{

	}

	/**
	 * Creates an asset.
	 *
	 * @throws ConstructionAssetFactoryException
	 *	Thrown if the asset construction fails.
	 *
	 * @param string $type
	 *	The asset type.
	 *
	 * @param string $id
	 *	The asset identifier.
	 *
	 * @param string $filePath
	 *	The asset file path.
	 *
	 * @return IAsset
	 *	The asset.
	 */
	public final function createAsset(string $type, string $id, string $filePath) : IAsset
	{
		switch ($type)
		{
			case 'directory':
				return new DirectoryAsset($id, $filePath);

			case 'php':
				return new PhpAsset($id, $filePath);
		}

		return new Asset($id, $filePath);
	}
}