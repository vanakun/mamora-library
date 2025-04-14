<x-app-layout>
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <div class="flex-1 p-6 bg-gray-100">
            {{-- Kotak ucapan selamat pagi --}}
            @php
                $hour = \Carbon\Carbon::now()->format('H');
                if ($hour >= 5 && $hour < 11) {
                    $greeting = 'Selamat pagi';
                } elseif ($hour >= 11 && $hour < 15) {
                    $greeting = 'Selamat siang';
                } elseif ($hour >= 15 && $hour < 18) {
                    $greeting = 'Selamat sore';
                } else {
                    $greeting = 'Selamat malam';
                }
            @endphp

            <div class="max-w-7xl mx-auto mb-6 bg-blue-100 border border-blue-300 text-blue-800 rounded-2xl shadow-md p-4">
                <p class="text-lg font-semibold">{{ $greeting }}, {{ Auth::user()->name }} 👋</p>
            </div>



            {{-- Kotak statistik kecil --}}
            <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-2xl shadow-md p-4 flex items-center space-x-4">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H2v-2a4 4 0 014-4h1m5-6a4 4 0 110-8 4 4 0 010 8zM7 10a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah User</p>
                        <p class="text-xl font-bold text-gray-800">{{ $jumlahUser }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-4 flex items-center space-x-4">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 20h9M12 4v16M6 20h.01M6 4v16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Buku</p>
                        <p class="text-xl font-bold text-gray-800">{{ $jumlahBuku }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-4 flex items-center space-x-4">
                    <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 16h.01M12 16h.01M16 16h.01M9 20h6a2 2 0 002-2V6a2 2 0 00-2-2H9a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Peminjaman yang selesai</p>
                        <p class="text-xl font-bold text-gray-800">{{ $jumlahPeminjaman }}</p>
                    </div>
                </div>
            </div>

            {{-- Kotak grafik --}}
            <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-md p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md relative">
                        <p class="absolute top-4 left-4 text-lg font-semibold text-gray-800">Grafik Peminjaman per Bulan</p>
                        <canvas id="peminjamanBulanChart" class="w-full max-h-[250px] max-w-[400px] mt-[40px]"></canvas>
                        <div id="peminjamanBulanChartMessage" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gray-600 font-semibold">Tidak ada data</div>
                    </div>

                    <div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md relative">
                        <p class="absolute top-4 left-4 text-lg font-semibold text-gray-800">Grafik Peminjaman per Buku</p>
                        <canvas id="peminjamanBukuChart" class="w-full max-h-[250px] max-w-[400px] mt-[40px]"></canvas>
                        <div id="peminjamanBukuChartMessage" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gray-600 font-semibold">Tidak ada data</div>
                    </div>

                    <div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md relative">
                        <p class="absolute top-4 left-4 text-lg font-semibold text-gray-800">Grafik Buku per Kategori</p>
                        <canvas id="bukuKategoriChart" class="w-full max-h-[250px] max-w-[400px] mt-[40px]"></canvas>
                        <div id="bukuKategoriChartMessage" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gray-600 font-semibold">Tidak ada data</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let peminjamanBulanChart, bukuKategoriChart, peminjamanBukuChart;

        function loadPeminjamanBulanChart(labels, values) {
            const ctx = document.getElementById('peminjamanBulanChart').getContext('2d');
            if (labels.length === 0 || values.length === 0) {
                document.getElementById('peminjamanBulanChartMessage').style.display = 'block';
            } else {
                document.getElementById('peminjamanBulanChartMessage').style.display = 'none';
                peminjamanBulanChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Peminjaman per Bulan',
                            data: values,
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgba(37, 99, 235, 1)',
                            borderWidth: 2,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#374151',
                                    font: { size: 14, weight: 'bold' }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#f9fafb',
                                titleColor: '#111827',
                                bodyColor: '#111827',
                                borderColor: '#d1d5db',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: '#4b5563' },
                                grid: { color: '#e5e7eb' }
                            },
                            x: {
                                ticks: { color: '#4b5563' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        }

        function loadBukuKategoriChart(labels, values) {
            const ctx = document.getElementById('bukuKategoriChart').getContext('2d');
            if (labels.length === 0 || values.length === 0) {
                document.getElementById('bukuKategoriChartMessage').style.display = 'block';
            } else {
                document.getElementById('bukuKategoriChartMessage').style.display = 'none';
                bukuKategoriChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Buku per Kategori',
                            data: values,
                            backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#9333EA', '#F472B6'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#374151',
                                    font: { size: 14, weight: 'bold' }
                                }
                            }
                        }
                    }
                });
            }
        }

        function loadPeminjamanBukuChart(labels, values) {
            const ctx = document.getElementById('peminjamanBukuChart').getContext('2d');
            if (labels.length === 0 || values.length === 0) {
                document.getElementById('peminjamanBukuChartMessage').style.display = 'block';
            } else {
                document.getElementById('peminjamanBukuChartMessage').style.display = 'none';
                peminjamanBukuChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Peminjaman per Buku',
                            data: values,
                            backgroundColor: 'rgba(34, 211, 238, 0.6)',
                            borderColor: 'rgba(6, 182, 212, 1)',
                            borderWidth: 2,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#374151',
                                    font: { size: 14, weight: 'bold' }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#f9fafb',
                                titleColor: '#111827',
                                bodyColor: '#111827',
                                borderColor: '#d1d5db',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: '#4b5563' },
                                grid: { color: '#e5e7eb' }
                            },
                            x: {
                                ticks: { color: '#4b5563' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        }

        fetch('/dashboard/chart-data')
            .then(res => res.json())
            .then(data => {
                loadPeminjamanBulanChart(data.labels, data.values);
                loadBukuKategoriChart(data.kategoriLabels, data.bukuValues);
                loadPeminjamanBukuChart(data.bukuLabels, data.peminjamanValues);
            });
    </script>
</x-app-layout>
