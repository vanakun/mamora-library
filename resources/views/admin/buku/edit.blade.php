<x-app-layout>
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
        @include('partials.sidebar')

        <div class="w-full pt-[20px] px-[20px]">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Edit Buku</h2>

            <div id="alert-box" class="hidden mb-4 p-4 rounded text-white bg-green-500"></div>

            <!-- Form to update the book -->
            <form method="POST" action="{{ route('bukus.update', $buku->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <!-- Kolom Kiri: Judul, Penulis, Tahun Terbit, Stok -->
                    <div class="space-y-6">
                        <div>
                            <label for="judul" class="block font-medium text-gray-700">Judul</label>
                            <input type="text" name="judul" id="judul" value="{{ old('judul', $buku->judul) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 @error('judul') border-red-500 @enderror">
                            @error('judul')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="penulis" class="block font-medium text-gray-700">Penulis</label>
                            <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $buku->penulis) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 @error('penulis') border-red-500 @enderror">
                            @error('penulis')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tahun_terbit" class="block font-medium text-gray-700">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 @error('tahun_terbit') border-red-500 @enderror">
                            @error('tahun_terbit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stok" class="block font-medium text-gray-700">Stok</label>
                            <input type="number" name="stok" id="stok" value="{{ old('stok', $buku->stok) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 @error('stok') border-red-500 @enderror">
                            @error('stok')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan: Kategori dan Gambar -->
                    <div class="space-y-6">
                        <div>
                            <label for="kategori_id" class="block font-medium text-gray-700">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 @error('kategori_id') border-red-500 @enderror">
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $kategori->id == $buku->kategori_id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <!-- Gambar Buku -->
                            <label for="gambar" class="block font-medium text-gray-700">Gambar Buku (opsional)</label>
                            <input type="file" name="gambar" id="gambar" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 @error('gambar') border-red-500 @enderror">
                            @error('gambar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            @if ($buku->gambar)
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-600">Gambar Saat Ini:</label>
                                    <img src="{{ asset('storage/gambar_buku/' . $buku->gambar) }}" alt="Gambar Buku" class="w-32 h-40 object-cover rounded-lg shadow-lg mt-2">
                                    <button type="button" class="text-red-500 text-sm mt-2 hover:underline" onclick="confirmDelete()">Hapus Gambar</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-200">
                        Update Buku
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm("Apakah Anda yakin ingin menghapus gambar ini?")) {
                // Hapus gambar, tambahkan logika sesuai dengan kebutuhan
                alert("Gambar berhasil dihapus!");
            }
        }
    </script>
</x-app-layout>
