<x-app-layout>
    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Toggle Button Mobile -->
        <div class="md:hidden fixed top-4 left-4 z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="bg-gray-800 text-white p-2 rounded">☰</button>
        </div>

        <!-- Main Content -->
        <div class="w-full pt-[20px] pl-[20px] pr-[20px]">
            <!-- Tombol Tambah Pengguna -->
            <!-- <a href=""
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pengguna
            </a> -->

            <!-- Tabel Pengguna -->
            <div class="mt-4 bg-white p-5 rounded shadow overflow-x-auto">
                <table id="userTable" class="min-w-full divide-y divide-gray-300 text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Nama</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Dibuat</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan dimuat melalui DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CDN jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Inisialisasi DataTable dengan Tailwind Style -->
    <script>
        $(document).ready(function () {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at' },
                   
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 4,
                dom: '<"flex justify-between items-center mb-4"l<"flex-1 text-right"f>>t<"flex justify-between items-center mt-4"ip>',
                drawCallback: function () {
                    $('.dataTables_wrapper').addClass('p-5');

                    $('.dataTables_filter input')
                        .addClass('border border-gray-300 rounded-lg px-4 py-2 w-64 focus:outline-none focus:ring focus:border-blue-300')
                        .attr('placeholder', 'Cari pengguna...');
                    $('.dataTables_length').hide();
                    $('.dataTables_paginate').addClass('flex items-center space-x-2');
                    $('.dataTables_paginate span a').addClass('px-3 py-1 border rounded hover:bg-blue-100');
                    $('.dataTables_paginate span .current').addClass('bg-blue-500 text-white px-3 py-1 rounded');
                    $('.dataTables_info').addClass('text-sm text-gray-600');
                }
            });
        });
    </script>
</x-app-layout>
