# Joomla\ORM

This package provides the connection between the business logic and the persistence layer.
The central element is the `Entity`, a data structure holding the data of a domain model.

## Sample model

![Sample model](https://github.com/nibralab/joomla-architecture/blob/master/svg/testmodel.svg)

## Repository

The `Repository` acts as a facade to the persistence layer.
Depending on the entity, it provides access to the corresponding `EntityFinder`,
`CollectionFinder`, `Validator`, and `Persistor`.

### Retrieve an entity

```php
<?php

$repository = new Repository('Master');
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

##### Lazy loading

By default, relations are resolved with lazy loading.
That means, that the relation data will be loaded as soon as and only if you read the virtual propoerty assigned to that relation.

```php
<?php

$repository = new Repository('Master');
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

$repository = new Repository('Master');
$master = $repository
    ->findOne()
    ->columns('*', 'parent', 'tags')
    ->with('id', Operator::EQUAL, 42)
    ->get();
```

The `parent` and `tags` virtual properties are populated immediately,
but `details` is still lazy loaded.
