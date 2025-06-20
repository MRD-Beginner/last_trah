@php
    $isMenu = false;
    $navbarHideToggle = false;
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Keluarga')

@section('page-script')

@section('content')

    <div class="nav-align-top">
        <ul class="nav nav-pills mb-4 nav-fill bg-white p-2" role="tablist">
            <li class="nav-item mb-1 mb-sm-0">
                <a href="{{ route('keluarga.detail.public', $tree_id) }}"
                    class="nav-link {{ request()->is('detail/public/data/keluarga/data/*') ? 'active' : '' }}" role="tab">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-person me-2"></i>Data Keluarga
                    </span>
                    <i class="fa-solid fa-person icon-sm d-sm-none"></i>
                </a>
            </li>
            <li class="nav-item mb-1 mb-sm-0">
                <a href="{{ route('keluarga.detail.pohon', $tree_id) }}"
                    class="nav-link {{ request()->is('detail/public/data/keluarga/pohon/*') ? 'active' : '' }}"
                    role="tab">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-sitemap me-2"></i>Pohon Keluarga
                    </span>
                    <i class="fa-solid fa-sitemap icon-sm d-sm-none"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href=""
                    class="nav-link active"
                    role="tab" aria-selected="true">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-link me-2"></i>Hubungan
                    </span>
                    <i class="fa-solid fa-link icon-sm d-sm-none"></i>
                </a>
            </li>
        </ul>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('keluarga.detail.hubungan', $tree_id) }}" method="GET">
                
                <input type="hidden" name="tree_id" value="{{ $tree_id }}">
                <input type="hidden" name="compare" value="true">

                <div class="container px-4">
                    <div class="row gx-5">
                        <div class="col">
                            <div class="mb-3">
                                <label for="person1" class="form-label text-center w-100">Pilih Anggota Keluarga 1:</label>
                                <div class="d-flex justify-content-center">
                                    <select name="name1" id="person1" class="form-control" required>
                                        <option value="" style="color: gray;">-- Pilih --</option>
                                        @foreach ($anggota_keluarga as $trah)
                                            <option value="{{ $trah->nama }}" style="color: black;"
                                                {{ old('name1', $name1 ?? '') == $trah->nama ? 'selected' : '' }}>
                                                {{ $trah->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-3">
                                <label for="person2" class="form-label text-center w-100">Pilih Anggota Keluarga 2:</label>
                                <select name="name2" id="person2" class="form-control" required>
                                    <option value="" style="color: gray;">-- Pilih --</option>
                                    @foreach ($anggota_keluarga as $trah)
                                        <option value="{{ $trah->nama }}"
                                            {{ old('name2', $name2 ?? '') == $trah->nama ? 'selected' : '' }}>
                                            {{ $trah->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Cari hubungannya yuk</button>
                    </div>
                </div>
            </form>

            @if (isset($relationshipDetails) && isset($relationshipDetailsReversed))
                <div class="row" id="relationship-details">
                    <h4 class="text-center text-lg font-semibold mb-5 mt-5">Hasil Perbandingan</h4>

                    <!-- Kolom hubungan anggota 1 -->
                    <div class="col-md-6">
                        <div class="bg-white shadow-md p-5 rounded-md mt-3">
                            @if (is_array($relationshipDetails))
                                <div class="bg-primary text-white text-center rounded-pill p-3 mb-3">
                                    {{ $relationshipDetails['relation'] }}
                                </div>
                                @if (!empty($relationshipDetails['detailedPath']))
                                    <div class="bg-[#FEF3C7] text-gray-800 p-3 rounded-md mb-3">
                                        <strong class="flex justify-center mb-3">Jalur Hubungan Keluarga:</strong>
                                        <ul class="list-group mt-2">
                                            @foreach ($relationshipDetails['detailedPath'] as $detail)
                                                <li class="list-group-item">{{ $detail }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif(isset($path) && count($path))
                                    <div class="bg-[#FEF3C7] text-gray-800 p-3 rounded-md mb-3">
                                        <strong>Tidak ada jalur yang ditemukan</strong>
                                        <p>
                                            {{ implode(' → ', array_map(fn($m) => $m->nama, $path)) }}
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">{{ $relationshipDetails }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom hubungan keluarga 2 -->
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="bg-white shadow-md p-5 rounded-md mt-3">
                            @if (is_array($relationshipDetailsReversed))
                                <div class="bg-primary text-white text-center rounded-pill p-3 mb-3">
                                    {{ $relationshipDetailsReversed['relation'] }}
                                </div>
                                @if (!empty($relationshipDetailsReversed['detailedPath']))
                                    <div class="bg-[#FEF3C7] text-gray-800 p-3 rounded-md mb-3">
                                        <strong class="flex justify-center mb-3">Jalur Hubungan Keluarga:</strong>
                                        <ul class="list-group mt-2">
                                            @foreach ($relationshipDetailsReversed['detailedPath'] as $detail)
                                                <li class="list-group-item">{{ $detail }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif(isset($pathRev) && count($pathRev))
                                    <div class="bg-[#FEF3C7] text-gray-800 p-3 rounded-md mb-3">
                                        <strong>Tidak ada jalur yang ditemukan</strong>
                                        <p>
                                            {{ implode(' → ', array_map(fn($m) => $m->nama, $pathRev)) }}
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">{{ $relationshipDetailsReversed }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-danger rounded-pill" onclick="resetForm();">
                    <i class="fas fa-undo"></i>
                </button>
            </div>

            @if (isset($relationshipDetails) && isset($relationshipDetailsReversed))
                <script>
                    window.onload = () => {
                        document.getElementById('relationship-details')?.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                </script>
            @endif

            <script>
                function resetForm() {
                    document.querySelector("form").reset();
                    document.querySelector("#person1").selectedIndex = 0;
                    document.querySelector("#person2").selectedIndex = 0;
                    document.getElementById("relationship-details").innerHTML = ''; // Clear results
                }
            </script>
        </div>
    </div>
@endsection
