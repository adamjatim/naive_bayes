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
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-800">
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
                                class="px-4 py-3.5 text-sm font-normal text-center text-gray-500 dark:text-gray-400 border-e" colspan="14">
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
                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">1</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">41</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Sedang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Rendah</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Tidak Ada Asuransi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Mobil Pribadi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Air Ledeng</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Jamban Keluarga</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Menumpang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Semen</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Batu Bata</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Kecil</td>

                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                <h2 class="font-medium text-gray-800 dark:text-white text-right">Penerima</h2>
                            </td>

                        </tr>

                        <tr>
                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">2</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">41</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Sedang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Rendah</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Tidak Ada Asuransi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Mobil Pribadi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Air Ledeng</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Jamban Keluarga</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Menumpang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Semen</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Batu Bata</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Kecil</td>

                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                <h2 class="font-medium text-gray-800 dark:text-white text-right">Penerima</h2>
                            </td>

                        </tr>

                        <tr>
                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">3</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">41</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Sedang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Ada</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Rendah</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Tidak Ada Asuransi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Mobil Pribadi</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Air Ledeng</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Jamban Keluarga</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Menumpang</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Semen</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Batu Bata</td>

                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Kecil</td>

                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                <h2 class="font-medium text-gray-800 dark:text-white text-right">Penerima</h2>
                            </td>

                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </main>

@endsection
