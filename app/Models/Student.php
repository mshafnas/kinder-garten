<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
class Student extends Model
{
    use HasFactory;
    protected $table = "students";
    protected $primaryKey = "id";

    public function student(){
        return $this->belongsTo(Group::class);
    }
}
