<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    protected $table = 'modules';
    public function modules_data()
    {
        return $this->hasMany('App\Models\ModulesData','module_id');
    }

    public function fields()
    {
        return $this->hasMany('App\Models\FieldsShow','module_id');
    }

    public function extraFields()
    {
        return $this->hasMany('App\Models\ExtraFields','module_id');
    }

}
