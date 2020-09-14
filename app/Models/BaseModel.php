<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public $jsonResponse;
    
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->jsonResponse = new Response();
    }
}
