@props([
    'label',
    'name' => 'foto_bukti',
    'optional' => true,
    'required' => false,
    'uploadId' => 'sigap-foto-bukti',
])

<div class="mb-5">
    <label class="mb-1.5 block text-sm font-semibold text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @elseif($optional)
            <span class="font-normal text-gray-500">(opsional)</span>
        @endif
    </label>
    <label class="relative flex min-h-[160px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-xl bg-gray-200 px-4 py-6 transition hover:bg-gray-300/80">
        <input type="file" name="{{ $name }}" id="{{ $uploadId }}-input" accept="image/jpeg,image/png,image/jpg" class="hidden" data-sigap-upload="{{ $uploadId }}" @if($required) required @endif />
        <div id="{{ $uploadId }}-placeholder" class="flex flex-col items-center text-center text-sm text-gray-600">
            <span class="mb-1 font-medium text-gray-700">Ketuk untuk memilih foto</span>
            <span class="text-xs text-gray-500">JPG, PNG (maks. 10MB)</span>
        </div>
        <div id="{{ $uploadId }}-preview-wrap" class="absolute inset-0 hidden items-center justify-center bg-gray-100 p-2">
            <img id="{{ $uploadId }}-preview" src="" alt="Pratinjau" class="max-h-full max-w-full rounded-lg object-contain" />
        </div>
    </label>
    @error($name)
        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('change', function (e) {
            var input = e.target;
            if (!input.matches || !input.matches('input[data-sigap-upload]')) return;
            var uid = input.getAttribute('data-sigap-upload');
            var wrap = document.getElementById(uid + '-preview-wrap');
            var img = document.getElementById(uid + '-preview');
            var ph = document.getElementById(uid + '-placeholder');
            if (!wrap || !img || !ph) return;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (ev) {
                    img.src = ev.target.result;
                    wrap.classList.remove('hidden');
                    wrap.classList.add('flex');
                    ph.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
    @endpush
@endonce
