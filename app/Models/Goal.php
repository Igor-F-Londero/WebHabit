<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id', 'habit_id', 'title', 'description',
        'target_count', 'start_date', 'end_date', 'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function checkinCount(): int
    {
        return $this->habit->checkins()
            ->whereBetween('checked_date', [$this->start_date, $this->end_date])
            ->count();
    }

    public function progressPercent(): float
    {
        if ($this->target_count <= 0) {
            return 0;
        }

        return min(100, round(($this->checkinCount() / $this->target_count) * 100, 1));
    }

    public function daysRemaining(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->end_date, false));
    }

    public function syncStatus(): void
    {
        if ($this->status === 'completed') {
            return;
        }

        if ($this->progressPercent() >= 100) {
            $this->update(['status' => 'completed']);
        } elseif ($this->end_date->isPast()) {
            $this->update(['status' => 'expired']);
        }
    }
}
