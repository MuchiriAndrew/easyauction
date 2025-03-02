<div>
    <input
        type="text"
        {{ $attributes->merge(['class' => 'form-input filament-forms-input block w-full rounded-lg shadow-sm outline-none transition duration-75 focus:ring-1 focus:ring-inset disabled:opacity-70 border-gray-300 focus:border-primary-500 focus:ring-primary-500']) }}
        x-data
        x-init="
            $el.addEventListener('input', function() {
                let value = $el.value.replace(/,/g, '');
                $el.value = new Intl.NumberFormat().format(value);
            });
        "
    />
</div>