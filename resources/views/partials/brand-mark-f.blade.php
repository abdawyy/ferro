{{-- Default FERRO iron "F" mark (shown when custom logo upload is disabled) --}}
@php
    $markClass = $class ?? 'w-7 h-7 sm:w-8 sm:h-8 shrink-0 text-ferro-orange';
@endphp
<svg class="{{ $markClass }}"
     viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z" fill="currentColor"/>
</svg>
