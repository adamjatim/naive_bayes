@extends('layout.app')

@section('title', 'List Karyawan')

@section('content')

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                List Karyawan
            </h2>

            <button
                class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-50 transition-colors duration-200 bg-blue-500 border rounded-lg gap-x-2 sm:w-fit dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-blue-800 dark:text-gray-200 dark:border-gray-700"
                onclick="openModal('createModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M13 11a3 3 0 1 0-3-3a3 3 0 0 0 3 3m0-4a1 1 0 1 1-1 1a1 1 0 0 1 1-1m4.11 3.86a5 5 0 0 0 0-5.72A2.9 2.9 0 0 1 18 5a3 3 0 0 1 0 6a2.9 2.9 0 0 1-.89-.14M13 13c-6 0-6 4-6 4v2h12v-2s0-4-6-4m-4 4c0-.29.32-2 4-2c3.5 0 3.94 1.56 4 2m7 0v2h-3v-2a5.6 5.6 0 0 0-1.8-3.94C24 13.55 24 17 24 17M8 12H5v3H3v-3H0v-2h3V7h2v3h3Z" />
                </svg>

                <span>Tambah Karyawan</span>
            </button>
        </div>
    </header>

    <main class="bg-gray-100 flex-grow ">

        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg overflow-x-scroll m-6">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <span>No</span>
                        </th>

                        <th scope="col"
                            class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-x-3">
                                <span>Name</span>
                            </div>
                        </th>

                        <th scope="col"
                            class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            Email address</th>

                        <th scope="col"
                            class="px-4 py-3.5 text-sm font-normal text-right text-gray-500 dark:text-gray-400">
                            <span>Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                    @foreach ($karyawan as $index => $item)
                        <tr>
                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                <h2 class="font-medium text-gray-800 dark:text-white ">{{ $item->name }}</h2>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                {{ $item->email }}
                            </td>

                            <td class="px-4 py-4 text-sm whitespace-nowrap">
                                <div class="flex justify-end items-center gap-x-6">

                                    <button
                                        class="text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none"
                                        onclick="editKaryawan({{ $item }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <button
                                        class="text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none"
                                        onclick="deleteKaryawan({{ $item->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </main>

@endsection

@section('modals')
    <!-- Modal dengan Transisi -->
    <div id="createModal"
        class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center opacity-0 scale-95 hidden transition-all duration-300 ease-in-out px-5">
        <div
            class="bg-white p-5 rounded-lg w-full sm:w-full sm:max-w-sm sm:p-6 sm:align-middle opacity-0 scale-95 transition-all duration-300 ease-in-out">
            <h2 class="text-lg font-medium leading-6 text-gray-800 capitalize" id="title_modal">Tambah Karyawan</h2>
            {{-- <p class="mt-2 text-sm text-gray-500">
                Menambahkan Karyawan baru.
            </p> --}}
            <form id="karyawanForm" class="mt-4">
                @csrf
                <input type="hidden" id="karyawan_id">
                <div class="mt-3">
                    <label class="text-sm text-gray-700">Nama</label>
                    <input type="text" id="name"
                        class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40">
                </div>
                <div class="mt-3">
                    <label class="text-sm text-gray-700">Email</label>
                    <input type="email" id="email"
                        class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40">
                </div>
                <div id="passwordField" class="mt-3">
                    <label class="text-sm text-gray-700">Password</label>
                    <input type="password" id="password"
                        class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40">
                </div>
                <div class="mt-5 flex flex-row justify-between">
                    <button type="button" onclick="closeModal('createModal')"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openModal(id, titleModal = "Tambah Karyawan") {
            let modal = document.getElementById(id);
            let modalBox = modal.querySelector('div');

            // Reset semua input saat modal dibuka
            document.getElementById('karyawan_id').value = "";
            document.getElementById('name').value = "";
            document.getElementById('email').value = "";
            document.getElementById('passwordField').classList.remove('hidden'); // Tampilkan password saat tambah baru
            document.getElementById('password').value = ""; // Reset password field

            // Ubah title modal
            document.getElementById('title_modal').innerText = titleModal;

            modal.classList.remove('hidden');
            modal.classList.add('flex')
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
            }, 300);
        }

        document.getElementById('karyawanForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let id = document.getElementById('karyawan_id').value;
            let url = id ? `/karyawan/update/${id}` : "/karyawan/store";
            let method = id ? "PUT" : "POST";

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password')?.value,
                }),
            }).then(response => location.reload());
        });

        function editKaryawan(data) {
            openModal('createModal', 'Edit Karyawan');
            document.getElementById('karyawan_id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('passwordField').classList.add('hidden');
        }

        function deleteKaryawan(id) {
            if (confirm("Yakin ingin menghapus?")) {
                fetch(`/karyawan/delete/${id}`, {
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => location.reload());
            }
        }
    </script>
@endsection
