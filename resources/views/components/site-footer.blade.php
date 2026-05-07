<footer {{ $attributes->merge(['class' => 'print:hidden mt-auto shrink-0 w-full border-t border-gray-200 bg-gradient-to-b from-slate-50 to-white text-gray-600']) }}>
    <div class="max-w-md mx-auto px-4 py-3 flex flex-col items-center gap-2">
        <div class="flex items-center gap-2 text-xs text-gray-700 font-bold">
            <span>Business Corner</span>
            <span class="text-gray-300">·</span>
            <span>Bazar PCR</span>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-400">
            <a href="https://www.instagram.com/bisniscorner.pcr/"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-gradient-to-r from-pink-500/80 via-rose-500/90 to-orange-400/80 text-white font-semibold shadow hover:opacity-90 transition"
               aria-label="Instagram bisniscorner.pcr">
                <span aria-hidden="true">📷</span>
                <span>@bisniscorner.pcr</span>
            </a>
        </div>
        <div class="text-[10px] text-gray-300 mt-1">&copy; {{ date('Y') }} {{ config('app.name', 'Bazar PCR') }}</div>
    </div>
</footer>
