<div class="mb-4">

    <label class="block mb-2 font-semibold">
        Nama Kategori
    </label>

    <input
        type="text"
        name="name"
        value="{{ old('name', $category->name ?? '') }}"
        class="w-full border rounded-lg px-4 py-2">

    @error('name')

        <p class="text-red-600 text-sm mt-1">
            {{ $message }}
        </p>

    @enderror

</div>