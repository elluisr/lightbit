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

namespace Lightbit\Html;

use \Lightbit\Html\HtmlDocumentFactory;
use \Lightbit\Html\HtmlDocumentFactoryException;

use \Lightbit\Html\IHtmlDocument;
use \Lightbit\Html\IHtmlDocumentFactory;

/**
 * HtmlDocumentProvider.
 *
 * @author Datapoint — Sistemas de Informação, Unipessoal, Lda.
 * @since 2.0.0
 */
final class HtmlDocumentProvider
{
	/**
	 * The singleton instance.
	 */
	private static $instance;

	/**
	 * Gets the singleton instance.
	 *
	 * @return HtmlDocumentProvider
	 *	The singleton instance.
	 */
	public static final function getInstance() : HtmlDocumentProvider
	{
		return (self::$instance ?? (self::$instance = new HtmlDocumentProvider()));
	}

	/**
	 * The document.
	 *
	 * @var IHtmlDocument
	 */
	private $document;

	/**
	 * Constructor.
	 */
	private function __construct()
	{

	}

	/**
	 * Gets the document.
	 *
	 * @throws HtmlDocumentFactoryException
	 *	Thrown if the document creation fails.
	 *
	 * @return IHtmlDocument
	 *	The document.
	 */
	public final function getDocument() : IHtmlDocument
	{
		return ($this->document ?? ($this->document = $this->getDocumentFactory()->createDocument()));
	}

	/**
	 * Gets the document factory.
	 *
	 * @return IHtmlDocumentFactory
	 *	The document factory.
	 */
	public final function getDocumentFactory() : IHtmlDocumentFactory
	{
		return ($this->documentFactory ?? ($this->documentFactory = new HtmlDocumentFactory()));
	}

}
