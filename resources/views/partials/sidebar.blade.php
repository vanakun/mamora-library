<div x-data="{ sidebarOpen: false }" :class="sidebarOpen ? 'block' : 'hidden md:block'" class="bg-gray-200 text-black w-64 p-6 md:relative fixed md:static z-20 h-screen">
    <ul class="space-y-4">
        <li>
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 hover:bg-gray-100 rounded"
               :class="{ 'bg-blue-600 text-white': isActive('dashboard') }"
               @click="setActive('dashboard')">
               🏠 Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('kategoris.index') }}" 
               class="block px-4 py-2 hover:bg-gray-100 rounded"
               :class="{ 'bg-blue-600 text-white': isActive('kategori_buku') }"
               @click="setActive('kategori_buku')">
               📚 Kategori
            </a>
        </li>
        <li>
            <a href="{{ route('admin.buku.index') }}" 
               class="block px-4 py-2 hover:bg-gray-100 rounded"
               :class="{ 'bg-blue-600 text-white': isActive('kelola_buku') }"
               @click="setActive('kelola_buku')">
               📚 Master Buku
            </a>
        </li>
        <li>
            <a href="{{ route('peminjamans.index') }}" 
               class="block px-4 py-2 hover:bg-gray-100 rounded"
               :class="{ 'bg-blue-600 text-white': isActive('peminjaman') }"
               @click="setActive('peminjaman')">
               📖 Peminjaman
            </a>
        </li>
        <li>
            <a href="{{ route('users.data') }}" 
               class="block px-4 py-2 hover:bg-gray-100 rounded"
               :class="{ 'bg-blue-600 text-white': isActive('pengguna') }"
               @click="setActive('pengguna')">
               👤 Pengguna
            </a>
        </li>
    </ul>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sidebar', () => ({
            sidebarOpen: false,
            active: '',
            setActive(item) {
                this.active = item;
            },
            isActive(item) {
                return this.active === item;
            }
        }));
    });
</script>
