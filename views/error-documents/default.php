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

$counter = 0;

$title = $status . ' ' . __http_status_message($status);

$document = $this->getHtmlDocument();
$document->setTitle($title);
$document->addInlineStyle('lightbit://views/error-documents/css/main');

?>

<!DOCTYPE html />

<html lang="en">
	<head><?= $document->inflate('head') ?></head>
	<body>
		<?= __html_element('h1', null, $title) ?>
		<?php if (__debug()) : ?>
		<hr />
		<?php while ($throwable) : ++$counter; ?>
		<h2><?= __html_encode($throwable->getMessage()) ?></h2>
		<p class="monospace"><?= __html_encode(__type_of($throwable)) ?></p>
		<?= __html_element
		(
			'pre',
			null,
			(
				__html_element('strong', null, $throwable->getFile() . ' :' . $throwable->getLine()) .
				PHP_EOL . 
				$throwable->getTraceAsString()
			),
			false
		) ?>
		<?php $throwable = $throwable->getPrevious(); endwhile; ?>
		<?php endif; ?>
	</body>
</html>