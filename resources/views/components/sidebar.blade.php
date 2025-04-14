<!-- Tambahkan ini di layout utama (jika belum) -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Sidebar dan toggle -->
<div x-data="{ open: false }" class="flex">
    <!-- Sidebar -->
    <div :class="open ? 'block' : 'hidden md:block'" class="bg-gray-800 text-white w-64 min-h-screen px-4 py-6 fixed md:relative z-20">
        
        <ul class="space-y-4">
            <li>
                <a href= class="block px-4 py-2 hover:bg-gray-700 rounded">📚 Kelola Buku</a>
            </li>
            <li>
                <a href="" class="block px-4 py-2 hover:bg-gray-700 rounded">👤  engguna</a>
            </li>
            <li>
                <a href="" class="block px-4 py-2 hover:bg-gray-700 rounded">📖 Peminjaman</a>
            </li>
            <li>
                <a href="" class="block px-4 py-2 hover:bg-gray-700 rounded">🏠 Kembali ke Dashboard</a>
            </li>
        </ul>
    </div>

    <!-- Toggle Button (Mobile) -->
    <div class="md:hidden fixed top-4 left-4 z-30">
        <button @click="open = !open" class="bg-gray-800 text-white p-2 rounded">
            ☰
        </button>
    </div>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 p-6">
        <!-- Konten halaman di sini -->
        @yield('content')
    </div>
</div>
