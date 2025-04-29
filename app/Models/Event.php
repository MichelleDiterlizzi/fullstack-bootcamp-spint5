<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'address', 'event_date', 'price', 'is_free', 'description', 'image', 'creator_id', 'category_id'];

    protected $casts = [
        'event_date' => 'datetime',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category(){
    return $this->belongsTo(Category::class);
    }

    public function attendees(): BelongsToMany{
        return $this->belongsToMany(User::class, 'event_users', 'event_id', 'user_id')
                    ->withPivot('guests_count') 
                    ->withTimestamps();         
    }

    public function creator(): BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function getTotalPeopleAttribute(): int{
        if (! $this->relationLoaded('attendees')) {
            $this->load('attendees');
        }
        $total = 0;
        foreach ($this->attendees as $attendee) {
            $total += 1 + ($attendee->pivot->guests_count ?? 0);
        }
        return $total;
    }

    

}