<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\EventImage;

class Event extends Model
{
    use HasFactory;

    protected $table = "events";
    protected $primaryKey = "id";

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images(){
        return $this->hasMany(EventImage::class, 'event_id');
    }
}
