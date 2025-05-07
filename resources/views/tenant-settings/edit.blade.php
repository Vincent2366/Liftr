@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Tenant Settings</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $tenant->id }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('tenant.settings.update', $tenant->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Tenant Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $tenant->name) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="logo" name="logo">
                                <label class="custom-file-label" for="logo">Choose file</label>
                            </div>
                            @if($tenant->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="theme">Theme</label>
                            <select class="form-control" id="theme" name="theme">
                                @foreach($themes as $key => $theme)
                                    <option value="{{ $key }}" {{ old('theme', $tenant->theme) == $key ? 'selected' : '' }}>
                                        {{ $theme['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="custom-colors" class="mt-3" style="{{ old('theme', $tenant->theme) === 'custom' ? '' : 'display: none;' }}">
                            <h5>Custom Colors</h5>
                            
                            <div class="form-group">
                                <label for="primary_color">Primary Color</label>
                                <input type="color" class="form-control" id="primary_color" name="primary_color" value="{{ old('primary_color', $tenant->primary_color) }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="secondary_color">Secondary Color</label>
                                <input type="color" class="form-control" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $tenant->secondary_color) }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="accent_color">Accent Color</label>
                                <input type="color" class="form-control" id="accent_color" name="accent_color" value="{{ old('accent_color', $tenant->accent_color) }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Theme Preview</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($themes as $key => $theme)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header" style="background-color: {{ $theme['primary'] }}; color: white;">
                                {{ $theme['name'] }}
                            </div>
                            <div class="card-body">
                                <div class="mb-2" style="height: 30px; background-color: {{ $theme['secondary'] }}; border-radius: 4px;"></div>
                                <div style="height: 30px; background-color: {{ $theme['accent'] }}; border-radius: 4px;"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeSelect = document.getElementById('theme');
        const customColors = document.getElementById('custom-colors');
        
        themeSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customColors.style.display = 'block';
            } else {
                customColors.style.display = 'none';
            }
        });
        
        // Initialize file input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush
@endsection