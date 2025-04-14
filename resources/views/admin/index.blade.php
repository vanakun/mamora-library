<x-app-layout>
    <script src="//unpkg.com/alpinejs" defer></script>

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'block' : 'hidden md:block'" 
             class="bg-gray-300 text-black w-64 p-6 md:relative fixed z-20">
            <ul class="space-y-4">
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">🏠 Dashboard</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">📚 Kelola Buku</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">📖 Peminjaman</a>
                </li>
                <li>
                    <a href="/users/data" class="block px-4 py-2 hover:bg-gray-100 rounded">👤 Pengguna</a>
                </li>
            </ul>
        </div>

        <!-- Toggle Sidebar Button (Mobile Only) -->
        <div class="md:hidden fixed top-4 left-4 z-30">
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="bg-gray-800 text-white p-2 rounded">
                ☰
            </button>
        </div>

        <!-- Main Content -->
        <div class="flex-1 md:ml-64 p-6">
            <!-- Session Flash Message -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Daftar Buku -->
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($bukus as $buku)
                    <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold mb-2">{{ $buku->judul }}</h3>
                            <p class="text-gray-700"><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                            <p class="text-gray-700"><strong>Tahun:</strong> {{ $buku->tahun_terbit }}</p>
                            <p class="text-gray-700"><strong>Stok:</strong> {{ $buku->stok }}</p>
                        </div>

                        <div class="mt-4">
                            <form method="POST" action="{{ route('peminjaman.pinjam', $buku->id) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    Pinjam
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
