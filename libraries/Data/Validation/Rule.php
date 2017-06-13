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

use \Lightbit\Base\Element;
use \Lightbit\Data\IModel;
use \Lightbit\Data\Validation\EmailAddressRule;
use \Lightbit\Data\Validation\IRule;
use \Lightbit\Data\Validation\SafeRule;
use \Lightbit\Helpers\ObjectHelper;
use \Lightbit\Exception;

/**
 * Rule.
 *
 * @author Datapoint – Sistemas de Informação, Unipessoal, Lda.
 * @since 1.0.0
 */
abstract class Rule extends Element implements IRule
{
	/**
	 * Creates a new rule.
	 *
	 * @param IModel $model
	 *	The rule model.
	 *
	 * @param string $id
	 *	The rule identifier.
	 *
	 * @param array $configuration
	 *	The rule configuration.
	 *
	 * @return IRule
	 *	The rule.
	 */
	public static function create(IModel $model, string $id, array $configuration) : IRule
	{
		static $rulesClassName = 
		[
			'email-address' => EmailAddressRule::class,
			'safe' => SafeRule::class
		];

		if (!isset($configuration['@class']))
		{
			throw new Exception(sprintf('Bad validation rule configuration, missing class name: "%s", at model "%s"', $id, get_class($model)));
		}

		$ruleClassName = isset($rulesClassName[$configuration['@class']])
			? $rulesClassName[$configuration['@class']]
			: $configuration['@class'];

		return new $ruleClassName($model, $id, $configuration);
	}

	/**
	 * The attributes name.
	 *
	 * @type array
	 */
	private $attributesName;

	/**
	 * The identifier.
	 *
	 * @type string
	 */
	private $id;

	/**
	 * The messages.
	 *
	 * @type array
	 */
	private $messages;

	/**
	 * The model.
	 *
	 * @type string
	 */
	private $model;

	/**
	 * The required flag.
	 *
	 * @type bool
	 */
	private $required;

	/**
	 * The safe flag.
	 *
	 * @type bool
	 */
	private $safe;

	/**
	 * The scenarios.
	 *
	 * @type array
	 */
	private $scenarios;

	/**
	 * Constructor.
	 *
	 * @param IModel $model
	 *	The rule model.
	 *
	 * @param string $id
	 *	The rule identifier.
	 *
	 * @param array $configuration
	 *	The rule configuration.
	 */
	public function __construct(IModel $model, string $id, array $configuration = null)
	{
		$this->model = $model;
		$this->id = $id;
		$this->required = false;
		$this->safe = true;

		$this->messages = 
		[
			'empty' => 'Value of "{attribute}" must not be empty.'
		];

		if ($configuration)
		{
			ObjectHelper::configure($this, $configuration);
		}
	}

	/**
	 * Exports attributes.
	 *
	 * If the rule matches the model scenario, each attribute that it applies 
	 * to, if present, will be assigned to the model before validation and any
	 * encountered errors will be reported for proper action.
	 *
	 * @param array $attributes
	 *	The attributes to export.
	 */
	public final function export(array $attributes) : void
	{
		if ($this->hasScenario($this->model->getScenario()))
		{
			foreach ($attributes as $attribute => $subject)
			{
				if ($this->isSafe() && $this->hasAttribute($attribute))
				{
					$this->model->setAttribute($attribute, $subject);

					if ($subject)
					{
						if (!$this->validateAttribute($this->model, $attribute, $subject))
						{
							$result = false;
						}
					}

					else if ($required)
					{
						$this->report('empty', $attribute);
						$result = false;
					}
				}
			}
		}
	}

	/**
	 * Gets the attributes name.
	 *
	 * @return array
	 *	The attributes name.
	 */
	public final function getAttributesName() : array
	{
		if (!isset($this->attributesName))
		{
			return $this->model->getAttributesName();
		}

		return $this->attributesName;		
	}

	/**
	 * Gets the identifier.
	 *
	 * @return string
	 *	The identifier.
	 */
	public final function getID() : string
	{
		return $this->id;
	}

	/**
	 * Gets the model.
	 *
	 * @return IModel
	 *	The model.
	 */
	public final function getModel() : IModel
	{
		return $this->model;
	}

	/**
	 * Checks for an attribute applicability.
	 *
	 * @param string $attribute
	 *	The attribute name.
	 */
	public final function hasAttribute(string $attribute) : bool
	{
		return in_array($attribute, $this->getAttributesName());
	}

	/**
	 * Checks for an scenario applicability.
	 *
	 * @param string $scenario
	 *	The scenario.
	 */
	public function hasScenario(string $scenario) : bool
	{
		return isset($this->scenarios)
			? in_array($scenario, $this->scenarios)
			: true;
	}

	/**
	 * Checks the required flag.
	 *
	 * @return bool
	 *	The result.
	 */
	public final function isRequired() : bool
	{
		return $this->required;
	}

	/**
	 * Checks the safe flag.
	 *
	 * @return bool
	 *	The result.
	 */
	public final function isSafe() : bool
	{
		return $this->safe;
	}

	/**
	 * Reports an attribute error message.
	 *
	 * @param string $attribute
	 *	The attribute name.
	 *
	 * @param string $message
	 *	The message content or identifier.
	 *
	 * @param array $arguments
	 *	The message arguments.
	 */
	protected final function report(string $attribute, string $message, array $arguments = null) : void
	{
		if (isset($this->messages[$message]))
		{
			$message = $this->messages[$message];
		}

		// TODO message formatting
		$this->model->addAttributeError($attribute, $message);
	}

	/**
	 * Sets the attributes name.
	 *
	 * @return array
	 *	The attributes name.
	 */
	public final function setAttributesName(?array $attributesName) : void
	{
		$this->attributesName = isset($attributesName)
			? array_intersect($this->model->getAttributesName(), $attributesName)
			: null;
	}

	/**
	 * Sets a validation message.
	 *
	 * @param string $id
	 *	The message identifier.
	 *
	 * @param string $content
	 *	The message content.
	 */
	public final function setMessage(string $id, string $message) : void
	{
		$this->messages[$id] = $message;
	}

	/**
	 * Sets validation messages.
	 *
	 * @param array $messages
	 *	The messages.
	 *
	 * @param bool $merge
	 *	The message merge flag.
	 */
	public final function setMessages(array $messages, bool $merge = true) : void
	{
		$this->messages = ($merge && $this->messages)
			? $messages + $this->messages
			: $messages;
	}

	/**
	 * Sets the required flag.
	 *
	 * @param bool $required
	 *	The required flag.
	 */
	public final function setRequired(bool $required) : void
	{
		$this->required = $required;
	}

	/**
	 * Sets the safe flag.
	 *
	 * @param bool $safe
	 *	The safe flag.
	 */
	public final function setSafe(bool $safe) : void
	{
		$this->safe = $safe;
	}

	/**
	 * Sets the scenarios.
	 *
	 * @param array $scenarios
	 *	The scenarios.
	 */
	public final function setScenarios(?array $scenarios) : void
	{
		$this->scenarios = $scenarios;
	}

	/**
	 * Validates the model.
	 *
	 * If the rule matches the model scenario, each attribute that it applies 
	 * to will be validated and any encountered errors will be reported for
	 * proper action.
	 *
	 * If an attribute requires transformation, the new value must be set once
	 * the original passes validation.
	 *
	 * @return bool
	 *	The result.
	 */
	public final function validate() : bool
	{
		$result = true;

		if ($this->hasScenario($this->model->getScenario()))
		{
			foreach ($this->getAttributesName() as $i => $attribute)
			{
				$subject = $this->model->getAttribute($attribute);

				if ($subject)
				{
					if (!$this->validateAttribute($this->model, $attribute, $subject))
					{
						$result = false;
					}
				}

				else if ($required)
				{
					$this->report('empty', $attribute);
					$result = false;
				}
			}
		}

		return $result;
	}

	/**
	 * Validates a single attribute.
	 *
	 * By the time this method is called, the rule is confirmed to apply to
	 * the given model and attribute as this method is meant only to validate
	 * and, if necessary, report any encountered errors.
	 *
	 * If the attribute requires transformation, the new value must be set once
	 * the original passes validation.
	 *
	 * @param IModel $model
	 *	The model.
	 *
	 * @param string $attribute
	 *	The attribute name.
	 *
	 * @param string $subject
	 *	The attribute.
	 *
	 * @return bool
	 *	The result.
	 */
	protected function validateAttribute(IModel $model, string $attribute, $subject) : bool
	{
		return true;
	}
}