<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annoucement extends Model
{
    use HasFactory;
    protected $table = 'announcements' ;
    protected $fillable = ['title', 'description' ,'date' ,'location' , 'organizer_id', 'required_skills','type'];
    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

}
