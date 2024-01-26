# Supaapps Laravel API Kit <!-- omit in toc -->

Boilerplate and helpers for Supaapps Laravel projects

## Table of content <!-- omit in toc -->

- [Installation](#installation)
- [Usage](#usage)
  - [Generate CRUD controller](#generate-crud-controller)
  - [CRUD](#crud)
  - [Available CRUD properties](#available-crud-properties)
  - [Properties used by `CrudIndexTrait`](#properties-used-by-crudindextrait)
  - [Properties used by `UpdateIndexTrait`](#properties-used-by-updateindextrait)
  - [Properties used by `DeleteIndexTrait`](#properties-used-by-deleteindextrait)
- [CRUD Controller Override](#crud-controller-override)
  - [Override methods in `CrudIndexTrait`](#override-methods-in-crudindextrait)
- [User signature](#user-signature)
- [Tests](#tests)
- [Linting](#linting)
- [Useful links](#useful-links)

## Installation

```php
composer require supaapps/laravel-api-kit
```

## Usage

### Generate CRUD controller

To create crud controller, run the following command:

```sh
php artisan make:crud-controller
```

### CRUD

To get advantage of CRUD boilerplate controller, extend `BaseCrudController` in your controller. **Example**:

```php
use Supaapps\LaravelApiKit\Controllers\BaseCrudController;

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

### Properties used by `CrudIndexTrait`

- Paginate the response from index response or not.

```php
public bool $shouldPaginate = false;
```

<br/>

- Perform searches on single column using the `search` parameter from the request. *If you want to search multiple columns use `$searchSimilarFields`, see next property.*

```php
public ?string $searchField = null; // replace with desired column
```

<br/>

> #### All of the upcoming properties should be array. If you want to add some logic head to [CRUD controller override](#crud-controller-override) <!--omit in toc-->

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
    'ids',
    'codes',
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

### Properties used by `UpdateIndexTrait`

- Disable updates on the model.

```php
public bool $readOnly = false;
```

### Properties used by `DeleteIndexTrait`

- Enable deletion for the model.

```php
public bool $isDeletable = false;
```

---

## CRUD Controller Override

If you want to add more logic to properties, you can override properties in your controller using getters. For example: you want to return different `$searchExactFields` depending on a condition:

```php
private function getSearchExactFields(): array
{
    if (request('user_type') == 'admin') {
        return [
            'admin_id'
        ];
    }

    return [
        'user_id'
    ];
}
```

### Override methods in `CrudIndexTrait`

- Override `$searchSimilarFields`

```php
private function getSearchSimilarFields(): array;
```

- Override `$searchExactFields`

```php
private function getSearchExactFields(): array;
```

- Override `$searchDateFields`

```php
private function getSearchDateFields(): array;
```

- Override `$filters`

```php
private function getFilters(): array;
```

- Override `$dateFilters`

```php
private function getDateFilters(): array;
```

- Override `$isEmptyFilters`

```php
private function getIsEmptyFilters(): array;
```

- Get allowed list that can be ordered by

```php
// by default, it merges the values from:
//    $this->getSearchSimilarFields(),
//    $this->getSearchExactFields(),
//    $this->getSearchDateFields()
private function getOrderByColumns(): array;
```

- Override `$defaultOrderByColumns`

```php
private function getDefaultOrderByColumns(): ?array;
```

---

## User signature

You can keep track of `created` and `updated` user of your model in 3 steps:

1. Include required columns to your model `schema`

```php
Schema::table('articles', function (Blueprint $table) {
    $table->auditIds();
});
```

Where `auditIds` accepts 2 parameters

- `table`: the related table name, default: `users`
- `column`: the related column name on related table, default `id`

2. Include custom columns to your model `$fillable` property

```php
protected $fillable = [
    'created_by_id',
    'updated_by_id',
    ...
]
```

3. Add observer to your model

```php
// in src/Providers/EventServiceProvider.php add the following line

protected $observers = [
    \App\Models\Article::class => [ // replace Article with your model
        \Supaapps\LaravelApiKit\Observers\UserSignatureObserver::class,
    ],
    ...
];
```

---

## Tests

```sh
composer test
```

## Linting

```sh
composer lint
```

## Useful links

- https://www.laravelpackage.com/
- https://github.com/orchestral/testbench
- https://github.com/jfcherng/php-diff
