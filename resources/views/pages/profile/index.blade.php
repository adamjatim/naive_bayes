@extends('layout.app')

@section('title', 'Dataset')

@section('content')

    <div class="flex flex-col flex-grow gap-0 md:m-6 md:mx-10 m-4">
        <div class="p-4 rounded-md rounded-b-none border">
            <h1 class="text-2xl font-bold text-gray-800">Profile</h1>
        </div>
        <div class="p-2.5 rounded-md rounded-t-none border">
            <div class="flex flex-col md:flex-row md:gap-6 w-full">
                <div class="w-1/3 p-2">
                    <h1 class="text-md font-medium text-gray-700">Profile Information</h1>
                    <h3 class="text-sm font-normal text-gray-600">Update your account's profile information and email address.
                    </h3>
                </div>
                <div class="w-2/3 p-2 border rounded-md flex flex-col">
                    <div class="w-4/6 flex flex-col gap-4">
                        <div class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700" for="name">Name</label>
                            <input
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                                id="name" type="text">
                        </div>
                        <div class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700" for="email">Email</label>
                            <input
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                                id="email" type="text">
                        </div>
                    </div>
                    <div class="w-2/6 ms-auto ">
                        <button type="submit">Save</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
