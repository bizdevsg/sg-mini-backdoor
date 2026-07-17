<?php

use Illuminate\Support\Arr;

if (! function_exists('cn')) {
    /**
     * Build CSS class strings from strings and conditional arrays.
     *
     * @param  mixed  ...$classes
     */
    function cn(...$classes): string
    {
        $normalized = [];

        $append = function (mixed $value) use (&$normalized, &$append): void {
            if (is_string($value) && $value !== '') {
                $normalized[] = $value;

                return;
            }

            if (! is_array($value)) {
                return;
            }

            foreach ($value as $key => $item) {
                if (is_int($key)) {
                    $append($item);

                    continue;
                }

                $normalized[$key] = $item;
            }
        };

        foreach ($classes as $class) {
            $append($class);
        }

        return Arr::toCssClasses($normalized);
    }
}
