<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'naziv_hr', 'naziv_en', 'zadatak', 'tip_studija'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
{
    return $this->hasMany(Application::class);
}

    
}
