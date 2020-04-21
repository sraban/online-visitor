<?php

namespace Sraban\OnlineVisitor\Models;

use Sraban\OnlineVisitor\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeWebHistory extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	#protected $primaryKey = 'ip_address';

    protected $fillable = [
        'url','ip_address'
    ];

    function employee() {
    	return $this->belongsTo('Sraban\OnlineVisitor\Models\Employee','ip_address');
    }
}
