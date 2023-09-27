# Supalara <!-- omit in toc -->

Boilerplate and helpers for Supaapps Laravel projects

## Table of content <!-- omit in toc -->

- [Installation](#installation)
- [Usage](#usage)
  - [CRUD](#crud)
  - [Available CRUD properties](#available-crud-properties)
- [Overrides](#overrides)
  - [CRUD Controller](#crud-controller)
- [Todo](#todo)

## Installation

```php
composer require supaapps/supalara
```

## Usage

### CRUD

To get advantage of CRUD boilerplate controller, extend `BaseCrudController` in your controller. **Example**:

```php
use Supaapps\Supalara\Controllers\BaseCrudController;

class ExampleController extends BaseCrudController
{
    public string $model = \App\Models\Example::class; // replace with your model
}
```

### Available CRUD properties

There are multiple properties you can use within your CRUD controller:

- The model for CRUD operations. (**required**)

```php
public string $model = \App\Models\Example::class; // replace with your model
```

<br/>

- Paginate the response from index response or not. (default = `false`)

```php
public bool $shouldPaginate = false;
```

<br/>

- Enable deletion for the model. (default = `false`)

```php
public bool $isDeletable = false;
```

<br/>

- Disable updates on the model. (default = `false`)

```php
public bool $readOnly = false;
```

<br/>

- Perform searches on the specified column using the `search` parameter from the request.

```php
public ?string $searchField = null; // replace with desired column
```

<br/>

- Perform a lookup for <u>similar</u> results in the specified columns using the **`LIKE` operator** with the `search` parameter from the request.

```php
public array $searchSimilarFields = [];
```

In following example, it lockups for `%supa%` in either `name` or `description`

```php
// user hit endpoint > /example?search=supa

public array $searchSimilarFields = [
    'name',
    'description',
];
```

<br/>

- Perform a lookup for <u>exact</u> results in the specified columns using the **`=` operator** with the `search` parameter from the request.

```php
public array $searchExactFields = [];
```

In following example, it lockups for `1` in `id`, `price` or `category_id`

```php
// user hit endpoint > /example?search=1

public array $searchExactFields = [
    'id',
    'price',
    'category_id',
];
```

<br/>

- Perform a lookup for results in the specified columns wrapping the `search` parameter from the request with `DATE()` mysql function.

```php
public array $searchDateFields = [];
```

In following example, it lockups for `2023-09-26` in `completed_at`, `created_at` or `updated_at`

```php
// user hit endpoint > /example?search=2023-09-26

public array $searchDateFields = [
    'completed_at',
    'created_at',
    'updated_at',
];
```

<br/>

- Filter columns by exact given values. Ensure that the columns entered are in plural form.

```php
public array $filters = [];
```

In the following example, it will apply the query `WHERE id IN (1, 2) AND code IN ('ABC')`

```php
// user hit endpoint > /example?ids[]=1&ids[]=2&codes[]=ABC

public array $filters = [
    'ids' => [],
    'codes' => [],
];
```

<br/>

- Filter date columns by min and max values

```php
public array $dateFilters = [];
```

In the following example, it will search the records that have `created_at` larger than `create_at_min` and less than `created_at_max` and `updated_at` larger than `updated_at_min`

```php
// user hit endpoint > /example?created_at_min=2023-09-01&created_at_max=2023-09-30&updated_at_min=2023-09-15

public array $dateFilters = [
    'created_at',
    'updated_at',
];
```

<br/>

- Filter columns that are `NULL` or `NOT NULL`

```php
public ?array $isEmptyFilters = [];
```

In the following example, user wants to get the completed and not cancelled rewards. These rewards have `completed_at IS NOT NULL` and `cancelled_at IS NULL`

```php
// user hit endpoint > /example?is_empty[completed_at]=false&is_empty[cancelled_at]=true

public ?array $isEmptyFilters = [
    'completed_at',
    'cancelled_at',
];
```

<br/>

- Define default order by column

```php
public ?array $defaultOrderByColumns = null;
```

In the following example, there are 2 order by rules are defined in the controller. The results will be ordered by `created_at` descending and by `id` ascending.

```php
public ?array $defaultOrderByColumns = [
    'created_at,desc',
    'id,asc'
];
```

But if the request has `sort` query parameter, then it will override the `defaultOrderByColumns`. **Example**:

```js
/sort?sort[id]=desc&sort[name]=asc
```

This will sort the results first by `id` descending then by `name` ascending

---

## Overrides

### CRUD Controller

You can override properties in your controller using getters

<!-- TODO -->
---

## Todo

- [X] Add Basic CRUD functions
- [X] Add Basic audit & observers
- [ ] Tests for Crud
- [ ] Tests for Audit
- [ ] Publish Dockerfile
