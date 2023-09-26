# Supalara <!-- omit in toc -->

Boilerplate and helpers for Supaapps Laravel projects

## Table of content <!-- omit in toc -->

- [Installation](#installation)
- [Usage](#usage)
  - [CRUD](#crud)
  - [Available CRUD properties](#available-crud-properties)
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

The model for CRUD operations. (**required**)

```php
public string $model = \App\Models\Example::class; // replace with your model
```

Paginate the response from index response or not. (default = `false`)

```php
public bool $shouldPaginate = false;
```

Enable deletion for the model. (default = `false`)

```php
public bool $isDeletable = false;
```

Disable updates on the model. (default = `false`)

```php
public bool $readOnly = false;
```

Perform searches on the specified column using the `search` parameter from the request.

```php
public ?string $searchField = null; // replace with desired column
```

Perform a lookup for <u>similar</u> results in the specified columns using the **`LIKE` operator** with the `search` parameter from the request.

```php
public array $searchSimilarFields = [];
```

Perform a lookup for <u>exact</u> results in the specified columns using the **`=` operator** with the `search` parameter from the request.

```php
public array $searchExactFields = [];
```

Perform a lookup for results in the specified columns wrapping the `search` parameter from the request with `DATE()` function.

```php
public array $searchDateFields = [];
```

Filter columns by exact given values

```php
public array $filters = [];
```

Filter date columns by min and max values

```php
public array $dateFilters = [];
```

Filter columns that are `NULL` or `NOT NULL`

```php
public ?array $isEmptyFilters = [];
```

Define default order by column

```php
public ?array $defaultOrderByColumns = null;
```

## Todo

- [X] Add Basic CRUD functions
- [X] Add Basic audit & observers
- [ ] Tests for Crud
- [ ] Tests for Audit
- [ ] Publish Dockerfile
