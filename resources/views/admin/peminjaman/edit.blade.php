<x-app-layout>
    <script src="//unpkg.com/alpinejs" defer></script>

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'block' : 'hidden md:block'" class="bg-gray-200 text-black w-64 p-6 md:relative fixed md:static z-20 h-screen">
            <ul class="space-y-4">
                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">🏠 Dashboard</a></li>
                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">📚 Kelola Buku</a></li>
                <li><a href="{{ route('peminjamans.index') }}" class="block px-4 py-2 hover:bg-gray-100 rounded font-semibold text-blue-600">📖 Peminjaman</a></li>
                <li><a href="/users/data" class="block px-4 py-2 hover:bg-gray-100 rounded">👤 Pengguna</a></li>
            </ul>
        </div>

        <!-- Toggle Button Mobile -->
        <div class="md:hidden fixed top-4 left-4 z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="bg-gray-800 text-white p-2 rounded">☰</button>
        </div>

        <!-- Main Content -->
        <div class="w-full pt-[20px] pl-[20px] pr-[20px]">
            <h1 class="text-2xl font-bold mb-6">Edit Peminjaman</h1>

            <div class="bg-white p-6 rounded shadow">
                <form action="{{ route('peminjamans.update', $peminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                  
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Nama Peminjam</label>
                        <input type="text" value="{{ $peminjaman->user->name }}" readonly
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-gray-700 cursor-not-allowed" />
                        <input type="hidden" name="user_id" value="{{ $peminjaman->user_id }}" />
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Judul Buku</label>
                        <input type="text" value="{{ $peminjaman->buku->judul }}" readonly
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-gray-700 cursor-not-allowed" />
                        <input type="hidden" name="buku_id" value="{{ $peminjaman->buku_id }}" />
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_pinjam" class="block font-medium text-gray-700">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ $peminjaman->tanggal_pinjam }}"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" />
                        @error('tanggal_pinjam') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="tanggal_kembali" class="block font-medium text-gray-700">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" value="{{ $peminjaman->tanggal_kembali }}"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" />
                        @error('tanggal_kembali') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-gray-700">Denda Saat Ini</label>
                        <input type="text" value="Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}" readonly
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-gray-700 cursor-not-allowed" />
                    </div>

                    
                    <div class="flex justify-between">
                        <a href="{{ route('peminjamans.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
   
</x-app-layout>
