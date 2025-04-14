<x-app-layout>
    <div class="min-h-screen flex bg-gray-100">
        @include('partials.sidebar')

        <div class="w-full p-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">📚 Tambah Buku</h2>

                <!-- Flash message -->
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('bukus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Buku</label>
                        <input type="text" name="judul" id="judul" required
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @error('judul')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penulis -->
                    <div>
                        <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                        <input type="text" name="penulis" id="penulis" required
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @error('penulis')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Terbit -->
                    <div>
                        <label for="tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" id="tahun_terbit" required
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @error('tahun_terbit')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stok" id="stok" required
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @error('stok')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="kategori_id" id="kategori_id" required
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gambar Buku -->
                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700">Upload Gambar Buku</label>
                        <input type="file" name="gambar" id="gambar" accept="image/*"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm file:bg-blue-600 file:text-white file:font-semibold file:px-4 file:py-2 file:rounded file:border-0 file:cursor-pointer">
                        @error('gambar')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-4">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                            💾 Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
