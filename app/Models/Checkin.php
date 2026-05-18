<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    public $timestamps = false;

    protected $fillable = ['habit_id', 'checked_date', 'checked_at', 'note'];

    protected function casts(): array
    {
        return [
            'checked_date' => 'date',
            'checked_at'   => 'datetime',
        ];
    }

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}
