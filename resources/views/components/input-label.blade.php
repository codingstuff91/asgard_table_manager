@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-lg font-bold text-gray-800 dark:text-gray-400']) }}>
    {{ $value ?? $slot }}
</label>
