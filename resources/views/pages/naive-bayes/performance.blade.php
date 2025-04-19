@extends('layout.app')

@section('title', 'Performance')

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

        @if ($percentage)
            <!-- Tabel Pemisahan Data -->
            <div class="bg-white shadow rounded-lg overflow-x-auto m-4">
                <h3 class="text-xl font-semibold text-gray-700 my-4 mx-2">Pemisahan Data Training & Testing</h3>

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
                    <tbody>
                        <tr>
                            <td colspan="100%" class="px-4 py-2 text-center bg-blue-400 text-white font-semibold">Data Training
                                ({{ $trainCount }})</td>
                        </tr>
                        @foreach ($trainData as $row)
                            <tr>
                                @foreach ($row->getAttributes() as $key => $val)
                                    @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                        <td class="px-4 py-2 text-sm text-gray-700 @if (strpos($val, 'RW') !== false) whitespace-nowrap @endif">{{ $val }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="100%" class="px-4 py-2 text-center bg-yellow-300 text-gray-700 font-semibold ">Data Testing
                                ({{ $testCount }})</td>
                        </tr>
                        @foreach ($testData as $row)
                            <tr>
                                @foreach ($row->getAttributes() as $key => $val)
                                    @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $val }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tabel Proses Testing -->
            @if (!empty($predictions))
                <div class="mt-10 m-4  bg-white p-4 shadow rounded-lg overflow-x-auto">
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
                                <th class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase text-left">Hasil Testing
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($predictions as $row)
                                <tr>
                                    @foreach ($row['data']->getAttributes() as $key => $val)
                                        @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                                            <td class="px-4 py-2 text-sm text-gray-700 @if (strpos($val, 'RW') !== false) whitespace-nowrap @endif">{{ $val }}</td>
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
                </div>

            @endif


            <!-- Hasil Kalkulasi -->
            <div class="mt-6 m-4 p-4 bg-red-500 border border-red-200 text-white rounded">
                <h3 class="font-semibold">Hasil Kalkulasi</h3>
                <p>Total Data: {{ $total }}</p>
                <p>Data Training: {{ $trainCount }}</p>
                <p>Data Testing: {{ $testCount }}</p>
                @if (!empty($predictions))
                    <p>Akurasi: <span class="font-semibold">{{ $accuracy }}%</span></p>
                @endif

            </div>
        @endif
    </main>

@endsection
