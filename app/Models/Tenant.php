<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    
    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_FROZEN = 'frozen';
    
    // Add status to the list of fillable attributes
    protected $fillable = [
        'id', 'status'
    ];
}
