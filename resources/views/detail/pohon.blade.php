@php
    $isMenu = false;
    $navbarHideToggle = false;
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Keluarga')

@section('page-script')

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            padding: 0px;
            margin: 0px;
            box-sizing: border-box;
        }

        body {
            align-items: center;
            font-family: 'Poppins', sans-serif;
            overflow: visible;
        }

        th {
            background-color: #8c8efd !important;
            border-color: #8c8efd !important;
            color: white !important;
            text-align: center !important;
            text-transform: capitalize !important;
        }

        .tree {
            height: auto;
            text-align: center;
            white-space: nowrap;
            /* Mencegah konten untuk turun ke baris berikutnya */
            width: 100%;
        }

        .tree ul {
            padding-top: 20px;
            position: relative;
            transition: .5s;
        }

        .tree li {
            display: inline-table;
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 10px;
            transition: .5s;
        }

        .tree li::before,
        .tree li::after {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            border-top: 1px solid #ccc;
            width: 51%;
            height: 10px;
        }

        .tree li::after {
            right: auto;
            left: 50%;
            border-left: 1px solid #ccc;
        }

        .tree li:only-child::after,
        .tree li:only-child::before {
            display: none;
        }

        .tree li:only-child {
            padding-top: 0;
        }

        .tree li:first-child::before,
        .tree li:last-child::after {
            border: 0 none;
        }

        .tree li:last-child::before {
            border-right: 1px solid #ccc;
            border-radius: 0 5px 0 0;
            -webkit-border-radius: 0 5px 0 0;
            -moz-border-radius: 0 5px 0 0;
        }

        .tree li:first-child::after {
            border-radius: 5px 0 0 0;
            -webkit-border-radius: 5px 0 0 0;
            -moz-border-radius: 5px 0 0 0;
        }

        .tree ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 1px solid #ccc;
            width: 0;
            height: 20px;
        }

        .tree li a {
            border: 1px solid rgb(130, 130, 130);
            padding: 10px;
            display: inline-grid;
            border-radius: 5px;
            text-decoration-line: none;
            border-radius: 5px;
            transition: .5s;
        }

        .tree li a img {
            width: 50px;
            height: 50px;
            margin-bottom: 10px !important;
            border-radius: 100px;
            margin: auto;
            object-fit: cover;
            aspect-ratio: 1/1;
        }

        .tree li a span {
            border: 1px solid #ccc;
            border-radius: 5px;
            color: #666;
            padding: 8px;
            font-size: 12px;
            text-transform: capitalize;
            letter-spacing: 1px;
            font-weight: 500;
        }

        /*Hover-Section*/
        .tree li a:hover,
        .tree li a:hover i,
        .tree li a:hover span,
        .tree li a:hover+ul li a {
            background: #c8e4f8;
            color: #000;
            border: 1px solid #94a0b4;
        }

        .tree li a:hover+ul li::after,
        .tree li a:hover+ul li::before,
        .tree li a:hover+ul::before,
        .tree li a:hover+ul ul::before {
            border-color: #94a0b4;
        }

        .wrap-text {
            white-space: normal;
            word-wrap: break-word;
            max-width: 200px;
        }

        .container {
            max-width: 100%;
            flex-wrap: wrap;
            padding: 16px;
        }

        .couple-container {
            justify-content: center;
            align-items: flex-start;
            display: flex;
            flex-direction: row;
            width: auto;
            /* background: #000; */
        }

        .partner,
        .main-member {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .main-member span {
            max-width: 100px;
        }

        .partner a {
            border: 1px solid #000;
            padding: 5px;
            border-radius: 5px;
            text-decoration: none;
            max-height: 100px !important;

        }

        .partner a img {
            width: 15px;
            height: 15px;
        }

        .partner a span {
            font-size: 8px !important;
            padding: 3px;
            margin-top: 5px;
            background-color: whitesmoke !important;
        }

        .truncate-name {
            position: relative;
            display: inline-block;
        }

        .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .high-z-index {
            z-index: 99999 !important;
        }

        @media (max-width: 576px) {
            .table-responsive {
                overflow-y: scroll !important;
            }
        }

        #navs-pills-justified-profile {
            overflow-x: auto !important;
            overflow-y: auto !important;
            touch-action: pan-x pan-y !important;
            cursor: grab !important;
            max-height: 500px !important;
        }

        .rip-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            /* background-color: rgba(0, 0, 0, 0.3); */
            border-radius: 50%;
            /* Match the image border-radius */
        }
    </style>
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
                <a href=""
                    class="nav-link {{ request()->is('detail/public/data/keluarga/pohon/*') ? 'active' : '' }}"
                    role="tab">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-sitemap me-2"></i>Pohon Keluarga
                    </span>
                    <i class="fa-solid fa-sitemap icon-sm d-sm-none"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('keluarga.detail.hubungan', $tree_id) }}"
                    class="nav-link {{ request()->is('detail/public/data/keluarga/hubungan/*') ? 'active' : '' }}"
                    role="tab">
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
            <div class="container flex-wrap overflow-auto">
                <div class="row justify-center">
                    {{-- <h1 class="fw-bold text-center mb-4" style="color: #000 !important; text-transform: capitalize;">
                        {{ $trah->trah_name }} Family Tree
                    </h1> --}}
                    <div class="tree justify-content-center">
                        <ul>
                            @foreach ($rootMember as $member)
                                @include('partials.family-member', ['member' => $member])
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tree {
            text-align: center;
            overflow-x: auto;
            padding: 20px 0;
        }

        .tree ul {
            padding-top: 20px;
            position: relative;
            transition: all 0.5s;
        }

        .tree li {
            display: inline-block;
            white-space: nowrap;
            vertical-align: top;
            margin: 0 -2px;
            padding: 20px 5px 0 5px;
            position: relative;
            transition: all 0.5s;
        }

        /* Tambahan styling untuk pohon keluarga */
    </style>
@endsection
