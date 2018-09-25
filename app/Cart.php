<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
Class Cart extends Model 
{
	protected $fillable = ['productName', 'productid', 'userid', 'cartID'];
}