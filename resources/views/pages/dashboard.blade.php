@extends('layout.app')

@section('title', 'Dashboard')

@section('content')

    @if ( Auth::user()->role == 'admin')
        <div class="flex-grow">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
            <p>Selamat datang, Admin! Berikut adalah ringkasan sistem.</p>
            <ul class="mt-4">
                <li>- Statistik penerima bantuan</li>
                <li>- Manajemen user</li>
                <li>- Laporan dan Analisis</li>
            </ul>
        </div>

    @elseif ( Auth::user()->role == 'karyawan')
        <div class="flex-grow">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Karyawan</h1>
            <p>Selamat datang, Karyawan! Berikut adalah tugas-tugas Anda.</p>
            <ul class="mt-4">
                <li>- Cek data warga</li>
                <li>- Input data penerima bantuan</li>
                <li>- Cetak laporan</li>
            </ul>
        </div>
    @else
        <div class="unauthorized">
            <h1 class="text-2xl font-bold text-red-600">Akses Ditolak</h1>
            <p>Anda tidak memiliki akses ke halaman ini.</p>
        </div>
    @endif

@endsection
