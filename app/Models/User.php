<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use A17\Twill\Models\User as TwillUser;

class User extends TwillUser
{
    use HasFactory;
}
