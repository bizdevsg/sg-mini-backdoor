@php
    $tag = $as ?? 'td';
    $cellClass = cn(
        'px-6 py-4',
        ($muted ?? true) ? 'text-sm text-smoke' : '',
        $class ?? null,
    );
@endphp

@if ($tag === 'th')
    <th class="{{ $cellClass }}">
        {{ $text }}
    </th>
@else
    <td class="{{ $cellClass }}">
        {{ $text }}
    </td>
@endif
