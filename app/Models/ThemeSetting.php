<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme',
        'primary_color',
        'secondary_color',
        'accent_color',
        'logo_path',
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
                'name' => 'Emerald Teal',
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
     * Get the current theme settings or create default if none exists
     */
    public static function current()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'theme' => self::THEME_DEFAULT,
            ]);
        }
        
        return $settings;
    }
}