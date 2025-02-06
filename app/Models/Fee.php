<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'total_amount',
        'payment_amount',
        'remaining_amount',
        'first_payment_date',
        'final_payment_date',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function student() : BelongsTo {
        return $this->belongsTo(User::class, 'student_id')
        ->withDefault([
            'name' => 'no students'
        ]);
    }
}
