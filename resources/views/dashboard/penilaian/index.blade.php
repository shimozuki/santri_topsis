@extends('dashboard.layouts.app')

@section('container')
<div class="container mx-auto p-4">

    @if (Auth::user()->roles->pluck('name')->contains('penguji_1'))
    <h4 class="mb-3 font-bold">Tes Wawancara</h4>
    @include('dashboard.penilaian._tabel', [
    'objek' => $objek,
    'kriteriaId' => $mapKriteria['Tes Wawancara']['id'],
    'pengujiKe' => $mapKriteria['Tes Wawancara']['penguji_ke'],
    ])
    @endif

    @if (Auth::user()->roles->pluck('name')->contains('penguji_2'))
    <h4 class="mb-3 font-bold">Tes Tulis</h4>
    @include('dashboard.penilaian._tabel', [
    'objek' => $objek,
    'kriteriaId' => $mapKriteria['Tes Tulis']['id'],
    'pengujiKe' => $mapKriteria['Tes Tulis']['penguji_ke'],
    ])
    @endif

    @if (Auth::user()->roles->pluck('name')->contains('penguji_3'))
    <h4 class="mb-3 font-bold">Tes Hafalan Qur'an</h4>
    @include('dashboard.penilaian._tabel', [
    'objek' => $objek,
    'kriteriaId' => $mapKriteria["Tes Hafalan Qur'an"]['id'],
    'pengujiKe' => $mapKriteria["Tes Hafalan Qur'an"]['penguji_ke'],
    ])
    @endif

</div>

@include('dashboard.penilaian._modal_nilai')
@endsection