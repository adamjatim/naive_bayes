@extends('layout.app')

@section('title', 'Initial Process')

@section('content')

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                Initial Process
            </h2>
        </div>
    </header>

    <main class="bg-gray-100 flex-grow ">
        <div class="flex flex-col m-6">
            <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg overflow-x-scroll ">
                @if ($importedData->isEmpty())
                    {{-- Tampilkan pesan jika data kosong --}}
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>Tidak ada data yang diimport.</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                        <thead class="bg-white">
                            <tr>
                                @if ($importedData->isNotEmpty())
                                    @foreach (json_decode($importedData->first()->row_data, true) as $key => $value)
                                        <th scope="col"
                                            class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400 text-center @if (count(json_decode($importedData->first()->row_data, true)) )

                                            @else

                                            @endif">
                                            {{ $key }}
                                        </th>
                                    @endforeach
                                @endif
                            </tr>

                            <tr class="border-y">
                                <th scope="col"
                                    class="px-4 py-3.5 text-sm font-normal text-center text-gray-500 dark:text-gray-400 border-e"
                                    colspan="{{ count(json_decode($importedData->first()->row_data, true)) - 1 }}">
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
                                <tr>
                                    @php
                                        $rowData = json_decode($data->row_data, true);
                                        $totalColumns = count($rowData);
                                        $currentColumn = 0;
                                    @endphp

                                    @foreach ($rowData as $key => $value)
                                        @php
                                            $currentColumn++;
                                            $isLastColumn = $currentColumn === $totalColumns;
                                        @endphp

                                        <td
                                            class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap
                                            {{ $isLastColumn ? 'bg-yellow-100 text-center' : 'bg-blue-100' }}">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    {{-- Tampilkan navigasi pagination --}}
                    <div class="mt-4">
                        {{ $importedData->links() }}
                    </div>

                @endif
            </div>
        </div>

    </main>

@endsection
