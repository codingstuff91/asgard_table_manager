@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-800 dark:bg-gray-800 dark:text-white focus:border-indigo-800 dark:focus:border-indigo-900 dark:focus:text-white focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>
