<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Tag extends \Spatie\Tags\Tag
{
    use HasTags;
}
