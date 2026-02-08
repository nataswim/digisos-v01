<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-secondary inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide hover:bg-gray-200 hover:border-gray-400 transition-all duration-200']) }}>
    {{ $slot }}
</button>