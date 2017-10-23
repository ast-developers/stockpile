<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
	protected $table = "debtors_master";
    protected $primaryKey = 'debtor_no';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
