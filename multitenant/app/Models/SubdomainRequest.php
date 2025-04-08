<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubdomainRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subdomain',
        'email',
        'description',
        'status',
        'tenant_id',
    ];

    /**
     * Get the tenant associated with the subdomain request.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
