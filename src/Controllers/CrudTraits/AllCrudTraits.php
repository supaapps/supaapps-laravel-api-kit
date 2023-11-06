<?php

namespace Supaapps\LaravelApiKit\Controllers\CrudTraits;

trait AllCrudTraits
{
    use CrudIndexTrait;
    use CrudStoreTrait;
    use CrudUpdateTrait;
    use CrudShowTrait;
    use CrudDestroyTrait;
}
