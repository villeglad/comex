<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'business_id',
        'company_form',
        'name',
        'registration_date'
    ];

    public function addresses()
    {
        $this->hasMany('App\Address');
    }

    public function createdBy()
    {
        $this->belongsTo('App\User');
    }

    public function businessLines()
    {
        $this->belongsToMany('App\BusinessLine');
    }

}
