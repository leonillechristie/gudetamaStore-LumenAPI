<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
Class Product extends Model 
{
protected $fillable = ['title', 'owner', 'catgory', 'city', 'price', 'description, image'];
}
