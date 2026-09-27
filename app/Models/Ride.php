<?php

namespace App\Models;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ride extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'passenger_id',
        'driver_id',
        'pickup_location',
        'dropoff_location',
        'requested_at',
        'pickup_at',
        'completed_at',
        'status',
        'fare',
        'payment_method',
        'payment_status',
        'paid_at',
        'is_priority',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requested_at' => 'datetime',
        'pickup_at'    => 'datetime',
        'completed_at' => 'datetime',
        'paid_at'      => 'datetime',
        'fare'         => 'decimal:2',
        'is_priority'  => 'boolean',
    ];

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'pending'     => 'warning',
            'assigned'    => 'info',
            'accepted'    => 'primary',
            'in_progress' => 'secondary',
            'completed'   => 'success',
            'cancelled'   => 'danger',
            default       => 'light',
        };
    }

    public function getPaymentBadgeClass(): string
    {
        return match ($this->payment_status) {
            'paid'   => 'success',
            'waived' => 'secondary',
            default  => 'warning',
        };
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(Schedule::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
