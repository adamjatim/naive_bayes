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
                            <th scope="col"
                                class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>No</span>
                            </th>

                            <th scope="col"
                                class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Usia</span>
                            </th>

                            <th scope="col"
                                class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Jumlah Tanggungan Kepala Keluarga</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Lansia</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Anak Wajib Sekolah</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Penghasilan Kepala Keluarga</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Status BPJS Anggota Keluarga</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Tipe Kendaraan</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Sumber Air Bersih</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Tipe Jamban</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Status Kepemilikan Bangunan</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Bahan Dasar Lantai</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Bahan Dasar Dinding</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                <span>Kategori Luas Bangunan</span>
                            </th>

                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-right text-gray-500 dark:text-gray-400">
                                <span>KETERANGAN</span>
                            </th>
                        </tr>

                        <tr class="border-y">
                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-center text-gray-500 dark:text-gray-400 border-e"
                                colspan="14">
                                <span>-- Atribut Pendukung --</span>
                            </th>
                            <th scope="col"
                                class="px-4 py-3.5 text-sm font-normal text-center text-nowrap text-gray-500 dark:text-gray-400  border-s">
                                <span>-- Label Target --</span>
                            </th>
                        </tr>

                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                        <tr>
                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap  bg-yellow-100">1</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                41</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Sedang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Rendah</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Tidak Ada Asuransi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Mobil Pribadi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Air Ledeng</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Jamban Keluarga</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Menumpang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Semen</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Batu Bata</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Kecil</td>

                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap  bg-blue-100">
                                <h2 class="font-medium text-gray-800 dark:text-white text-right">Penerima</h2>
                            </td>

                        </tr>

                        <tr>
                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap  bg-yellow-100">2</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                41</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Sedang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Rendah</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Tidak Ada Asuransi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Mobil Pribadi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Air Ledeng</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Jamban Keluarga</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Menumpang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Semen</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Batu Bata</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Kecil</td>

                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap  bg-blue-100">
                                <h2 class="font-medium text-gray-800 dark:text-white text-right">Penerima</h2>
                            </td>

                        </tr>

                        <tr>
                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap  bg-yellow-100">3</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                41</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Sedang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Ada</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Rendah</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Tidak Ada Asuransi</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Mobil Pribadi</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Air Ledeng</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Jamban Keluarga</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Menumpang</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Semen</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Batu Bata</td>

                            <td
                                class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap  bg-yellow-100">
                                Kecil</td>

                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap  bg-blue-100">
                                <h2 class="font-medium text-gray-800 dark:text-white text-right">Penerima</h2>
                            </td>

                        </tr>

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
