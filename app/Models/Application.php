<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable =  [
        'volunteer_id',
        'announcement_id'
    ];
    public function announcement(){
        return $this->belongsTo(Annoucement::class);
    }
    public function volunteer(){
        return $this->belongsTo(Volunteer::class);
    }
}
