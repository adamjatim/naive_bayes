@extends('layout.app')

@section('title', 'Login')

@section('content')
    <form class="w-auto m-auto md:mx-60 mx-10">
        <div class="flex justify-center mb-6 gap-4">
            <img class="w-auto max-w-16 h-fit sm:max-w-20" src="./logo_kemkes_jadi.png" alt="">
            <div class="sm:flex flex-col gap-0 hidden">
                <h5 class="font-semibold sm:text-xl">Sistem Penerima</h5>
                <h5 class="font-semibold sm:text-xl">Bantuan Program</h5>
                <h5 class="font-semibold sm:text-xl">Keluarga Harapan</h5>
            </div>
        </div>
        <div class="md:px-10 lg:px-20">
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                <input type="email" id="email"
                    class="placeholder:text-gray-500 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="name@gmail.com" required />
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                <input type="password" id="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required />
            </div>

            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </div>
    </form>
@endsection
