@php
    $isMenu = false;
    $navbarHideToggle = false;
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Keluarga')

@section('content')
    <div class="card w-100 mb-5">
        <h5 class="card-header d-flex flex-column fw-bold">
            Family Name
            <small class="fw-light">Family description goes here</small>
        </h5>
    </div>

    <div class="nav-align-top">
        <ul class="nav nav-pills mb-4 nav-fill bg-white p-2" role="tablist">
            <li class="nav-item mb-1 mb-sm-0">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                    data-bs-target="#tab-keluarga" aria-controls="tab-keluarga" aria-selected="true">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-person me-2"></i>Data Keluarga
                    </span>
                    <i class="fa-solid fa-person icon-sm d-sm-none"></i>
                </button>
            </li>
            <li class="nav-item mb-1 mb-sm-0">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                    data-bs-target="#tab-pohon-keluarga" aria-controls="tab-pohon-keluarga" aria-selected="false">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-sitemap me-2"></i>Pohon Keluarga
                    </span>
                    <i class="fa-solid fa-sitemap icon-sm d-sm-none"></i>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-comparasi"
                    aria-controls="tab-comparasi" aria-selected="false">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-link me-2"></i>Hubungan
                    </span>
                    <i class="fa-solid fa-link icon-sm d-sm-none"></i>
                </button>
            </li>
        </ul>

        <div class="card">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-keluarga" role="tabpanel">
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#tab-anggota-keluarga" aria-controls="tab-anggota-keluarga"
                                    aria-selected="true">
                                    Anggota Keluarga
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#tab-pasangan-anggota-keluarga"
                                    aria-controls="tab-pasangan-anggota-keluarga" aria-selected="false">
                                    Pasangan Anggota Keluarga
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-anggota-keluarga" role="tabpanel">
                                <!-- Content for Anggota Keluarga tab -->
                                <div class="p-3">
                                    <p>Daftar anggota keluarga akan ditampilkan di sini</p>
                                    <!-- You can add your table or list of family members here -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-pasangan-anggota-keluarga" role="tabpanel">
                                <!-- Content for Pasangan Anggota Keluarga tab -->
                                <div class="p-3">
                                    <p>Daftar pasangan anggota keluarga akan ditampilkan di sini</p>
                                    <!-- You can add your table or list of partners here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-pohon-keluarga" role="tabpanel">
                    <p>tab pohon keluarga</p>
                </div>
                <div class="tab-pane fade" id="tab-comparasi" role="tabpanel">
                    <p>tab hubungan keluarga</p>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
