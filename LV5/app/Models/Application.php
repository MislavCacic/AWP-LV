<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{

    protected $fillable = [
        'task_id',
        'prioritet',
        'accepted',
    ];
    

    public function user()
{
    return $this->belongsTo(User::class);
}

public function task()
{
    return $this->belongsTo(Task::class);
}

}
