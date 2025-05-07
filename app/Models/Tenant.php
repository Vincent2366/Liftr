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
        'name',
        'logo',
        'theme',
        'primary_color',
        'secondary_color',
        'accent_color',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // Theme constants
    const THEME_DEFAULT = 'default';
    const THEME_GREEN = 'green';
    const THEME_PURPLE = 'purple';
    const THEME_RED = 'red';
    const THEME_ORANGE = 'orange';
    const THEME_TEAL = 'teal';
    const THEME_DARK = 'dark';
    const THEME_CUSTOM = 'custom';

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

    /**
     * Get available themes with their colors
     */
    public static function getThemes()
    {
        return [
            self::THEME_DEFAULT => [
                'name' => 'Default Blue',
                'primary' => '#4e73df',
                'secondary' => '#858796',
                'accent' => '#36b9cc'
            ],
            self::THEME_GREEN => [
                'name' => 'Forest Green',
                'primary' => '#1e88e5',
                'secondary' => '#2e7d32',
                'accent' => '#43a047'
            ],
            self::THEME_PURPLE => [
                'name' => 'Lavender Purple',
                'primary' => '#673ab7',
                'secondary' => '#9c27b0',
                'accent' => '#7e57c2'
            ],
            self::THEME_RED => [
                'name' => 'Ruby Red',
                'primary' => '#d32f2f',
                'secondary' => '#f44336',
                'accent' => '#ef5350'
            ],
            self::THEME_ORANGE => [
                'name' => 'Sunset Orange',
                'primary' => '#ff5722',
                'secondary' => '#ff9800',
                'accent' => '#ff7043'
            ],
            self::THEME_TEAL => [
                'name' => 'Emerald Green',
                'primary' => '#00897b',
                'secondary' => '#009688',
                'accent' => '#26a69a'
            ],
            self::THEME_DARK => [
                'name' => 'Midnight Blue',
                'primary' => '#283593',
                'secondary' => '#3949ab',
                'accent' => '#3f51b5'
            ],
            self::THEME_CUSTOM => [
                'name' => 'Custom Colors',
                'primary' => null,
                'secondary' => null,
                'accent' => null
            ],
        ];
    }

    /**
     * Check if a specific feature is available for the current plan
     * 
     * @param string $feature The feature to check
     * @return bool Whether the feature is available
     */
    public function hasFeature($feature)
    {
        $features = [
            'unlimited_users' => [self::PLAN_PREMIUM, self::PLAN_ULTIMATE],
            'generate_reports' => [self::PLAN_ULTIMATE],
            'custom_theme' => [self::PLAN_PREMIUM, self::PLAN_ULTIMATE],
            // Add more features as needed
        ];
        
        if (!isset($features[$feature])) {
            return false;
        }
        
        return in_array($this->plan, $features[$feature]);
    }
    
    /**
     * Get the maximum number of users allowed for the current plan
     * 
     * @return int|null The maximum number of users (null for unlimited)
     */
    public function getUserLimit()
    {
        switch ($this->plan) {
            case self::PLAN_FREE:
                return 3;
            case self::PLAN_PREMIUM:
            case self::PLAN_ULTIMATE:
                return null; // unlimited
            default:
                return 3; // default to free plan limit
        }
    }
}



