<?php
/**
 * Test script for the ORM implementation
 *
 * @todo Write real tests
 * @todo Allow Entity definitions using JSON and Yaml
 * @todo Add ID objects (compound ids)
 * @todo Enable EntityBuilder to support compound ids
 * @todo Complete the Operator list
 * @todo Add triggers for HCs
 * @todo Ensure the support of Entity / Collection status
 * @todo Add an example for handling of start_date, duration, end_date
 * @todo Add documentation
 * @todo Add eager loading
 * @todo Commit Joomla\ORM to joomla-framework
 */

namespace PoC;

use Joomla\ORM\Definition\Locator\Locator;
use Joomla\ORM\Definition\Locator\Strategy\RecursiveDirectoryStrategy;
use Joomla\ORM\Entity\Entity;
use Joomla\ORM\Entity\EntityInterface;
use Joomla\ORM\Finder\CollectionFinderInterface;
use Joomla\ORM\Finder\EntityFinderInterface;
use Joomla\ORM\Persistor\PersistorInterface;
use Joomla\ORM\Repository\Repository;
use Joomla\ORM\Repository\StorageProviderInterface;

include "../code/Joomla/autoload.php";

class MockApiFactory implements StorageProviderInterface
{
	public function getEntityFinder($entityName)
	{
		return new MockModel;
	}

	public function getCollectionFinder($entityName)
	{
		return new MockModel;
	}

	public function getPersistor($entityName)
	{
		return new MockModel;
	}
}

class MockModel implements EntityFinderInterface, CollectionFinderInterface, PersistorInterface
{
	private $conditions = [];

	public function orderBy($column, $direction = 'ASC')
	{
		return $this;
	}

	public function columns($columns)
	{
		return $this;
	}

	public function with($lValue, $op, $rValue)
	{
		$this->conditions[] = "{$lValue} {$op} '{$rValue}'";

		return $this;
	}

	public function get($count = 0, $start = 0)
	{
		return print_r($this->conditions, true);
	}

	public function store(EntityInterface $entity)
	{
		echo "Storing {$entity->type()}#{$entity->id}\n";
	}

	public function delete(EntityInterface $entity)
	{
		echo "Deleting {$entity->type()}#{$entity->id}\n";
	}
}

$locator = new Locator([
	new RecursiveDirectoryStrategy(__DIR__),
]);
$repository = new Repository('Article', $locator);

/** @var Entity $entity */
$entity = $repository->create();

echo "\nInternal structure:\n";
print_r($entity->getDefinition());

echo "\nResetting\n";
$entity = $repository->create();

echo "\nbelongTo: ";
print_r($entity->parent);
echo "\n";

echo "\nSetting parent_id = 23\n";
$entity->parent_id = '23';

echo "\nbelongsTo (first access): ";
print_r($entity->parent);

echo "\nbelongsTo (other access): ";
print_r($entity->parent);

echo "\nResetting\n";
$entity = $repository->create();

echo "\nhasOne: ";
print_r($entity->hasThisAsExtension);
echo "\n";

echo "\nSetting id = 42\n";
$entity->id = '42';

echo "\nhasOne (first access): ";
print_r($entity->hasThisAsExtension);

echo "\nhasOne (other access): ";
print_r($entity->hasThisAsExtension);

echo "\nResetting\n";
$entity = $repository->create();

echo "\nhasMany: ";
print_r($entity->haveThisAsParent);
echo "\n";

echo "\nSetting id = 42\n";
$entity->id = '42';

echo "\nhasMany (first access): ";
print_r($entity->haveThisAsParent);

echo "\nhasMany (other access): ";
print_r($entity->haveThisAsParent);

echo "\nLocking\n";
$entity->lock();

echo "\nChanging id after locking\n";
try {
	$entity->id = '43';
} catch (\Exception $e) {
	echo "Exception: " . $e->getMessage();
}
