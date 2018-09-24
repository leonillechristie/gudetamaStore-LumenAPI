<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $hidden = [
		'user_id'
	];

	public function products()
	{
		return $this->belongsToMany('App\Product');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}
}