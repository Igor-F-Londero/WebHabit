<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $fillable = ['user_id', 'category_id', 'name', 'description', 'frequency', 'color', 'active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
