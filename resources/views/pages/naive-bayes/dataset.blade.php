@extends('layout.app')

@section('title', 'Dataset')

@section('content')

    <header class="bg-white shadow flex flex-col">
        <div class="max-w-7xl w-full mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                Dataset
            </h2>

            <button
                class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-50 transition-colors duration-200 bg-blue-500 border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-blue-700 dark:text-gray-200 dark:border-gray-700"
                onclick="openModal('importModal')">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_3098_154395)">
                        <path
                            d="M13.3333 13.3332L9.99997 9.9999M9.99997 9.9999L6.66663 13.3332M9.99997 9.9999V17.4999M16.9916 15.3249C17.8044 14.8818 18.4465 14.1806 18.8165 13.3321C19.1866 12.4835 19.2635 11.5359 19.0351 10.6388C18.8068 9.7417 18.2862 8.94616 17.5555 8.37778C16.8248 7.80939 15.9257 7.50052 15 7.4999H13.95C13.6977 6.52427 13.2276 5.61852 12.5749 4.85073C11.9222 4.08295 11.104 3.47311 10.1817 3.06708C9.25943 2.66104 8.25709 2.46937 7.25006 2.50647C6.24304 2.54358 5.25752 2.80849 4.36761 3.28129C3.47771 3.7541 2.70656 4.42249 2.11215 5.23622C1.51774 6.04996 1.11554 6.98785 0.935783 7.9794C0.756025 8.97095 0.803388 9.99035 1.07431 10.961C1.34523 11.9316 1.83267 12.8281 2.49997 13.5832"
                            stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_3098_154395">
                            <rect width="20" height="20" fill="white" />
                        </clipPath>
                    </defs>
                </svg>

                <span>Import Dataset</span>
            </button>
        </div>

        {{-- resources/views/partials/upload-notification.blade.php --}}
        @if (session('uploaded_files'))
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg mx-4">
                <h3 class="font-medium text-blue-800 mb-2">File yang berhasil di upload:</h3>
                <ul class="list-disc list-inside space-y-1">
                    @foreach (session('uploaded_files') as $file)
                        <li class="text-blue-700">{{ $file }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </header>

    <main class="bg-gray-100 flex-grow ">
        <div class="flex flex-col m-6">

            <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg overflow-x-scroll ">
                @if ($importedData->isEmpty())
                    <!-- Tampilkan pesan jika data kosong -->
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>Tidak ada data yang diimport.</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                @if ($importedData->isNotEmpty())
                                    {{-- @foreach (json_decode($importedData->first()->row_data, true) as $key => $value) --}}
                                    @foreach (is_array($importedData->first()->row_data) ? $importedData->first()->row_data : json_decode($importedData->first()->row_data, true) as $key => $value)
                                        <th scope="col"
                                            class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">
                                            {{ $key }}
                                        </th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                            @foreach ($importedData as $index => $data)
                                <tr>
                                    {{-- @foreach (json_decode($data->row_data, true) as $value) --}}
                                    @foreach (is_array($importedData->first()->row_data) ? $importedData->first()->row_data : json_decode($importedData->first()->row_data, true) as $key => $value)
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <!-- Tampilkan navigasi pagination -->
                    <div class="mt-4">
                        {{ $importedData->links() }}
                    </div>
                @endif
            </div>
        </div>

    </main>

@endsection

@section('modals')

    <div id="importModal"
        class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center opacity-0 scale-95 hidden transition-all duration-300 ease-in-out px-5">
        <div
            class="bg-white p-5 rounded-lg w-full sm:w-full sm:max-w-sm sm:p-6 sm:align-middle opacity-0 scale-95 transition-all duration-300 ease-in-out">
            <h2 class="text-lg font-medium leading-6 text-gray-800 capitalize" id="title_modal">Import Dataset</h2>
            <form action="{{ route('naive-bayes.dataset.import') }}" method="POST" enctype="multipart/form-data"
                class="mt-4">
                @csrf
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="file-upload-placeholder">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Click to upload</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">XLS, or XLSX (Multiple files allowed)
                            </p>
                        </div>
                        <div id="file-info" class="hidden p-4 overflow-y-scroll w-full">
                            <div class="flex flex-col gap-2" id="file-list"></div>
                        </div>
                        <input id="dropzone-file" type="file" name="files[]" accept=".csv, .xls, .xlsx" class="hidden"
                            multiple />
                    </label>
                </div>
                <div class="mt-5 flex flex-row justify-between">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit"
                        class="bg-blue-600 rounded-lg hover:bg-blue-700 text-white px-4 py-2">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Handle file input change
        document.getElementById('dropzone-file').addEventListener('change', function(event) {
            const files = event.target.files;
            const fileList = document.getElementById('file-list');
            const filePlaceholder = document.getElementById('file-upload-placeholder');
            const fileInfo = document.getElementById('file-info');

            if (files.length > 0) {
                filePlaceholder.classList.add('hidden');
                fileInfo.classList.remove('hidden');
                fileList.innerHTML = '';

                Array.from(files).forEach(file => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center';
                    fileItem.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                        <g fill="none" stroke="#1D6F42" stroke-linecap="round" stroke-width="4">
                            <path stroke-linejoin="round" d="M8 15V6a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v36a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-9" />
                            <path d="M31 15h3m-6 8h6m-6 8h6" />
                            <path stroke-linejoin="round" d="M4 15h18v18H4zm6 6l6 6m0-6l-6 6" />
                        </g>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-700">${file.name}</p>
                        <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(2)} KB</p>
                    </div>
                `;
                    fileList.appendChild(fileItem);
                });
            } else {
                fileInfo.classList.add('hidden');
                filePlaceholder.classList.remove('hidden');
            }
        });


        // Fungsi untuk mereset input file dan informasi file
        function resetFileInput() {
            const fileInput = document.getElementById('dropzone-file');
            const fileInfo = document.getElementById('file-info');
            const fileUploadPlaceholder = document.getElementById('file-upload-placeholder');

            // Reset input file
            fileInput.value = '';

            // Sembunyikan informasi file dan tampilkan placeholder
            fileInfo.classList.add('hidden');
            fileUploadPlaceholder.classList.remove('hidden');
        }

        // Fungsi untuk membuka modal
        function openModal(id) {
            let modal = document.getElementById(id);
            let modalBox = modal.querySelector('div');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'scale-95');
                modalBox.classList.remove('opacity-0', 'scale-95');
                modalBox.classList.add('scale-100');
            }, 10);

            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal(id);
                }
            });
        }

        // Fungsi untuk menutup modal
        function closeModal(id) {
            let modal = document.getElementById(id);
            let modalBox = modal.querySelector('div');

            modalBox.classList.add('opacity-0', 'scale-95');
            modalBox.classList.remove('scale-100');

            setTimeout(() => {
                modal.classList.add('hidden', 'opacity-0');
                resetFileInput(); // Reset input file saat modal ditutup
            }, 300);
        }
    </script>
@endsection
