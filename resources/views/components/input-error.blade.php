@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-2 text-sm text-danger']) }}>
        @foreach ((array) $messages as $message)
            <li><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</li>
        @endforeach
    </ul>
@endif