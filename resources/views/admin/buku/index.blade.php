<x-app-layout>
    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Wrapper -->
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
        
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Toggle Button Mobile -->
        <div class="md:hidden fixed top-4 left-4 z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="bg-gray-800 text-white p-2 rounded">☰</button>
        </div>

        <!-- Main Content -->
        <div class="w-full pt-5 px-5">
            
            <!-- Action Buttons -->
            <div class="inline-flex items-center gap-2 mb-4">
                <!-- Tambah Buku Button -->
                <a href="{{ route('admin.bukus.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Buku
                </a>

                <!-- Download Template -->
                <a href="/storage/data-import.xlsx"
                   onclick="alert('Template sedang diunduh...')"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-600 to-yellow-500 hover:from-yellow-700 hover:to-yellow-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                    📁 Base template
                </a>

                <!-- Import Excel Button -->
                <a href="#" onclick="document.getElementById('importExcel').click()"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-700 hover:to-yellow-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                    📁 Import Excel
                </a>
            </div>

            <!-- Form Import Excel (Hidden) -->
            <form id="excelImportForm" action="{{ route('admin.bukus.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" name="file" id="importExcel" accept=".xlsx, .xls" onchange="document.getElementById('excelImportForm').submit();">
            </form>

           
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
                }, 3000); 
            </script>
            @endif

            <!-- Table Section -->
            <div class="bg-white p-5 rounded shadow overflow-x-auto">
                <table id="bukuTable" class="min-w-full divide-y divide-gray-300 text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Judul</th>
                            <th class="px-4 py-2 border">Gambar</th>
                            <th class="px-4 py-2 border">Penulis</th>
                            <th class="px-4 py-2 border">Tahun Terbit</th>
                            <th class="px-4 py-2 border">Stok</th>
                            <th class="px-4 py-2 border">Kategori</th>
                            <th class="px-4 py-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTable will populate data -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script> 
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script> 

    <!-- DataTables Init -->
    <script>
        $(document).ready(function() {
            $('#bukuTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.buku.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'judul', name: 'judul' },
                    { data: 'gambar', name: 'gambar', orderable: false, searchable: false },
                    { data: 'penulis', name: 'penulis' },
                    { data: 'tahun_terbit', name: 'tahun_terbit' },
                    { data: 'stok', name: 'stok' },
                    { data: 'kategori', name: 'kategori' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                //pageLength: 4,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        className: 'px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700',
                        title: 'Data Buku',
                        exportOptions: { columns: [0, 1, 3, 4, 5, 6] }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export PDF',
                        className: 'px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700',
                        title: 'Data Buku',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: { columns: [0, 1, 3, 4, 5, 6] },
                        customize: function (doc) {
                            doc.styles.tableHeader = {
                                fillColor: '#4B5563',
                                color: 'white',
                                alignment: 'center',
                                bold: true,
                                fontSize: 10
                            };
                            doc.styles.title = {
                                alignment: 'center',
                                fontSize: 14,
                                bold: true
                            };
                            doc.content[1].layout = {
                                hLineWidth: function () { return 0.5; },
                                vLineWidth: function () { return 0.5; },
                                hLineColor: function () { return '#aaa'; },
                                vLineColor: function () { return '#aaa'; },
                                paddingLeft: function () { return 8; },
                                paddingRight: function () { return 8; },
                                paddingTop: function () { return 4; },
                                paddingBottom: function () { return 4; }
                            };
                            const columnCount = doc.content[1].table.body[0].length;
                            doc.content[1].table.widths = Array(columnCount).fill('*');
                        }
                    }
                ],
                drawCallback: function () {
                    $('.dataTables_wrapper').addClass('p-5');
                    $('.dataTables_filter input').addClass('border border-gray-300 rounded-lg px-4 py-2 w-64 focus:outline-none focus:ring focus:border-blue-300');
                    $('.dataTables_length select').addClass('border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-300');
                    $('.dataTables_paginate').addClass('flex items-center space-x-2');
                    $('.dataTables_paginate span a').addClass('px-3 py-1 border rounded hover:bg-blue-100');
                    $('.dataTables_paginate span .current').addClass('bg-blue-500 text-white px-3 py-1 rounded');
                }
            });
        });
    </script>
</x-app-layout>
