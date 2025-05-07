@php
    $themeSettings = \App\Models\ThemeSetting::current();
    $theme = $themeSettings->theme;
    $primaryColor = $themeSettings->primary_color;
    $secondaryColor = $themeSettings->secondary_color;
    $accentColor = $themeSettings->accent_color;
    
    // If using a predefined theme, get the colors
    if ($theme !== 'custom' && !$primaryColor) {
        $themes = \App\Models\ThemeSetting::getThemes();
        $primaryColor = $themes[$theme]['primary'] ?? '#4e73df';
        $secondaryColor = $themes[$theme]['secondary'] ?? '#858796';
        $accentColor = $themes[$theme]['accent'] ?? '#36b9cc';
    }
    
    // Convert hex to RGB for rgba() usage
    function hexToRgb($hex) {
        $hex = str_replace('#', '', $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "$r, $g, $b";
    }
    
    $primaryRgb = hexToRgb($primaryColor);
    $secondaryRgb = hexToRgb($secondaryColor);
    $accentRgb = hexToRgb($accentColor);
@endphp

<style>
    :root {
        --primary-color: {{ $primaryColor }};
        --primary-rgb: {{ $primaryRgb }};
        --secondary-color: {{ $secondaryColor }};
        --secondary-rgb: {{ $secondaryRgb }};
        --accent-color: {{ $accentColor }};
        --accent-rgb: {{ $accentRgb }};
        --light-bg: #f8f9fc;
        --card-bg: #ffffff;
    }
    
    /* Sidebar styling */
    .bg-gradient-primary {
        background-color: var(--primary-color);
        background-image: linear-gradient(180deg, var(--primary-color) 10%, {{ $secondaryColor }} 100%);
        background-size: cover;
    }
    
    /* Button styling */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: {{ $secondaryColor }};
        border-color: {{ $secondaryColor }};
    }
    
    .btn-success {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }
    
    /* Text colors */
    .text-primary {
        color: var(--primary-color) !important;
    }
    
    /* Card styling */
    .card .card-header {
        background-color: var(--light-bg);
        border-bottom: 1px solid rgba(var(--primary-rgb), 0.1);
    }
    
    .card .border-left-primary {
        border-left: 0.25rem solid var(--primary-color) !important;
    }
    
    .card .border-left-success {
        border-left: 0.25rem solid var(--accent-color) !important;
    }
    
    .card .border-left-warning {
        border-left: 0.25rem solid var(--secondary-color) !important;
    }
    
    /* Content background */
    #content-wrapper {
        background-color: var(--light-bg);
    }
    
    /* Form controls */
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.25);
    }
    
    /* Badges */
    .badge-primary {
        background-color: var(--primary-color);
    }
    
    .badge-secondary {
        background-color: var(--secondary-color);
    }
    
    .badge-success {
        background-color: var(--accent-color);
    }
    
    /* Dropdown menus */
    .dropdown-item.active, .dropdown-item:active {
        background-color: var(--primary-color);
    }
    
    /* Pagination */
    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .page-link {
        color: var(--primary-color);
    }
    
    .page-link:hover {
        color: var(--secondary-color);
    }
    
    /* Progress bars */
    .progress-bar {
        background-color: var(--primary-color);
    }
    
    /* Alerts */
    .alert-primary {
        background-color: rgba(var(--primary-rgb), 0.2);
        border-color: rgba(var(--primary-rgb), 0.3);
        color: var(--primary-color);
    }
    
    .alert-success {
        background-color: rgba(var(--accent-rgb), 0.2);
        border-color: rgba(var(--accent-rgb), 0.3);
        color: var(--accent-color);
    }
    
    /* Tables */
    .table .thead-primary th {
        background-color: var(--primary-color);
        color: #fff;
    }
    
    /* Custom styling for specific elements */
    .topbar .dropdown-list .dropdown-header {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .sidebar .nav-item .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar .nav-item.active .nav-link {
        font-weight: 700;
    }
    
    /* Logo display if available */
    @if(isset($themeSettings->logo_path) && $themeSettings->logo_path)
    .sidebar-brand-icon img {
        max-height: 36px;
    }
    @endif

    /* Additional styling for settings pages */
    .settings-card .card-header {
        background-color: var(--primary-color);
        color: white;
    }
    
    .settings-section {
        border-left: 4px solid var(--primary-color);
        padding-left: 15px;
        margin-bottom: 20px;
    }
    
    /* Ensure all cards have consistent styling */
    .card {
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card .card-header {
        background-color: var(--light-bg);
        border-bottom: 1px solid rgba(var(--primary-rgb), 0.1);
    }
    
    /* Ensure all buttons have consistent styling */
    .btn {
        border-radius: 0.35rem;
    }
    
    /* Ensure all form controls have consistent styling */
    .form-control {
        border-radius: 0.35rem;
    }
    
    /* Ensure all tabs have consistent styling */
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        border-color: #e3e6f0 #e3e6f0 #fff;
    }
    
    .nav-tabs .nav-link:hover {
        color: var(--secondary-color);
    }
    
    /* Login and Registration page styling */
    .bg-login-image, .bg-register-image, .bg-password-image {
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .login-logo, .register-logo, .password-logo {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
    }
    
    .btn-user {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    .btn-user:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        color: white;
    }
    
    /* Links */
    a {
        color: var(--primary-color);
    }
    
    a:hover {
        color: var(--secondary-color);
    }
    
    /* Form control user */
    .form-control-user {
        border-radius: 10rem;
        padding: 1.5rem 1rem;
    }
    
    .form-control-user:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.25);
    }
</style>

@if(isset($themeSettings->logo_path) && $themeSettings->logo_path)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Replace the default icon with the custom logo
        const brandIcons = document.querySelectorAll('.sidebar-brand-icon');
        brandIcons.forEach(function(icon) {
            icon.innerHTML = '<img src="{{ asset('storage/' . $themeSettings->logo_path) }}" alt="Logo">';
        });
        
        // Also update login/register page images if they exist
        const loginImages = document.querySelectorAll('.bg-login-image, .bg-register-image, .bg-password-image');
        if (loginImages.length > 0 && !document.querySelector('.login-logo, .register-logo, .password-logo')) {
            loginImages.forEach(function(image) {
                image.innerHTML = '<img src="{{ asset('storage/' . $themeSettings->logo_path) }}" alt="Logo" class="login-logo">';
            });
        }
    });
</script>
@endif





