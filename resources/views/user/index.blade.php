<x-app-layout>
    <div class="min-h-screen flex bg-gray-100 justify-center py-8">
        <!-- Main Content -->
        <div class="flex-1 max-w-screen-lg px-4 sm:px-6 lg:px-8">
            <!-- Notifikasi -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4 shadow-md">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4 shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabel Peminjaman -->
            <div class="mb-8 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-center text-gray-800">Peminjaman Saya</h2>
                <div class="overflow-x-auto">
                    <table id="peminjamanTable" class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2 text-left">Kode Pinjam</th>
                                <th class="px-4 py-2 text-left">Buku</th>
                                <th class="px-4 py-2 text-left">Tanggal Pinjam</th>
                                <th class="px-4 py-2 text-left">Tanggal Kembali</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Buku yang Tersedia -->
            <div class="bg-gray-50 py-6 px-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-center text-gray-800">Buku yang Tersedia</h2>
                <div class="text-end mt-6 mb-4">
                <a href="{{ route('user.ShowMore') }}" class="text-blue-600 font-semibold hover:underline">
                    Lihat Semua Buku
                </a>

                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                    @foreach($bukus as $buku)
                        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="flex justify-center mb-4">
                                @if($buku->gambar)
                                    <img src="{{ asset('storage/gambar_buku/' . $buku->gambar) }}" alt="Gambar Buku" class="rounded-lg w-full h-64 object-contain shadow-md">
                                @else
                                    <img src="{{ asset('images/default-book.jpg') }}" alt="Gambar Buku" class="rounded-lg w-full h-64 object-contain shadow-md">
                                @endif
                            </div>
                            <h3 class="text-lg font-medium text-center text-gray-800 mb-4">{{ $buku->judul }}</h3>

                            <div class="mt-6 space-y-4">
                                <!-- Pinjam Buku Button -->
                                <form method="POST" action="{{ route('peminjaman.pinjam', $buku->id) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105">
                                        Pinjam Buku
                                    </button>
                                </form>

                                <!-- Detail Button -->
                                <button onclick="showDetailModal({{ $buku->id }}, '{{ $buku->judul }}', '{{ $buku->penulis }}', '{{ $buku->tahun_terbit }}', '{{ $buku->stok }}', '{{ $buku->deskripsi }}')" class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-6">
                {{ $bukus->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Detail Buku -->
    <div id="detailModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Detail Buku</h2>
            <div class="mb-4 text-gray-700">
                <p><strong>Judul:</strong> <span id="modalJudul"></span></p>
                <p><strong>Penulis:</strong> <span id="modalPenulis"></span></p>
                <p><strong>Tahun Terbit:</strong> <span id="modalTahunTerbit"></span></p>
                <p><strong>Stok:</strong> <span id="modalStok"></span></p>
                <p><strong>Deskripsi:</strong> <span id="modalDeskripsi">Deskripsi tidak tersedia</span></p>
            </div>
            <div class="mt-4">
                <button onclick="closeModal()" class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-all duration-300 transform hover:scale-105">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#peminjamanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('peminjaman.data') }}',
                columns: [
                    { data: 'kode_pinjam', name: 'kode_pinjam' },
                    { data: 'buku.judul', name: 'buku.judul' },
                    { data: 'tanggal_pinjam', name: 'tanggal_pinjam' },
                    { data: 'tanggal_kembali', name: 'tanggal_kembali' },
                    { data: 'status', name: 'status' }
                ]
            });
        });

        function showDetailModal(id, judul, penulis, tahun_terbit, stok, deskripsi) {
            document.getElementById('modalJudul').textContent = judul;
            document.getElementById('modalPenulis').textContent = penulis;
            document.getElementById('modalTahunTerbit').textContent = tahun_terbit;
            document.getElementById('modalStok').textContent = stok;
            document.getElementById('modalDeskripsi').textContent = deskripsi || 'Deskripsi tidak tersedia';

            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
