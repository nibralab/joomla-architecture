<?php
/**
 * Part of the Joomla Framework ORM Package
 *
 * @copyright  Copyright (C) 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\ORM\Definition\Locator;

/**
 * Interface LocatorInterface
 *
 * @package  Joomla\ORM
 * @since    1.0
 */
interface LocatorInterface
{
	/**
	 * Find the XML description file for an entity
	 *
	 * @param   string  $entityName  The name of the entity
	 *
	 * @return  string  Path to the XML file
	 */
	public function findXmlFile($entityName);
}
