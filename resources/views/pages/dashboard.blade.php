@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    @if (Auth::user()->role == 'admin')
        <div class="w-full mx-auto py-6 px-4 space-y-10">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-800">Dashboard Admin</h2>
            </div>

            <div class="flex fle-row gap-10 justify-between">
                {{-- HISTORY ADMIN --}}
                <div class="w-full bg-white p-6 rounded-2xl shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Riwayat Aktivitas Admin</h3>
                    <ul class="text-sm text-gray-700 space-y-2">
                        @forelse ($logs as $log)
                            <li class="border-b pb-2">
                                <span class="text-gray-500">[{{ $log->created_at }}]</span>
                                <strong>{{ $log->user->name }}</strong> melakukan <span
                                    class="font-medium">{{ $log->action }}</span>
                                @if ($log->file_name)
                                    pada file: <em>{{ $log->file_name }}</em>
                                @endif
                            </li>
                        @empty
                            <li class="text-gray-400 italic">Tidak ada aktivitas ditemukan.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- EXPORT PDF --}}
                <div class="w-full bg-white p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Export Laporan</h3>
                    <form action="{{ route('export.report') }}" method="GET" class="flex items-center space-x-4">
                        <select name="file_name" class="border-gray-300 rounded shadow-sm px-3 py-2">
                            <option value="">-- Pilih Nama File --</option>
                            @foreach ($availableFiles as $file)
                                <option value="{{ $file }}">{{ $file }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Export PDF
                        </button>
                    </form>
                </div>
            </div>

            {{-- STATISTIK --}}
            <div class="space-y-6">
                <h2 class="text-2xl font-semibold text-gray-800">Statistik Penerima Bantuan</h2>

                <div class="flex overflow-x-scroll no-scrollbar gap-6" id="chartContainer">
                    @php
                        $charts = [
                            ['id' => 'chartKategori', 'title' => 'Penerima per Kategori'],
                            ['id' => 'chartRW', 'title' => 'Penerima per RW'],
                            ['id' => 'chartPerbandingan', 'title' => 'Perbandingan Penerima vs Bukan'],
                            ['id' => 'chartPerFile', 'title' => 'Jumlah Data Berdasarkan File'],
                        ];
                    @endphp

                    @foreach ($charts as $chart)
                        <div class="min-w-[350px] w-[40%] bg-white p-6 rounded-xl shadow transform transition-all duration-300 cursor-pointer chart-card" onclick="focusChart(this)">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ $chart['title'] }}</h3>
                            <canvas id="{{ $chart['id'] }}"></canvas>
                        </div>
                    @endforeach
                </div>

                {{-- <div class="flex justify-center space-x-4 mt-4">
                    <button onclick="scrollCharts(-1)" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">⬅</button>
                    <button onclick="scrollCharts(1)" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">➡</button>
                </div> --}}
            </div>

        </div>
    @elseif (Auth::user()->role == 'karyawan')
        <div class="max-w-4xl mx-auto py-6 px-4">
            <div class="bg-white p-6 rounded-xl shadow">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Dashboard Karyawan</h1>
                <p class="text-gray-600 mb-4">Selamat datang, Karyawan! Berikut adalah tugas-tugas Anda.</p>
                <ul class="list-disc pl-6 text-gray-700 space-y-1">
                    <li>Cek data warga</li>
                    <li>Input data penerima bantuan</li>
                    <li>Cetak laporan</li>
                </ul>
            </div>
        </div>
    @else
        <div class="unauthorized p-6 text-center">
            <h1 class="text-2xl font-bold text-red-600">Akses Ditolak</h1>
            <p class="text-gray-600">Anda tidak memiliki akses ke halaman ini.</p>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = {
            chartKategori: {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($kriteriaStats->toArray())) !!},
                    datasets: [{
                        label: 'Jumlah Penerima',
                        data: {!! json_encode(array_values($kriteriaStats->toArray())) !!},
                        backgroundColor: '#3B82F6'
                    }]
                }
            },
            chartRW: {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($rwStats->toArray())) !!},
                    datasets: [{
                        label: 'Jumlah Penerima',
                        data: {!! json_encode(array_values($rwStats->toArray())) !!},
                        backgroundColor: '#10B981'
                    }]
                }
            },
            chartPerbandingan: {
                type: 'doughnut',
                data: {
                    labels: ['Penerima', 'Bukan Penerima'],
                    datasets: [{
                        data: [{{ $jumlahPenerima }}, {{ $jumlahBukan }}],
                        backgroundColor: ['#6366F1', '#F59E0B']
                    }]
                }
            },
            chartPerFile: {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($fileStats->toArray())) !!},
                    datasets: [{
                        label: 'Total Data',
                        data: {!! json_encode(array_values($fileStats->toArray())) !!},
                        backgroundColor: '#EF4444'
                    }]
                }
            }
        };

        Object.entries(chartData).forEach(([id, config]) => {
            new Chart(document.getElementById(id), config);
        });

        // function focusChart(card) {
        //     document.querySelectorAll('.chart-card').forEach(el => {
        //         el.classList.remove('scale-105', 'z-20');
        //         el.classList.add('opacity-60');
        //     });
        //     card.classList.remove('opacity-60');
        //     card.classList.add('scale-105', 'z-20');
        // }

        function scrollCharts(direction) {
            const container = document.getElementById('chartContainer');
            container.scrollBy({ left: 400 * direction, behavior: 'smooth' });
        }
    </script>
@endsection
