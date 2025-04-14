<x-app-layout>
    <div class="min-h-screen flex bg-gray-100">
        @include('partials.sidebar')

        <div class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-6">Edit Kategori</h2>

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('kategoris.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $kategori->id }}">

                <div class="mb-4">
                    <label for="nama" class="block text-sm font-semibold text-gray-700">Nama Kategori</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $kategori->nama) }}" class="w-full border border-gray-300 rounded px-4 py-2 mt-2" required>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Perbarui</button>
            </form>
        </div>
    </div>
</x-app-layout>
