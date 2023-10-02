<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupaLaraExampleModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
    ];

    protected static function newFactory()
    {
        return SupaLaraExampleModelFactory::new();
    }
}
