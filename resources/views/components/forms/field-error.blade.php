@props([
    'field',
    'class' => 'mt-1.5 text-xs font-medium text-red-700',
])

@error($field)
    <p {{ $attributes->merge(['class' => $class]) }}>{{ $message }}</p>
@enderror
