<?php

namespace Supaapps\Supalara\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlActionType extends BaseModel
{
    use HasFactory;

    protected $fillable = ['code'];

    public const ACT_MODEL_C = 1;
    public const ACT_MODEL_U = 2;
    public const ACT_MODEL_D = 3;
}
