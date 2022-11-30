<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Group extends Model
{
    use HasFactory;

    protected $table = "groups";
    protected $primaryKey = "id";

    public function students(){
        return $this->hasMany(Student::class, 'group_id');
    }
}
