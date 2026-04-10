{{--
    Komponen Alert / Flash Message
    Gunakan di controller: return redirect()->with('success', 'Pesan berhasil.');
    TANGGUNG JAWAB: Digunakan oleh semua developer
--}}
@if (session('success'))
    <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-800 px-4 py-3 flex items-center gap-2">
        <span>✅</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error') || $errors->any())
    <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-800 px-4 py-3">
        <span>❌</span>
        @if (session('error'))
            <span>{{ session('error') }}</span>
        @endif
        @if ($errors->any())
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
