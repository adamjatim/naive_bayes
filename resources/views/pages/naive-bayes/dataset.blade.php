@extends('layout.app')

@section('title', 'Dataset')

@section('styles')
    <style>
        /* File Upload Dropzone */
        .file-upload {
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            background-color: #f9fafb;
        }

        /* File Preview Container */
        #file-preview-container {
            transition: all 0.3s ease;
        }

        /* File List Scrollbar */
        #file-list::-webkit-scrollbar {
            width: 6px;
        }

        #file-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #file-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        #file-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection

@section('content')

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">Dataset</h2>
            <div class="flex flex-row gap-2">
                <button onclick="openModal('importModal')"
                    class="flex items-center px-5 py-2 text-sm text-white bg-blue-500 hover:bg-blue-700 rounded-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v16h16V4H4zm8 4v4m0 0v4m0-4h4m-4 0H8" />
                    </svg>
                    Import Dataset
                </button>
                <form method="POST" action="{{ route('naive-bayes.dataset.deleteAll') }}"
                    onsubmit="return confirm('Yakin ingin menghapus seluruh data dataset?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center px-5 py-2 text-sm text-white bg-red-500 hover:bg-red-700 rounded-lg">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                            <path fill="currentColor" fill-rule="evenodd" d="M5.75 3V1.5h4.5V3zm-1.5 0V1a1 1 0 0 1 1-1h5.5a1 1 0 0 1 1 1v2h2.5a.75.75 0 0 1 0 1.5h-.365l-.743 9.653A2 2 0 0 1 11.148 16H4.852a2 2 0 0 1-1.994-1.847L2.115 4.5H1.75a.75.75 0 0 1 0-1.5zm-.63 1.5h8.76l-.734 9.538a.5.5 0 0 1-.498.462H4.852a.5.5 0 0 1-.498-.462z" clip-rule="evenodd" stroke-width="0.1" stroke="currentColor" />
                        </svg>
                        Hapus Seluruh Dataset
                    </button>
                </form>
            </div>

        </div>

        @if ($activeFiles->isNotEmpty())
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg mx-4">
                <h3 class="font-medium text-blue-800 mb-2">File yang sedang digunakan:</h3>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($activeFiles as $file)
                        <li class="text-blue-700">
                            <span class="font-semibold">{{ $file->file_name }}</span>
                            &mdash; {{ number_format($file->file_size / 1024, 2) }} KB
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mx-4 mb-4 p-4 bg-green-50 text-green-800 border border-green-200 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mx-4 mb-4 p-4 bg-red-50 text-red-800 border border-red-200 rounded">
                {{ session('error') }}
            </div>
        @endif


    </header>

    <main class="bg-gray-100 p-6 flex-grow">
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
            @if ($importedData->isEmpty())
                <div class="text-center text-gray-500 p-6">Tidak ada data yang diimport.</div>
            @else
                <table class="min-w-full table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach (array_keys($importedData->first()->toArray()) as $column)
                                <th
                                    class="py-3.5 px-4 text-sm font-semibold text-left text-gray-500 dark:text-gray-400 uppercase">
                                    {{ Str::headline($column) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($importedData as $data)
                            <tr>
                                @foreach ($data->toArray() as $key => $value)
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                        {{ $value }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $importedData->links() }}
                </div>
            @endif
        </div>
    </main>

@endsection

@section('modals')
    <div id="importModal"
        class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center opacity-0 scale-95 hidden transition-all duration-300 ease-in-out px-5">
        <div
            class="bg-white p-5 rounded-lg w-full sm:w-full sm:max-w-md sm:p-6 sm:align-middle opacity-0 scale-95 transition-all duration-300 ease-in-out">
            <h2 class="text-lg font-medium leading-6 text-gray-800 capitalize" id="title_modal">Import Dataset</h2>
            <form action="{{ route('naive-bayes.dataset.import') }}" method="POST" enctype="multipart/form-data"
                class="mt-4">
                @csrf
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="file-upload-placeholder">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                            <p class="text-xs text-gray-500">Excel files only (.xlsx, .xls)</p>
                        </div>
                        <div id="file-preview-container" class="hidden w-full h-full">
                            <div class="border rounded-lg divide-y divide-gray-200 w-full h-full">
                                <div class="px-4 py-2 bg-gray-50 font-medium text-sm text-gray-500">
                                    Selected Files
                                </div>
                                <div id="file-list" class="max-h-48 overflow-y-auto"></div>
                            </div>
                        </div>
                        <input id="dropzone-file" type="file" name="files[]" multiple accept=".xlsx,.xls"
                            class="hidden" />
                    </label>
                </div>
                <div class="mt-5 flex flex-row justify-between">
                    <button type="button" onclick="closeModal('importModal')"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" id="upload-button"
                        class="bg-blue-600 rounded-lg hover:bg-blue-700 text-white px-4 py-2 hidden">Upload All</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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

        function closeModal(id) {
            let modal = document.getElementById(id);
            let modalBox = modal.querySelector('div');

            modalBox.classList.add('opacity-0', 'scale-95');
            modalBox.classList.remove('scale-100');

            setTimeout(() => {
                modal.classList.add('hidden', 'opacity-0');
                resetFileInput();
            }, 300);
        }

        function resetFileInput() {
            document.getElementById('dropzone-file').value = '';
            document.getElementById('file-preview-container').classList.add('hidden');
            document.getElementById('file-upload-placeholder').classList.remove('hidden');
            document.getElementById('upload-button').classList.add('hidden');
            document.getElementById('file-list').innerHTML = '';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        document.getElementById('dropzone-file').addEventListener('change', function(event) {
            const files = event.target.files;
            const fileList = document.getElementById('file-list');
            const placeholder = document.getElementById('file-upload-placeholder');
            const previewContainer = document.getElementById('file-preview-container');
            const uploadButton = document.getElementById('upload-button');

            if (files.length > 0) {
                placeholder.classList.add('hidden');
                previewContainer.classList.remove('hidden');
                uploadButton.classList.remove('hidden');
                fileList.innerHTML = '';

                Array.from(files).forEach(file => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'px-4 py-2 flex items-center';

                    fileItem.innerHTML = `
                    <div class="flex-shrink-0 text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                        <g fill="none" stroke="#1D6F42" stroke-linecap="round" stroke-width="4">
                            <path stroke-linejoin="round" d="M8 15V6a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v36a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-9" />
                            <path d="M31 15h3m-6 8h6m-6 8h6" />
                            <path stroke-linejoin="round" d="M4 15h18v18H4zm6 6l6 6m0-6l-6 6" />
                        </g>
                    </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)} • ${file.name.split('.').pop().toUpperCase()}</p>
                    </div>
                `;

                    fileList.appendChild(fileItem);
                });
            } else {
                resetFileInput();
            }
        });
    </script>
@endsection
