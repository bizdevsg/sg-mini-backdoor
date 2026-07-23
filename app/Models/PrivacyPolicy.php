<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'content',
    'content_en',
])]
class PrivacyPolicy extends Model
{
}
