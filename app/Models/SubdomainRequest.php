<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubdomainRequest extends Model
{
    use HasFactory;

    protected $fillable = ['subdomain', 'status', 'user_id'];
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}