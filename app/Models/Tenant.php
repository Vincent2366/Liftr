<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Support\Facades\Log;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_FROZEN = 'frozen';
    
    // Plan constants
    const PLAN_FREE = 'free';
    const PLAN_PREMIUM = 'premium';
    const PLAN_ULTIMATE = 'ultimate';
    
    protected $fillable = [
        'id',
        'email',
        'status',
        'password',
        'data',
        'plan',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get the plan attribute
     */
    public function getPlanAttribute()
    {
        // First check if there's a dedicated column
        if (array_key_exists('plan', $this->attributes)) {
            return $this->attributes['plan'];
        }
        
        // Then check if it's in the data array
        if (isset($this->data['plan'])) {
            return $this->data['plan'];
        }
        
        // Default to free plan
        return self::PLAN_FREE;
    }
}




