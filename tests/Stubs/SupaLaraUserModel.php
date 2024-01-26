<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;

class SupaLaraUserModel extends User
{
    use HasFactory;

    protected $fillable = [
        //
    ];

    protected static function newFactory()
    {
        return SupaLaraUserModelFactory::new();
    }
}
