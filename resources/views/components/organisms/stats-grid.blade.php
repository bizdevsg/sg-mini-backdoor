<div class="{{ cn('grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3') }}">
    @foreach ($stats as $stat)
        @include('components.molecules.stat-card', [
            'icon' => $stat['icon'],
            'title' => $stat['title'],
            'value' => $stat['value'],
            'description' => $stat['description'],
            'highlight' => $stat['highlight'] ?? false,
        ])
    @endforeach
</div>
