<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 px-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow" role="alert">
                        <strong>Sukses!</strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow" role="alert">
                        <strong>Gagal!</strong> {{ session('error') }}
                    </div>
                </div>
            @endif

            {{-- Tabel Buku --}}
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4 overflow-x-auto">
                    <table id="bukuTable" class="min-w-full table-auto border border-gray-200 divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Judul</th>
                                <th class="px-4 py-3 text-left">Penulis</th>
                                <th class="px-4 py-3 text-left">Tahun Terbit</th>
                                <th class="px-4 py-3 text-left">Stok</th>
                                <th class="px-4 py-3 text-left">Kategori</th>
                                <th class="px-4 py-3 text-left">Gambar</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Baris data akan diisi oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Script DataTables --}}
    <script>
        $(function () {
            $('#bukuTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('getBukuList') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'judul', name: 'judul' },
                    { data: 'penulis', name: 'penulis' },
                    { data: 'tahun_terbit', name: 'tahun_terbit' },
                    { data: 'stok', name: 'stok' },
                    { data: 'kategori', name: 'kategori.nama' },
                    { data: 'gambar', name: 'gambar', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });

        // Auto-dismiss alert
        setTimeout(() => {
            document.querySelectorAll('[role="alert"]').forEach(alert => alert.remove());
        }, 4000);
    </script>
</x-app-layout>
