<?php
namespace Joomla\Frontend;

class Payload
{
	/** A command has been accepted for later processing. */
	const ACCEPTED = 'ACCEPTED';

	/** A command failed to be accepted. */
	const NOT_ACCEPTED = 'NOT_ACCEPTED';

	/** An authentication attempt succeeded. */
	const AUTHENTICATED = 'AUTHENTICATED';

	/** The user is not authenticated. */
	const NOT_AUTHENTICATED = 'NOT_AUTHENTICATED';

	/** An authorization request succeeded. */
	const AUTHORIZED = 'AUTHORIZED';

	/** The user is not authorized for the action. */
	const NOT_AUTHORIZED = 'NOT_AUTHORIZED';

	/** A creation attempt succeeded. */
	const CREATED = 'CREATED';

	/** A creation attempt failed. */
	const NOT_CREATED = 'NOT_CREATED';

	/** A deletion attempt succeeded. */
	const DELETED = 'DELETED';

	/** A deletion attempt failed. */
	const NOT_DELETED = 'NOT_DELETED';

	/** A query successfully returned results. */
	const FOUND = 'FOUND';

	/** A query failed to return results. */
	const NOT_FOUND = 'NOT_FOUND';

	/** An update attempt succeeded. */
	const UPDATED = 'UPDATED';

	/** An update attempt failed. */
	const NOT_UPDATED = 'NOT_UPDATED';

	/** User input was valid. */
	const VALID = 'VALID';

	/** User input was invalid. */
	const NOT_VALID = 'NOT_VALID';

	/** A command is in-process but not finished. */
	const PROCESSING = 'PROCESSING';

	/** There was a generic success of some sort. */
	const SUCCESS = 'SUCCESS';

	/** There was a major error of some sort. */
	const ERROR = 'ERROR';

	/** There was a generic failure of some sort. */
	const FAILURE = 'FAILURE';

	protected $status;
	protected $input;
	protected $output;
	protected $messages;
	protected $extras;

	/**
	 * Sets the status of this output.
	 *
	 * @param mixed $status The status for this output.
	 *
	 * @return $this
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Gets the status of this output.
	 *
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Sets the input received by the domain layer.
	 *
	 * @param mixed $input The input received by the domain layer.
	 *
	 * @return $this
	 */
	public function setInput($input)
	{
		$this->input = $input;

		return $this;
	}

	/**
	 * Gets the input received by the domain layer.
	 *
	 * @return mixed
	 */
	public function getInput()
	{
		return $this->input;
	}

	/**
	 * Sets the output produced from the domain layer.
	 *
	 * @param mixed $output The output produced from the domain layer.
	 *
	 * @return $this
	 */
	public function setOutput($output)
	{
		$this->output = $output;

		return $this;
	}

	/**
	 * Gets the output produced from the domain layer.
	 *
	 * @return mixed
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 * Sets the messages produced by the domain layer.
	 *
	 * @param mixed $messages The messages produced by the domain layer.
	 *
	 * @return $this
	 */
	public function setMessages($messages)
	{
		$this->messages = $messages;

		return $this;
	}

	/**
	 * Gets the messages produced by the domain layer.
	 *
	 * @return mixed
	 */
	public function getMessages()
	{
		return $this->messages;
	}

	/**
	 * Sets arbitrary extra values produced by the domain layer.
	 *
	 * @param mixed $extras Arbitrary extra values produced by the domain layer.
	 *
	 * @return $this
	 */
	public function setExtras($extras)
	{
		$this->extras = $extras;

		return $this;
	}

	/**
	 * Gets arbitrary extra values produced by the domain layer.
	 *
	 * @return mixed
	 */
	public function getExtras()
	{
		return $this->extras;
	}
}
