@extends('layout.app')

@section('title', 'Performance')

@section('content')

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                Uji Akurasi Metode
            </h2>

        </div>
    </header>

    <main class="bg-gray-100 flex-grow ">
        <div class="mx-6 mt-2">
            <div>Prosentase Data Training</div>
            <form action="{{ route('naive-bayes.performance.calculate') }}" method="POST">
                @csrf
                <select class="w-2/5 border-gray-200 rounded-md" name="percentage" id="percentage">
                    <option value="">-- persentase --</option>
                    <option value="10">10%</option>
                    <option value="20">20%</option>
                    <option value="30">30%</option>
                    <option value="40">40%</option>
                    <option value="50">50%</option>
                    <option value="60">60%</option>
                    <option value="70">70%</option>
                    <option value="80">80%</option>
                    <option value="90">90%</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Hitung Akurasi</button>
            </form>
        </div>

        <div class="flex flex-col m-6">
            <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg overflow-x-scroll ">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                    <thead class="bg-white">
                        <tr>
                            @if ($importedData->isNotEmpty())
                                @foreach (json_decode($importedData->first()->row_data, true) as $key => $value)
                                    <th scope="col"
                                        class="py-3.5 px-4 text-sm font-normal text-center text-gray-500 dark:text-gray-400">
                                        {{ $key }}
                                    </th>
                                @endforeach
                            @endif
                        </tr>

                        <tr class="border-y">
                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-center text-gray-500 dark:text-gray-400 border-e"
                                colspan="{{ $importedData->isNotEmpty() ? count(json_decode($importedData->first()->row_data, true)) - 1 : ' ' }}">
                                <span>-- Atribut Pendukung --</span>
                            </th>
                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-center text-nowrap text-gray-500 dark:text-gray-400 border-s">
                                <span>-- Label Target --</span>
                            </th>
                        </tr>

                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">

                        @foreach ($importedData as $index => $data)
                            @php
                                $rowData = json_decode($data->row_data, true);
                                $totalColumns = count($rowData);
                                $currentColumn = 0;
                            @endphp
                            <tr>
                                @foreach ($rowData as $key => $value)
                                    @php
                                        $currentColumn++;
                                        $isLastColumn = ($currentColumn === $totalColumns);
                                    @endphp
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  text-center {{ $isLastColumn ? 'bg-blue-100' : 'bg-yellow-100' }}">
                                        {{ $value }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        @if (session('accuracy'))
            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                <p>Hasil Akurasi: {{ session('accuracy') }}%</p>
            </div>
        @endif
    </main>

@endsection
