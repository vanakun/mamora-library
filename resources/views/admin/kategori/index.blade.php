<x-app-layout>
    <div class="min-h-screen flex bg-gray-100">
        @include('partials.sidebar')

        <div class="flex-1 p-6">
         

            <div id="alert-box" class="hidden mb-4 p-4 rounded text-white text-sm"></div>

            <form id="kategori-form" class="mb-6 flex gap-4 items-center">
                @csrf
                <input type="hidden" name="id" id="kategori-id">
                <input type="text" name="nama" id="nama" placeholder="Nama kategori" class="flex-1 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Simpan</button>
            </form>

            <div class="bg-white shadow rounded p-4">
                <table id="kategori-table" class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase">
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail Kategori -->
    <div id="kategori-detail-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center transition-opacity duration-300 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 scale-90 opacity-0 transition-all duration-300 transform" id="modal-content">
            <h3 class="text-xl font-bold mb-4">Detail Kategori</h3>
            <div><strong>Nama: </strong><span id="detail-nama"></span></div>
            <div class="mt-4">
                <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        let table;

        $(document).ready(function() {
            table = $('#kategori-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kategoris.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });

            $('#kategori-form').submit(function(e) {
                e.preventDefault();

                const id = $('#kategori-id').val();
                const method = id ? 'PUT' : 'POST';
                const ajaxUrl = id ? `/kategoris/${id}` : "{{ route('kategoris.store') }}";
                const formData = $(this).serialize();

                $.ajax({
                    url: ajaxUrl,
                    type: method,
                    data: formData,
                    success: function(res) {
                        showAlert('success', res.message);
                        $('#kategori-form')[0].reset();
                        $('#kategori-id').val('');
                        table.ajax.reload();
                    },
                    error: function(err) {
                        showAlert('error', 'Terjadi kesalahan saat menyimpan.');
                        console.error(err);
                    }
                });
            });
        });

        function editKategori(id) {
            window.location.href = `/admin/kategoris/${id}/edit`;  // Redirect to the edit page
        }

        function deleteKategori(id) {
            if (confirm('Yakin ingin menghapus?')) {
                $.ajax({
                    url: `/admin/kategoris/${id}`,
                    type: 'DELETE',
                    success: function(res) {
                        showAlert('success', res.message);
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        showAlert('error', 'Gagal menghapus kategori.');
                        console.error(error);
                    }
                });
            }
        }

        function showDetail(id) {
            $.get(`/kategoris/${id}`, function(data) {
                $('#detail-nama').text(data.nama);
                const modal = $('#kategori-detail-modal');
                const content = $('#modal-content');
                const sidebar = $('.sidebar'); // Ensure this is the correct selector for your sidebar

                // Debugging: Check if sidebar is selected correctly
                console.log(sidebar);

                modal.removeClass('hidden');
                sidebar.addClass('sidebar-blur');  // Apply blur effect to sidebar

                setTimeout(() => {
                    content.removeClass('opacity-0 scale-90');
                }, 10);
            }).fail(function() {
                alert('Gagal mengambil detail kategori.');
            });
        }

        function closeModal() {
            const content = $('#modal-content');
            const modal = $('#kategori-detail-modal');
            const sidebar = $('.sidebar'); // Ensure this is the correct selector for your sidebar

            // Debugging: Check if sidebar is selected correctly
            console.log(sidebar);

            content.addClass('opacity-0 scale-90');
            sidebar.removeClass('sidebar-blur');  // Remove blur effect from sidebar

            setTimeout(() => {
                modal.addClass('hidden');
            }, 300);
        }

        function showAlert(type, message) {
            let box = $('#alert-box');
            box.removeClass('hidden bg-green-500 bg-red-500');
            box.addClass(type === 'success' ? 'bg-green-500' : 'bg-red-500');
            box.text(message).fadeIn().delay(2000).fadeOut();
        }
    </script>

    <!-- CSS for Sidebar Blur Effect -->
    <style>
        /* CSS for blurring the sidebar */
        .sidebar-blur {
            filter: blur(5px);
            pointer-events: none;  /* Disable interactions with the sidebar while blurred */
        }

        /* Ensure modal has higher z-index than sidebar */
        .modal {
            z-index: 9999 !important;
        }
    </style>
</x-app-layout>
