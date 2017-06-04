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

namespace Lightbit\Data\Sql\MySql;

use \Lightbit\Base\Object;
use \Lightbit\Data\Sql\ISqlConnection;
use \Lightbit\Data\Sql\ISqlStatement;
use \Lightbit\Data\Sql\SqlDriver;
use \Lightbit\Data\Sql\SqlStatement;

/**
 * MySqlSqlDriver.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
class MySqlSqlDriver extends SqlDriver
{
	/**
	 * Constructor.
	 *
	 * @param ISqlConnection $sqlConnection
	 *	The sql driver connection.
	 *
	 * @param array $configuration
	 *	The sql driver configuration.
	 */
	public function __construct(ISqlConnection $sqlConnection, array $configuration = null)
	{
		parent::__construct($sqlConnection, $configuration);
	}

	/**
	 * Gets the identifier.
	 *
	 * @return string
	 *	The identifier.
	 */
	public final function getID() : string
	{
		return 'mysql';
	}

	/**
	 * Gets the last insert row identifier.
	 *
	 * @return int
	 *	The last insert row identifier.
	 */
	public function getLastInsertID() : int
	{
		return $this->getSqlConnection()->scalar('SELECT LAST_INSERT_ID()');
	}

	/**
	 * Quotes a statement by replacing the ANSI double quotes with the
	 * proper quote character sequence.
	 *
	 * @param string $statement
	 *	The statement to quote.
	 *
	 * @return string
	 *	The result.
	 */
	public function quote(string $statement) : string
	{
		return strtr($statement, [ '"' => '`' ]);
	}
}