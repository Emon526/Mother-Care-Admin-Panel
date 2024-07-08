<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as BaseModel;
class  Model extends BaseModel
{
protected $connection = "mongodb";

}
