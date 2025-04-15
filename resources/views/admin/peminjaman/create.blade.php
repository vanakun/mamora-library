<x-app-layout>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Flash Message -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Toggle Button Mobile -->
        <div class="md:hidden fixed top-4 left-4 z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="bg-gray-800 text-white p-2 rounded">☰</button>
        </div>

        <!-- Main Content -->
        <div class="w-full pt-[20px] pl-[20px] pr-[20px]">
            <div class="bg-white p-6 rounded shadow">
                <form action="{{ route('peminjamans.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="user_id" class="block font-medium text-gray-700">Nama Peminjam</label>
                        <select name="user_id" id="user_id" class="js-select2 mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">-- Pilih User --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="buku_id" class="block font-medium text-gray-700">Judul Buku</label>
                        <select name="buku_id" id="buku_id" class="js-select2 mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($bukus as $buku)
                                <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>{{ $buku->judul }}</option>
                            @endforeach
                        </select>
                        @error('buku_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_pinjam" class="block font-medium text-gray-700">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" />
                        @error('tanggal_pinjam') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="tanggal_kembali" class="block font-medium text-gray-700">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" value="{{ old('tanggal_kembali') }}"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" />
                        @error('tanggal_kembali') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('peminjamans.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Inisialisasi Select2 -->
    <script>
        $(document).ready(function () {
            $('.js-select2').select2({
                width: '100%',
                placeholder: "-- Pilih --",
                allowClear: true
            });
        });
    </script>
</x-app-layout>
