<x-app-layout>
    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />

    <!-- Wrapper -->
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Toggle Button Mobile -->
        <div class="md:hidden fixed top-4 left-4 z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="bg-gray-800 text-white p-2 rounded">☰</button>
        </div>

        <!-- Main Content -->
        <div class="w-full pt-[20px] pl-[20px] pr-[20px]">
            
        @if(session('success'))
            <div id="success-alert" class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    const alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.style.display = 'none';
                    }
                }, 3000); // 3000 milidetik = 3 detik
            </script>
        @endif


        <a href="{{ route('peminjamans.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Peminjaman
            </a>

            <!-- Modal Detail Buku -->
            <div 
                id="buku-modal" 
                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden"
            >
                <div class="bg-white rounded-lg shadow-xl w-96 p-6 relative">
                    <h2 class="text-lg font-bold mb-4">Detail Buku</h2>
                    <p><strong>Judul:</strong> <span id="modal-judul"></span></p>
                    <p><strong>Penulis:</strong> <span id="modal-penulis"></span></p>
                    <p><strong>Tahun Terbit:</strong> <span id="modal-tahun"></span></p>
                   
                    <button id="close-modal" class="absolute top-2 right-2 text-gray-600 hover:text-red-500">&times;</button>
                </div>
            </div>
            <div class="mt-4 bg-white p-5 rounded shadow overflow-x-auto">
                <table id="peminjamans-table" class="min-w-full divide-y divide-gray-300 text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Kode Pinjam</th>
                            <th class="px-4 py-2 border">Nama User</th>
                            <th class="px-4 py-2 border">Judul Buku</th>
                            <th class="px-4 py-2 border">Tanggal Pinjam</th>
                            <th class="px-4 py-2 border">Tanggal Kembali</th>
                            <th class="px-4 py-2 border">Tanggal Dikembalikan</th>
                            <th class="px-4 py-2 border">Denda</th>
                            <th class="px-4 py-2 border text-center w-32">Status</th>
                            <th class="px-4 py-2 border text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- JQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(function () {
            // Sisipkan filter status 'dom'
            const statusFilter = `
                <div class="mb-4 flex justify-start items-center">
                    <label for="filter-status" class="mr-2 font-semibold text-gray-700">Filter Status:</label>
                    <select id="filter-status" class="border rounded p-2 text-sm">
                        <option value="">Semua</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="dikembalikan">Dikembalikan</option>
                        <option value="menunggu_admin">menunggu_admin</option>
                    </select>
                </div>
            `;

            // Inisialisasi DataTables
            var table = $('#peminjamans-table').DataTable({
                dom: '<"dataTables_top flex justify-between items-center"<"dataTables_status_wrapper">f>t<"dataTables_bottom flex justify-between items-center"ip>',
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("peminjamans.data") }}',
                    data: function (d) {
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'kode_pinjam', name: 'kode_pinjam' },
                    { data: 'user', name: 'user.name' },
                    { data: 'buku', name: 'buku.judul' },
                    { data: 'tanggal_pinjam', name: 'tanggal_pinjam' },
                    { data: 'tanggal_kembali', name: 'tanggal_kembali' },
                    { data: 'tanggal_kembali_final', name: 'tanggal_kembali_final' },
                    { data: 'denda', name: 'denda' },
                   
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 4,
                drawCallback: function () {
                    // Tambahkan filter status ke dalam wrapper yang telah ditentukan
                    if (!$('.dataTables_status_wrapper').has('#filter-status').length) {
                        $('.dataTables_status_wrapper').html(statusFilter);
                        $('#filter-status').on('change', function () {
                            table.ajax.reload();
                        });
                    }

                    $('.dataTables_wrapper').addClass('p-5');
                    $('.dataTables_filter').addClass('mb-5');
                    $('.dataTables_filter input').addClass('border rounded px-3 py-1 ml-2 focus:outline-none focus:ring focus:border-blue-300');
                    $('.dataTables_paginate').addClass('mt-4 flex items-center justify-end space-x-2');
                    $('.dataTables_paginate span a').addClass('px-3 py-1 border rounded hover:bg-blue-100');
                    $('.dataTables_paginate span .current').addClass('bg-blue-500 text-white px-3 py-1 rounded');
                    $('.dataTables_length').addClass('mb-5');
                    $('.dataTables_length label').addClass('text-sm font-medium text-gray-700');
                    $('.dataTables_length select').addClass('border rounded px-2 py-1 ml-2 focus:outline-none focus:ring focus:border-blue-300');
                    $('.dataTables_info').addClass('mt-4 text-sm text-gray-600');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.lihat-buku-btn', function () {
            $('#modal-judul').text($(this).data('judul'));
            $('#modal-penulis').text($(this).data('penulis'));
            $('#modal-tahun').text($(this).data('tahun'));
            $('#buku-modal').removeClass('hidden');
        });

        $('#close-modal').on('click', function () {
            $('#buku-modal').addClass('hidden');
        });

        // Tutup modal saat klik di luar kontennya
        $('#buku-modal').on('click', function (e) {
            if (e.target === this) {
                $(this).addClass('hidden');
            }
        });
    </script>
</x-app-layout>
