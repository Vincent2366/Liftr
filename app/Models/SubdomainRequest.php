<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubdomainRequest extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'subdomain',
        'status',
        'user_id',
        'email', // Add email to fillable attributes
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


