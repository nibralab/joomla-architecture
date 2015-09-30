<?php
/**
 * Part of the Joomla Framework ORM Package
 *
 * @copyright  Copyright (C) 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\ORM\Validator;

use Joomla\ORM\Entity\EntityInterface;

/**
 * Interface ValidatorInterface
 *
 * @package  Joomla\ORM
 * @since    1.0
 */
interface ValidatorInterface
{
	/**
	 * Validate an entity.
	 *
	 * @param   EntityInterface  $entity  The entity to validate
	 *
	 * @return  bool
	 */
	public function check(EntityInterface $entity);

	/**
	 * Sanitise an entity.
	 *
	 * @param   EntityInterface $entity The entity to sanitise
	 *
	 * @return  bool
	 */
	public function sanitise(EntityInterface $entity);
}
