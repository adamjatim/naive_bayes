@extends('layout.app')

@section('title', 'Performance')

@section('styles')
    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

@endsection

@section('content')
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                Performance
            </h2>
        </div>
    </header>

    <main class="bg-gray-100 flex-grow ">
        <div class="mx-6 my-2">
            <div>Prosentase Data Training</div>
            <form action="{{ route('naive-bayes.performance.calculate') }}" method="POST">
                @csrf
                <label for="percentage" class="block mb-2 text-sm font-medium text-gray-700">Pilih Persentase Data
                    Training:</label>
                <select id="percentage" name="percentage" onchange="this.form.submit()"
                    class="w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Persentase --</option>
                    @for ($i = 10; $i <= 90; $i += 10)
                        <option value="{{ $i }}" {{ $percentage == $i ? 'selected' : '' }}>{{ $i }}%
                        </option>
                    @endfor
                </select>
            </form>
        </div>

        @if (!empty($warningMessage))
            <div id="warning-box"
                class="flex items-center p-4 m-4 mb-6 text-yellow-800 bg-yellow-100 border border-yellow-300 rounded-lg shadow animate-fade-in transition-all duration-500">
                <svg class="w-6 h-6 mr-2 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.675-1.36 3.44 0l6.518 11.592c.75 1.336-.213 3.009-1.72 3.009H3.46c-1.507 0-2.47-1.673-1.72-3.009L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-.25-5a.75.75 0 00-1.5 0v3a.75.75 0 001.5 0V9z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-sm">
                    <span class="font-semibold">Perhatian:</span> {{ $warningMessage }}
                </div>
            </div>
        @endif

        @if ($percentage)
            <!-- Tabel Data Training -->
            <div class="bg-white shadow rounded-lg overflow-x-auto m-4">
                <h3 class="text-xl font-semibold text-gray-700 my-4 mx-2">Data Training ({{ $trainCount }})</h3>

                <table class="min-w-full table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($trainData->first()->getAttributes() as $key => $val)
                                @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase text-left">
                                        {{ Str::headline($key) }}
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="lazy-train-table">
                        @foreach ($trainData->take(100) as $row)
                            <tr>
                                @foreach ($row->getAttributes() as $key => $val)
                                    @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                        <td
                                            class="px-4 py-2 text-sm text-gray-700 bg-blue-100 @if (strpos($val, 'RW') !== false) whitespace-nowrap @endif">
                                            {{ $val }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center py-4">
                    <button id="loadMoreTrainBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Load lebih banyak data training
                    </button>
                    <div id="spinnerTrain"
                        class="hidden mt-2 animate-spin w-6 h-6 border-2 border-blue-400 border-t-transparent rounded-full mx-auto">
                    </div>
                </div>
            </div>

            <!-- Tabel Data Testing -->
            <div class="bg-white shadow rounded-lg overflow-x-auto m-4">
                <h3 class="text-xl font-semibold text-gray-700 my-4 mx-2">Data Testing ({{ $testCount }})</h3>

                <table class="min-w-full table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($testData->first()->getAttributes() as $key => $val)
                                @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase text-left">
                                        {{ Str::headline($key) }}
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="lazy-test-table">
                        @foreach ($testData->take(100) as $row)
                            <tr>
                                @foreach ($row->getAttributes() as $key => $val)
                                    @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                        <td
                                            class="px-4 py-2 text-sm text-gray-700 bg-yellow-100 @if (strpos($val, 'RW') !== false) whitespace-nowrap @endif">
                                            {{ $val }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center py-4">
                    <button id="loadMoreBtn" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                        Load lebih banyak data testing
                    </button>
                    <div id="spinner"
                        class="hidden mt-2 animate-spin w-6 h-6 border-2 border-yellow-400 border-t-transparent rounded-full mx-auto">
                    </div>
                </div>
            </div>

            <!-- Tabel Proses Testing -->
            <div class="mt-10 m-4 bg-white p-4 shadow rounded-lg overflow-x-auto">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Tabel Proses Testing</h3>
                <table class="w-full table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($testData->first()->getAttributes() as $key => $val)
                                @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase text-left">
                                        {{ Str::headline($key) }}
                                    </th>
                                @endif
                            @endforeach
                            <th class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase text-left">Hasil Testing</th>
                        </tr>
                    </thead>
                    <tbody id="lazy-process-table">
                        @foreach (collect($predictions)->take(100) as $row)
                            <tr>
                                @foreach ($row['data']->getAttributes() as $key => $val)
                                    @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                        <td
                                            class="px-4 py-2 text-sm text-gray-700 @if (strpos($val, 'RW') !== false) whitespace-nowrap @endif">
                                            {{ $val }}</td>
                                    @endif
                                @endforeach
                                <td
                                    class="px-4 py-2 font-semibold {{ $row['correct'] ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $row['predicted'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center py-4">
                    <button id="loadMoreProcessBtn" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        Load lebih banyak hasil testing
                    </button>
                    <div id="spinnerProcess"
                        class="hidden mt-2 animate-spin w-6 h-6 border-2 border-purple-400 border-t-transparent rounded-full mx-auto">
                    </div>
                </div>
            </div>

            <!-- Hasil Kalkulasi -->
            <div class="mt-6 m-4 p-4 bg-red-500 border border-red-200 text-white rounded">
                <h3 class="font-semibold">Hasil Kalkulasi</h3>
                <p>Total Data: {{ $totalData }}</p>
                <p>Data Training: {{ $trainCount }}</p>
                <p>Data Testing: {{ $testCount }}</p>
                @if (!empty($predictions))
                    <p>Akurasi: <span class="font-semibold">{{ $accuracy }}%</span></p>
                @endif
            </div>
        @endif
    </main>
@endsection

@section('scripts')
    <script>
        let batch = 1;
        const perBatch = 100;
        let total = {{ $testCount }};
        let loading = false;

        document.getElementById('loadMoreBtn')?.addEventListener('click', async function() {
            if (loading) return;
            loading = true;
            this.disabled = true;
            document.getElementById('spinner').classList.remove('hidden');

            batch++;
            const res = await fetch(
                `{{ route('naive-bayes.performance.lazy.testing') }}?percentage={{ $percentage }}&batch=${batch}`
            );
            const data = await res.json();

            data.forEach(row => {
                const tr = document.createElement('tr');
                row.forEach(val => {
                    const td = document.createElement('td');
                    td.className = 'px-4 py-2 text-sm text-gray-700 bg-yellow-100';
                    if (typeof val === 'string' && val.includes('RW')) {
                        td.className.add = 'whitespace-nowrap';
                    }
                    td.textContent = val;
                    tr.appendChild(td);
                });
                document.getElementById('lazy-test-table').appendChild(tr);
            });

            document.getElementById('spinner').classList.add('hidden');
            this.disabled = false;
            loading = false;

            if ((batch * perBatch) >= total) {
                this.remove();
            }
        });
    </script>
    <script>
        let batchTrain = 1;
        const perBatchTrain = 100;
        const totalTrain = {{ $trainCount }};

        document.getElementById('loadMoreTrainBtn')?.addEventListener('click', async function() {
            if (loading) return;
            loading = true;
            this.disabled = true;
            document.getElementById('spinnerTrain').classList.remove('hidden');

            batchTrain++;
            const res = await fetch(
                `{{ route('naive-bayes.performance.lazy.training') }}?percentage={{ $percentage }}&batch=${batchTrain}`
            );
            const data = await res.json();

            data.forEach(row => {
                const tr = document.createElement('tr');
                row.forEach(val => {
                    const td = document.createElement('td');
                    td.className = 'px-4 py-2 text-sm text-gray-700 bg-blue-100';
                    if (typeof val === 'string' && val.includes('RW')) {
                        td.className.add = 'whitespace-nowrap';
                    }
                    td.textContent = val;
                    tr.appendChild(td);
                });
                document.getElementById('lazy-train-table').appendChild(tr);
            });

            document.getElementById('spinnerTrain').classList.add('hidden');
            this.disabled = false;
            loading = false;

            if ((batchTrain * perBatchTrain) >= totalTrain) {
                this.remove();
            }
        });
    </script>
    <script>
        let batchProcess = 1;
        const perBatchProcess = 100;
        const totalProcess = {{ count($predictions ?? []) }};
        document.getElementById('loadMoreProcessBtn')?.addEventListener('click', async function() {
            if (loading) return;
            loading = true;
            this.disabled = true;
            document.getElementById('spinnerProcess').classList.remove('hidden');

            try {
                batchProcess++;
                const res = await fetch(
                    `{{ route('naive-bayes.performance.lazy.process') }}?percentage={{ $percentage }}&batch=${batchProcess}`
                );

                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

                const data = await res.json();

                if (!Array.isArray(data)) throw new Error('Invalid response format');

                data.forEach(row => {
                    const tr = document.createElement('tr');

                    row.data.forEach(val => {
                        const td = document.createElement('td');
                        td.className = 'px-4 py-2 text-sm text-gray-700';
                        if (typeof val === 'string' && val.includes('RW')) {
                            td.className.add = 'whitespace-nowrap';
                        }
                        td.textContent = val;
                        tr.appendChild(td);
                    });

                    const resultTd = document.createElement('td');
                    resultTd.className = 'px-4 py-2 font-semibold ' + (row.correct ? 'text-green-600' :
                        'text-red-600');
                    resultTd.textContent = row.predicted;
                    tr.appendChild(resultTd);

                    document.getElementById('lazy-process-table').appendChild(tr);
                });

                if ((batchProcess * perBatchProcess) >= totalProcess) {
                    this.remove();
                }
            } catch (err) {
                console.error('Gagal load proses testing:', err.message);
            }

            document.getElementById('spinnerProcess').classList.add('hidden');
            this.disabled = false;
            loading = false;
        });
    </script>

@endsection
