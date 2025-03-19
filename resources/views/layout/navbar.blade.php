<nav class="bg-gray-900 border-gray-700">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-4">
        @if (Auth::check())
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 rtl:space-x-reverse my-4">
                {{-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> --}}
                {{-- <img src="{{ asset('logo.png') }}" class="h-8" alt="Flowbite Logo" /> --}}
                <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">
                    Halo, {{ Auth::user()->name }} 👋
                </span>
            </a>
        @endif
        <button data-collapse-toggle="navbar-multi-level" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600"
            aria-controls="navbar-multi-level" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto my-4 md:my-0" id="navbar-multi-level">
            <ul
                class="flex flex-col font-medium p-4 md:p-0  border rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0  bg-gray-800 md:bg-gray-900 border-gray-700">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex flex-row md:gap-1 justify-between py-2 px-3 text-white rounded-sm md:p-0 md:hover:text-blue-300
                        @if (Route::is('dashboard')) md:border-b-blue-500 md:border-b-2 bg-blue-600
                        @else
                            md:hover:border-b-blue-500 md:hover:border-b-2 @endif md:bg-transparent  md:py-4"
                        aria-current="page">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M16.008 19q-.356 0-.586-.232q-.23-.233-.23-.576v-4.184q0-.356.232-.586q.233-.23.576-.23h4.185q.356 0 .585.233T21 14v4.185q0 .356-.232.585t-.576.23zM12 10.808q-.343 0-.575-.232T11.192 10V5.815q0-.355.233-.585T12 5h8.192q.344 0 .576.232t.232.576v4.185q0 .355-.232.585q-.233.23-.576.23zM3.808 19q-.343 0-.576-.232T3 18.192v-4.184q0-.356.232-.586t.576-.23H12q.343 0 .576.232t.232.576v4.185q0 .356-.232.585T12 19zm.007-8.192q-.355 0-.585-.232T3 10V5.815q0-.355.232-.585T3.808 5h4.185q.355 0 .585.232t.23.576v4.185q0 .355-.232.585q-.233.23-.576.23z" />
                        </svg>
                        <span class="mx-auto">Dashboard</span>
                    </a>
                </li>
                @if (Auth::user()->role == 'admin')
                    <li>
                        <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                            class="flex flex-row md:gap-1 justify-between py-2 px-3 text-white rounded-sm md:p-0 md:hover:text-blue-300 items-center w-full
                        @if (Route::is('naive-bayes.*')) md:border-b-blue-500 md:border-b-2 md:bg-transparent bg-blue-600
                        @else
                            md:hover:border-b-blue-500 md:hover:border-b-2 @endif  md:py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12.125 1H22v22H2V1.83l10.125 10zm2 20H20v-1.333h-3.15v-2H20v-1.334h-3.15v-2H20V13h-3.15v-2H20V9.667h-3.15v-2H20V6.333h-3.15v-2H20V3h-5.875zm-2 0v-6.36L4 6.615V21z" />
                            </svg>
                            <span class="mx-auto">Naive Bayes</span>
                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdownNavbar"
                            class="hidden absolute font-normal divide-y rounded-lg shadow-sm w-44 bg-gray-700 divide-gray-600">
                            <ul class="py-2 text-sm  text-gray-200" aria-labelledby="dropdownLargeButton">
                                <li>
                                    <a href="{{ route('naive-bayes.dataset.index') }}"
                                        class="flex flex-row justify-between px-4 py-2 hover:bg-gray-600 hover:text-white">
                                        <span>Dataset</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M21 7c0 2.21-4.03 4-9 4S3 9.21 3 7m18 0c0-2.21-4.03-4-9-4S3 4.79 3 7m18 0v5M3 7v5m18 0c0 2.21-4.03 4-9 4s-9-1.79-9-4m18 0v5c0 2.21-4.03 4-9 4s-9-1.79-9-4v-5" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('naive-bayes.initial-process') }}"
                                        class="flex flex-row justify-between px-4 py-2 hover:bg-gray-600 hover:text-white">
                                        <span>Initial Process</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 22c-.818 0-1.6-.33-3.163-.99C3.946 19.366 2 18.543 2 17.16V7m9 15V11.355M11 22c.34 0 .646-.057 1-.172M20 7v4.5M18 18l.906-.905M22 18a4 4 0 1 0-8 0a4 4 0 0 0 8 0M7.326 9.691L4.405 8.278C2.802 7.502 2 7.114 2 6.5s.802-1.002 2.405-1.778l2.92-1.413C9.13 2.436 10.03 2 11 2s1.871.436 3.674 1.309l2.921 1.413C19.198 5.498 20 5.886 20 6.5s-.802 1.002-2.405 1.778l-2.92 1.413C12.87 10.564 11.97 11 11 11s-1.871-.436-3.674-1.309M5 12l2 1m9-9L6 9"
                                                color="currentColor" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('naive-bayes.performance') }}"
                                        class="flex flex-row justify-between px-4 py-2 hover:bg-gray-600 hover:text-white">
                                        <span>Performance</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24">
                                            <g fill="none">
                                                <path
                                                    d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                                <path fill="currentColor"
                                                    d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm0 2H5v14h14zm-6.963 2.729c.271-.868 1.44-.95 1.85-.184l.052.11L15.677 12H17a1 1 0 0 1 .117 1.993L17 14h-1.993a1.01 1.01 0 0 1-.886-.524l-.052-.11l-.953-2.384l-1.654 5.293c-.259.828-1.355.953-1.807.255l-.06-.105L8.381 14H7a1 1 0 0 1-.117-1.993L7 12h1.994c.34 0 .654.17.84.449l.063.11l.388.776z" />
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('naive-bayes.prediction') }}"
                                        class="flex flex-row justify-between px-4 py-2 hover:bg-gray-600 hover:text-white">
                                        <span>Prediction</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 16 16">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                d="M1.75 1a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5zM1 4.75A.75.75 0 0 1 1.75 4H7a.75.75 0 0 1 0 1.5H1.75A.75.75 0 0 1 1 4.75m9 7.75a2.5 2.5 0 1 0 0-5a2.5 2.5 0 0 0 0 5m0 1.5c.834 0 1.607-.255 2.248-.691l1.472 1.471a.75.75 0 1 0 1.06-1.06l-1.471-1.472A4 4 0 1 0 10 14M1.75 7a.75.75 0 0 0 0 1.5H4A.75.75 0 0 0 4 7z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <li>
                    <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownSettings"
                        class="flex flex-row md:gap-1 justify-between py-2 px-3 text-white rounded-sm md:p-0 md:hover:text-blue-300 items-center w-full
                        @if (Route::is('profile.*') || Route::is('karyawan.*')) md:border-b-blue-500 md:border-b-2 md:bg-transparent bg-blue-600
                        @else
                            md:hover:border-b-blue-500 md:hover:border-b-2 @endif  md:py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024">
                            <path fill="currentColor"
                                d="M600.704 64a32 32 0 0 1 30.464 22.208l35.2 109.376c14.784 7.232 28.928 15.36 42.432 24.512l112.384-24.192a32 32 0 0 1 34.432 15.36L944.32 364.8a32 32 0 0 1-4.032 37.504l-77.12 85.12a357 357 0 0 1 0 49.024l77.12 85.248a32 32 0 0 1 4.032 37.504l-88.704 153.6a32 32 0 0 1-34.432 15.296L708.8 803.904c-13.44 9.088-27.648 17.28-42.368 24.512l-35.264 109.376A32 32 0 0 1 600.704 960H423.296a32 32 0 0 1-30.464-22.208L357.696 828.48a352 352 0 0 1-42.56-24.64l-112.32 24.256a32 32 0 0 1-34.432-15.36L79.68 659.2a32 32 0 0 1 4.032-37.504l77.12-85.248a357 357 0 0 1 0-48.896l-77.12-85.248A32 32 0 0 1 79.68 364.8l88.704-153.6a32 32 0 0 1 34.432-15.296l112.32 24.256c13.568-9.152 27.776-17.408 42.56-24.64l35.2-109.312A32 32 0 0 1 423.232 64H600.64zm-23.424 64H446.72l-36.352 113.088l-24.512 11.968a294 294 0 0 0-34.816 20.096l-22.656 15.36l-116.224-25.088l-65.28 113.152l79.68 88.192l-1.92 27.136a293 293 0 0 0 0 40.192l1.92 27.136l-79.808 88.192l65.344 113.152l116.224-25.024l22.656 15.296a294 294 0 0 0 34.816 20.096l24.512 11.968L446.72 896h130.688l36.48-113.152l24.448-11.904a288 288 0 0 0 34.752-20.096l22.592-15.296l116.288 25.024l65.28-113.152l-79.744-88.192l1.92-27.136a293 293 0 0 0 0-40.256l-1.92-27.136l79.808-88.128l-65.344-113.152l-116.288 24.96l-22.592-15.232a288 288 0 0 0-34.752-20.096l-24.448-11.904L577.344 128zM512 320a192 192 0 1 1 0 384a192 192 0 0 1 0-384m0 64a128 128 0 1 0 0 256a128 128 0 0 0 0-256" />
                        </svg>
                        <span class="mx-auto">Settings</span>
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 4 4 4-4" />
                        </svg></button>
                    <!-- Dropdown menu -->
                    <div id="dropdownSettings"
                        class="hidden font-normal divide-y rounded-lg shadow-sm w-44 bg-gray-700 divide-gray-600">
                        <ul class="py-2 text-sm  text-gray-200" aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="{{ route('profile.index') }}"
                                    class="flex flex-row justify-between  px-4 py-2 hover:bg-gray-600 hover:text-white">
                                    <span>Profile</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linejoin="round"
                                                d="M4 18a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z" />
                                            <circle cx="12" cy="7" r="3" />
                                        </g>
                                    </svg>
                                </a>
                            </li>
                            @if (Auth::user()->role === 'admin')
                                <li>
                                    <a href="{{ route('karyawan.index') }}"
                                        class="flex flex-row justify-between  px-4 py-2  hover:bg-gray-600 hover:text-white">
                                        <span>Karyawan</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M9 13.75c-2.34 0-7 1.17-7 3.5V19h14v-1.75c0-2.33-4.66-3.5-7-3.5M4.34 17c.84-.58 2.87-1.25 4.66-1.25s3.82.67 4.66 1.25zM9 12c1.93 0 3.5-1.57 3.5-3.5S10.93 5 9 5S5.5 6.57 5.5 8.5S7.07 12 9 12m0-5c.83 0 1.5.67 1.5 1.5S9.83 10 9 10s-1.5-.67-1.5-1.5S8.17 7 9 7m7.04 6.81c1.16.84 1.96 1.96 1.96 3.44V19h4v-1.75c0-2.02-3.5-3.17-5.96-3.44M15 12c1.93 0 3.5-1.57 3.5-3.5S16.93 5 15 5c-.54 0-1.04.13-1.5.35c.63.89 1 1.98 1 3.15s-.37 2.26-1 3.15c.46.22.96.35 1.5.35" />
                                        </svg>
                                    </a>
                                </li>
                            @endif
                        </ul>
                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button
                                    class="flex flex-row justify-between  px-4 py-2 text-sm   hover:bg-gray-600 text-gray-200 hover:text-white w-full text-start"
                                    type="submit">
                                    <span>Logout</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h6q.425 0 .713.288T12 4t-.288.713T11 5H5v14h6q.425 0 .713.288T12 20t-.288.713T11 21zm12.175-8H10q-.425 0-.712-.288T9 12t.288-.712T10 11h7.175L15.3 9.125q-.275-.275-.275-.675t.275-.7t.7-.313t.725.288L20.3 11.3q.3.3.3.7t-.3.7l-3.575 3.575q-.3.3-.712.288t-.713-.313q-.275-.3-.262-.712t.287-.688z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
