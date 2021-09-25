<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = 'employees';
    protected $primaryKey = "empl_id";
    protected $fillable = [
        'name','email', 'user_id','desig_id','image',
    ];

    public function designation()
    {
        return $this->hasOne('App\Designation', 'id', 'desig_id');
    }
    
}

