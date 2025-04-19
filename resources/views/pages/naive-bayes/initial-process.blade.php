@extends('layout.app')

@section('title', 'Initial Process')

@section('content')
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold text-gray-800">Initial Process</h2>
    </div>
</header>

<main class="bg-gray-100 p-6 flex-grow">
    <div class="overflow-x-auto border border-gray-200 rounded-lg bg-white">
        @if ($importedData->isEmpty())
            <div class="text-center text-gray-500 p-6">Tidak ada data yang diimport.</div>
        @else
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach (array_keys($importedData->first()->toArray()) as $col)
                            <th class="py-3.5 px-4 text-sm font-semibold text-left text-gray-500 dark:text-gray-400 uppercase">
                                {{ Str::headline($col) }}
                            </th>
                        @endforeach
                    </tr>
                    <tr class="">
                        <th colspan="16" class="px-4 py-2 text-center text-sm font-medium bg-blue-400 text-white">Atribut Pendukung</th>
                        <th class="px-4 py-2 text-center text-sm font-medium bg-yellow-200 text-gray-600">Label Target</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($importedData as $data)
                        <tr>
                            @foreach ($data->toArray() as $key => $value)
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap {{ $key === 'keterangan' ? 'bg-yellow-100 font-semibold text-yellow-800' : '' }}">
                                    {{ $value }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{-- {{ $importedData->links() }} --}}
            </div>
        @endif
    </div>
</main>
@endsection
