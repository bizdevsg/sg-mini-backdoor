<nav class="hidden lg:block">
    <div class="sticky top-25 space-y-1 text-sm">
        <ul>
            <li>
                <h5 class="font-bold text-lg text-gold">Menu Settings</h5>
            </li>
            @foreach ($sections as $navSection)
                <li>
                    <a href="#{{ $navSection['key'] }}"
                        class="block rounded-lg px-3 py-2 text-smoke/70 transition-colors hover:bg-white/5 hover:text-white">
                        {{ $navSection['title'] }}
                    </a>
                </li>
            @endforeach
            <li>
                <a href="#api-security"
                    class="block rounded-lg px-3 py-2 text-smoke/70 transition-colors hover:bg-white/5 hover:text-white">
                    API &amp; Security
                </a>
            </li>
            <li>
                <a href="#api-access"
                    class="block rounded-lg px-3 py-2 text-smoke/70 transition-colors hover:bg-white/5 hover:text-white">
                    API Access
                </a>
            </li>
        </ul>
    </div>
</nav>
