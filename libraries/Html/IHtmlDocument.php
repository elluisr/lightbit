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

namespace Lightbit\Html;

use \Lightbit\Base\IComponent;

/**
 * IHtmlDocument.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
interface IHtmlDocument extends IComponent
{
	/**
	 * Sets an additional inline script.
	 *
	 * @param string $script
	 *	The inline script file system alias.
	 *
	 * @param string $position
	 *	The script position.
	 *
	 * @param array $attributes
	 *	The script attributes.
	 */
	public function addInlineScript(string $script, string $position = 'head', array $attributes = null) : void;

	/**
	 * Sets an additional inline style.
	 *
	 * @param string $style
	 *	The style file system alias.
	 *
	 * @param array $attributes
	 *	The style attributes.
	 */
	public function addInlineStyle(string $style, array $attributes = null) : void;

	/**
	 * Sets additional meta attributes.
	 *
	 * @param array $attributes
	 *	The meta attributes.
	 */
	public function addMetaAttributes(array $attributes) : void;

	/**
	 * Sets an additional script.
	 *
	 * @param string $location
	 *	The script location.
	 *
	 * @param string $position
	 *	The script position.
	 *
	 * @param array $attributes
	 *	The script attributes.
	 */
	public function addScript(string $location, string $position = 'head', array $attributes = null) : void;

	/**
	 * Sets an additional style.
	 *
	 * @param string $location
	 *	The stylesheet location.
	 *
	 * @param array $attributes
	 *	The style attributes.
	 */
	public function addStyle(string $location, array $attributes = null) : void;

	/**
	 * Sets an additional tag.
	 *
	 * @param string $tag
	 *	The tag name.
	 *
	 * @param array $attributes
	 *	The link attributes.
	 */
	public function addTag(string $tag, array $attributes = null) : void;

	/**
	 * Gets the meta attributes.
	 *
	 * @return array
	 *	The meta attributes.
	 */
	public function getMetaAttributes() : array;

	/**
	 * Gets the scripts.
	 *
	 * @return array
	 *	The scripts.
	 */
	public function getScripts() : array;

	/**
	 * Gets the styles.
	 *
	 * @return array
	 *	The styles.
	 */
	public function getStyles() : array;

	/**
	 * Gets the tags.
	 *
	 * @return array
	 *	The tags.
	 */
	public function getTags() : array;

	/**
	 * Gets the title.
	 *
	 * @return string
	 *	The title.
	 */
	public function getTitle() : ?string;

	/**
	 * Inflates the document.
	 *
	 * @param string $position
	 *	The position.
	 *
	 * @return string
	 *	The markup.
	 */
	public function inflate(string $position) : string;

	/**
	 * Resets the document.
	 */
	public function reset() : void;

	/**
	 * Sets the title.
	 *
	 * @param string $title
	 *	The title.
	 */
	public function setTitle(?string $title) : void;
}