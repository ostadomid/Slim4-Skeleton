<?php
namespace App\Models;
class Publisher extends \Illuminate\Database\Eloquent\Model{
    protected $fillable = ['name'];
    public function authors(){
        return $this->hasMany(Author::class,'publisher_id','id');
    }
}