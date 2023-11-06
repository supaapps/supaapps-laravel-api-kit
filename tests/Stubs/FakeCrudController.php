<?php

namespace Tests\Stubs;

use Supaapps\LaravelApiKit\Controllers\BaseCrudController;
use Supaapps\LaravelApiKit\Controllers\CrudTraits\CrudIndexTrait;

class FakeCrudController extends BaseCrudController
{
    use CrudIndexTrait;
}
