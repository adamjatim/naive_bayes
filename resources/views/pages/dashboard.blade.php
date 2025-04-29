@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'karyawan')
        <div class="w-full mx-auto py-6 px-4 space-y-10">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-800">Dashboard Admin</h2>
            </div>

            <div class="flex fle-row gap-10 justify-between">
                {{-- HISTORY ADMIN --}}
                <div class="flex flex-col w-full h-60 bg-white p-6 rounded-2xl border">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Riwayat Aktivitas Admin</h3>
                    <ul class="h-fit overflow-y-scroll text-sm text-gray-700 space-y-2">
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
                <div class="w-full bg-white p-6 rounded-2xl border">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Export Laporan</h3>

                    <form action="{{ route('export.report') }}" method="GET" class="flex items-center space-x-4">
                        <select name="percentage" class="border-gray-300 rounded shadow-sm px-3 py-2">
                            <option value="" disabled selected>-- Pilih Persentase Data Testing --</option>
                            @for ($i = 10; $i <= 90; $i += 10)
                                <option value="{{ $i }}">{{ $i }}%</option>
                            @endfor
                        </select>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Export Excel
                        </button>
                    </form>

                </div>
            </div>

            {{-- STATISTIK --}}
            <div class="w-full flex flex-row gap-6 px-6 py-8 h-fit">
                {{-- BAGIAN KIRI --}}
                <div class="w-3/4 flex flex-col gap-6 justify-between">
                    <div class="flex flex-row">
                        {{-- Penerima per Kategori --}}
                        <div class="bg-white p-6 rounded-xl shadow w-[550px] h-[330px] mx-auto">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Penerima per Kategori</h3>
                            <canvas id="chartKategoriPenerima"></canvas>
                        </div>

                        {{-- Bukan Penerima per Kategori --}}
                        <div class="bg-white p-6 rounded-xl shadow w-[550px] h-[330px] mx-auto">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Bukan Penerima per Kategori</h3>
                            <canvas id="chartKategoriBukan"></canvas>
                        </div>
                    </div>

                    <div class="flex flex-row">
                        {{-- Penerima per RW --}}
                        <div class="bg-white p-6 rounded-xl shadow w-[550px] h-[330px] mx-auto">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Penerima per RW</h3>
                            <canvas id="chartRWPenerima"></canvas>
                        </div>

                        {{-- Bukan Penerima per RW --}}
                        <div class="bg-white p-6 rounded-xl shadow w-[550px] h-[330px] mx-auto">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Bukan Penerima per RW</h3>
                            <canvas id="chartRWBukan"></canvas>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN KANAN --}}
                <div class="w-1/4 flex flex-col gap-6">
                    {{-- Perbandingan Penerima vs Bukan --}}
                    <div class="bg-white p-6 rounded-xl shadow">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Perbandingan Penerima vs Bukan</h3>
                        <canvas id="chartPerbandingan" class="!w-[248px] !h-auto mx-auto"></canvas>
                    </div>

                    {{-- Jumlah Data Berdasarkan File --}}
                    <div class="bg-white p-6 rounded-xl shadow h-[330px]">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Jumlah Data Berdasarkan File</h3>
                        <canvas id="chartPerFile" class="!w-full !h-[260px]"></canvas>
                    </div>
                </div>
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
        // Chart: Penerima per Kategori
        new Chart(document.getElementById('chartKategoriPenerima'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($kriteriaStats->toArray())) !!},
                datasets: [{
                    label: 'Penerima',
                    data: {!! json_encode(array_values($kriteriaStats->toArray())) !!},
                    backgroundColor: '#3B82F6'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart: Bukan Penerima per Kategori
        new Chart(document.getElementById('chartKategoriBukan'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($kriteriaStatsBukan->toArray())) !!},
                datasets: [{
                    label: 'Bukan Penerima',
                    data: {!! json_encode(array_values($kriteriaStatsBukan->toArray())) !!},
                    backgroundColor: '#F87171'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart: Penerima per RW
        new Chart(document.getElementById('chartRWPenerima'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($rwStats->toArray())) !!},
                datasets: [{
                    label: 'Penerima',
                    data: {!! json_encode(array_values($rwStats->toArray())) !!},
                    backgroundColor: '#10B981'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart: Bukan Penerima per RW
        new Chart(document.getElementById('chartRWBukan'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($rwStatsBukan->toArray())) !!},
                datasets: [{
                    label: 'Bukan Penerima',
                    data: {!! json_encode(array_values($rwStatsBukan->toArray())) !!},
                    backgroundColor: '#F59E0B'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart: Perbandingan Penerima vs Bukan
        new Chart(document.getElementById('chartPerbandingan'), {
            type: 'doughnut',
            data: {
                labels: ['Penerima', 'Bukan Penerima'],
                datasets: [{
                    data: [{{ $jumlahPenerima }}, {{ $jumlahBukan }}],
                    backgroundColor: ['#6366F1', '#F59E0B']
                }]
            },
            options: {
                responsive: true
            }
        });

        new Chart(document.getElementById('chartPerFile'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(
                    array_map(function ($label) {
                        $string = $label;
                        $pieces = explode(' ', $string);
                        $last_word = array_pop($pieces);
                        return strlen($label) > 10 ? $last_word : $label;
                    }, array_keys($fileStats->toArray())),
                ) !!},
                datasets: [{
                    label: 'Jumlah Data',
                    data: {!! json_encode(array_values($fileStats->toArray())) !!},
                    backgroundColor: '#EF4444'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            const fullLabels = {!! json_encode(array_keys($fileStats->toArray())) !!};
                            const index = tooltipItems[0].dataIndex;
                            return fullLabels[index];
                        }
                    }
                }
            }
        });
    </script>

@endsection
