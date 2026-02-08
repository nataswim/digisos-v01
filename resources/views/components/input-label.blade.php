@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-gray-700 mb-2']) }}>
    <i class="fas fa-tag text-primary me-1" style="font-size: 0.75rem; opacity: 0.6;"></i>
    {{ $value ?? $slot }}
</label>