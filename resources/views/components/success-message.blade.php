@props(['message' => null, 'id' => null, 'class' => '', 'autoHide' => false, 'timeout' => 3000])

<div 
    {{ $id ? "id=$id" : '' }} 
    {{ $attributes->merge(['class' => 'success-message ' . ($message ? '' : 'hidden') . ' ' . $class]) }}
    data-auto-hide="{{ $autoHide }}"
    data-timeout="{{ $timeout }}"
>
    <div class="flex justify-between items-center">
        <div>
            @if($message)
                <span>{{ $message }}</span>
            @else
                {{ $slot }}
            @endif
        </div>
        <button type="button" class="close-message" onclick="this.parentElement.parentElement.classList.add('hidden')">
            &times;
        </button>
    </div>
</div>

@if($autoHide)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('{{ $id }}');
    if (element) {
        setTimeout(() => {
            element.classList.add('hidden');
        }, {{ $timeout }});
    }
});
</script>
@endif
