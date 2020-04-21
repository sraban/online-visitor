<?php

namespace Sraban\OnlineVisitor\Models;

use Sraban\OnlineVisitor\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //

    #protected $primaryKey = 'ip_address';

	protected $fillable = [
        'emp_id', 'emp_name', 'ip_address'
    ];

    public function history() {
        return $this->hasMany('Sraban\OnlineVisitor\Models\EmployeeWebHistory', 'ip_address', 'ip_address');
    }
}
