# Joomla\ORM

This package provides the connection between the business logic and the persistence layer.
The central element is the `Entity`, a data structure holding the data of a domain model.

## Sample model

In the examples, the following sample data model is used.

![Sample model](../../svg/testmodel.svg)

## Repository

The `Repository` acts as a facade to the persistence layer.
Depending on the entity, it provides access to the corresponding `EntityFinder`,
`CollectionFinder`, `Validator`, and `Persistor`.

### Retrieve an entity

```php
<?php
use Joomla\ORM\Definition\Locator\Locator;
use Joomla\ORM\Definition\Locator\Strategy\RecursiveDirectoryStrategy;
use Joomla\ORM\Repository\Repository;

$locator = new Locator([
	new RecursiveDirectoryStrategy(__DIR__),
]);

$repository = new Repository('Master', $locator);
$master = $repository->findById(42);
```

This will return the record with the id 42 from the `masters` table.
The `masters` table has three relations.
It belongs to a parent, has many details, and has many tags through an association table.
The foreign keys, a kind of pointers, are stored in the master record only for the parent relation.
For the other relations, they are stored in the foreign tables.

However, in any case, the ORM will create virtual properties.
The names of these properties depend on the kind of relation.

#### Resolving relations

As mentioned before, the foreign key is stored in the current record only for `belongsTo` relations.
In this case, the name of the virtual property is derived from the field with the foreign key
by stripping the '_id' or 'id' suffix from the FK field name.
Thus, the entity referenced by `parent_id` can be accessed using the virtual property `parent`. 

A `hasOne` relation leads to a virtual property with the name of the referenced entity
in singular with lowercase letters.

The remaining relation types, `hasMany` and `hasManyThrough` also get the name of the referenced entity
in lowercase letters, but in plural.
For `Master` in the sample above, the associated Detail records can be accessed using the virtual `details` property,
and the associated Tags using `tags`. 

> **Problem**: If a detail has multiple FKs to the same entity type, how should naming be done?
> Example: Let's say, an article has an `author_id` and a `publisher_id`, both pointing to users.
> How should the property names be in the user entity?
>
> Most intuitive would be `articles_authored` and `articles_published`,
> i.e., the other entity's type in plural, followed by a verbum derived from the FK fieldname.
> Is it possible to build an inflector for that?

##### Lazy loading

By default, relations are resolved with lazy loading.
That means, that the relation data will be loaded as soon as and only if you read the virtual property assigned to that relation.

```php
<?php
use Joomla\ORM\Definition\Locator\Locator;
use Joomla\ORM\Definition\Locator\Strategy\RecursiveDirectoryStrategy;
use Joomla\ORM\Finder\Operator;
use Joomla\ORM\Repository\Repository;

$locator = new Locator([
	new RecursiveDirectoryStrategy(__DIR__),
]);

$repository = new Repository('Master', $locator);
$master = $repository
    ->findOne()
    ->with('id', Operator::EQUAL, 42)
    ->get();

$parent = $master->parent;
```

The virtual `parent` property has no value until it is accessed.
On the first read access, `parent` is queried in the background automatically 

##### Eager loading

Sometimes it is useful to retrieve related data together with the main record.
This can easily be done by specifying the virtual field in the query.

```php
<?php
use Joomla\ORM\Definition\Locator\Locator;
use Joomla\ORM\Definition\Locator\Strategy\RecursiveDirectoryStrategy;
use Joomla\ORM\Finder\Operator;
use Joomla\ORM\Repository\Repository;

$locator = new Locator([
	new RecursiveDirectoryStrategy(__DIR__),
]);

$repository = new Repository('Master', $locator);
$master = $repository
    ->findOne()
    ->columns('*', 'parent', 'tags')
    ->with('id', Operator::EQUAL, 42)
    ->get();
```

The `parent` and `tags` virtual properties are populated immediately,
but `details` is still lazy loaded.

> **Note**: The tags of the parent will not be eager loaded.
> To do so, the virtual column '`parent.tags`' must be added to the query.
