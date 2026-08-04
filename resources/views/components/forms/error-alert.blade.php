@if ($errors->any())
    <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50/90 px-4 py-3 text-sm text-red-800">
        <i class="fa-solid fa-triangle-exclamation pt-0.5 text-base text-red-600"></i>
        <div>
            <p class="font-semibold text-red-800">Terdapat kesalahan pengisian:</p>
            <p class="text-xs text-red-700">{{ $errors->first() }}</p>
        </div>
    </div>
@endif
