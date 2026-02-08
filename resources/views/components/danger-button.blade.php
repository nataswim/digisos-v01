<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-danger inline-flex items-center gap-2 px-5 py-2.5 bg-danger border-0 rounded-lg font-semibold text-sm text-white uppercase tracking-wide shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>