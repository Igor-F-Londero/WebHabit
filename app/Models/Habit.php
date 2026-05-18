<?php

namespace App\Models;

use App\Services\StreakService;
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

    public function isCompletedToday(): bool
    {
        return $this->checkins
            ->contains(fn($c) => $c->checked_date->isToday());
    }

    public function currentStreak(): int
    {
        return app(StreakService::class)->calculate($this);
    }
}
