<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    public $status;
    public $message;
    public $data;
    
    public function __construct() {
        $this->status = false;
        $this->message = null;
        $this->data = new \StdClass();
    }
    
    public function get()
    {
        return (object) [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
        
    }
    
}
