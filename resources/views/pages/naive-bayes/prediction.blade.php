@extends('layout.app')

@section('title', 'Prediction')

@section('content')
    <div class="flex flex-row w-full mx-auto px-4 py-6 space-y-6">
        {{-- Bagian 1: Form Input --}}
        <div class="w-full mx-5 max-w-[42.76vw]">
            <form method="POST" action="{{ route('naive-bayes.prediction.index') }}"
                class="space-y-4 bg-white p-6 rounded shadow">
                @csrf

                <h2 class="text-xl font-semibold text-gray-800">Prediksi Naive Bayes</h2>

                @php
                    $fields = [
                        'rw' => 'RW',
                        'kriteria' => 'Kriteria',
                        'usia' => 'Usia',
                        'nama' => 'Nama',
                        'tanggungan_kepala_keluarga' => 'Tanggungan Kepala Keluarga',
                        'lansia' => 'Lansia',
                        'anak_wajib_sekolah' => 'Anak Wajib Sekolah',
                        'penghasilan_kepala_keluarga' => 'Penghasilan Kepala Keluarga',
                        'status_bpjs' => 'Status BPJS',
                        'tipe_kendaraan' => 'Tipe Kendaraan',
                        'sumber_air' => 'Sumber Air',
                        'tipe_jamban' => 'Tipe Jamban',
                        'status_kepemilikan_bangunan' => 'Status Kepemilikan Bangunan',
                        'bahan_lantai' => 'Bahan Lantai',
                        'bahan_dinding' => 'Bahan Dinding',
                        'kategori_luas_bangunan' => 'Kategori Luas Bangunan',
                        'keterangan' => 'Keterangan (opsional untuk evaluasi)',
                    ];
                @endphp

                @foreach ($fields as $name => $label)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                        <input type="text" name="{{ $name }}" list="suggestions-{{ $name }}"
                            value="{{ old($name, $formInput[$name] ?? '') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />

                        @if (!empty($suggestions[$name]))
                            <datalist id="suggestions-{{ $name }}">
                                @foreach ($suggestions[$name] as $option)
                                    <option value="{{ $option }}"></option>
                                @endforeach
                            </datalist>
                        @endif
                    </div>
                @endforeach


                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Prediction</button>
            </form>
        </div>

        {{-- Bagian 2: Proses Prediksi --}}
        @if (!empty($probabilities))
            <div class="bg-white p-6 rounded shadow space-y-6 w-full mx-5">
                <h3 class="text-lg font-semibold text-gray-800">Hasil Proses</h3>

                {{-- Total Label --}}
                <div class="bg-cyan-500 text-white p-4 rounded-lg">
                    <h4 class="font-bold">Total Label:</h4>
                    <p class="text-sm ">
                        @foreach ($labelCount as $label => $count)
                            {{ $label }}: {{ $count }}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                        | Total: {{ $total }}
                    </p>
                </div>

                {{-- Statistik dari input --}}
                @foreach ($stats as $field => $values)
                    <div class="bg-amber-500 text-white p-4 rounded-lg">
                        <h4 class="font-bold ">{{ ucfirst(str_replace('_', ' ', $field)) }} ::
                            {{ $formInput[$field] }}</h4>
                        <ul class="text-sm  ml-4 list-disc">
                            @foreach ($values as $label => $count)
                                <li>{{ $label }}: {{ $count }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                {{-- Hasil Prediksi --}}
                <div class="border-t mt-4 bg-green-600 text-white p-4 rounded-lg">
                    <h4 class="font-semibold ">Hasil Prediksi:</h4>
                    @foreach ($probabilities as $label => $score)
                        <p class="text-sm">{{ $label }}: <span class="font-medium">{{ $score }}%</span></p>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
