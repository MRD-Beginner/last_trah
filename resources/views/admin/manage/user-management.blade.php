@extends('layouts/contentNavbarLayout')

@section('title', 'Data Keluarga')

@section('page-script')
@endsection


<style>
    table {}

    th {
        background-color: #8c8efd !important;
        border-color: #8c8efd !important;
        color: white !important;
        text-align: center !important;
        text-transform: capitalize !important;
    }

    .col-no {
        max-width: 40px !important;
    }

    .ellipsis-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        text-overflow: ellipsis;
    }

    .ellipsis {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        text-overflow: ellipsis;
    }

    .ellipsis-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        text-overflow: ellipsis;
    }

    tbody {}
</style>

@section('content')
    <div class="card">
        <h5 class="card-header">Daftar User</h5>
        <div class="card-body">

            <div class="row g-6 mb-6">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">User</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">21,459</h4>
                                        <p class="text-success mb-0">(+29%)</p>
                                    </div>
                                    <small class="mb-0">Jumlah Users</small>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="icon-base bx bx-group icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Keluarga</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">4,567</h4>
                                        <p class="text-success mb-0">(+18%)</p>
                                    </div>
                                    <small class="mb-0">Jumlah Keluarga </small>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class="icon-base bx bx-user-plus icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Anggota Keluarga</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">19,860</h4>
                                        <p class="text-danger mb-0">(-14%)</p>
                                    </div>
                                    <small class="mb-0">Jumlah Anggota Keluarga</small>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="icon-base bx bx-user-check icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Admin</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">
                                          @if ($admincount )  
                                            {{ $admincount }} 
                                          @endif
                                        </h4>
                                    </div>
                                    <small class="mb-0">Jumlah Total Admin</small>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="icon-base bx bx-user-voice icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive md:overflow-auto">
                <table class="table table-responsive-lg table-bordered table-striped dataTable no-footer"
                    style="width: 100%;" id="datatables" aria-describedby="datatables_info">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center sorting col-no" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Nomor: activate to sort column ascending"
                                style="width: 49.7px;">No</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Nama: activate to sort column ascending"
                                style="width: 65px;">Username</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Penerima: activate to sort column ascending"
                                style="width: 40px;">Role</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Pengusul: activate to sort column ascending"
                                style="width: 64px;">Email</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Penerima: activate to sort column ascending"
                                style="width: 67px;">Terakhir Diupdate</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Aksi: activate to sort column ascending"
                                style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user as $User)
                            <tr class="odd">
                                <td class="text-center col-no" style="overflow: hidden;;">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="" style="overflow: hidden">
                                    <div class="ellipsis text-center">
                                        {{ $User->name }}
                                    </div>
                                </td>
                                <td class="" style="overflow: hidden">
                                    <div class="ellipsis text-center">
                                        {{ $User->role }}
                                    </div>
                                </td>
                                @php
                                    $visible = substr($User->email, 0, 5);
                                    $masked = str_repeat('*', max(0, strlen($User->email) - 5));
                                @endphp
                                <td class="" style="overflow: hidden; max-width: 100px !important;">
                                    <div class="text-ellipsis text-center">
                                        {{ $visible . $masked }}
                                    </div>
                                </td>
                                <td class="" style="overflow: hidden">
                                    <div class="ellipsis text-center">
                                        {{ $User->created_at->translatedFormat('d-F-Y') }}
                                    </div>
                                </td>
                                <td class="justify-content-center" style="overflow: hidden">
                                    <div class="ellipsis">
                                        <a class="badge bg-label-success m-1 py-1"
                                            href="{{ route('admin.dashboard') }}"><i
                                                class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                        <a class="badge bg-label-warning m-1 py-1" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $User->id }}"><i
                                                class="fa-solid fa-pencil"></i></a>
                                        <a class="badge bg-label-danger m-1 py-1" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $User->id }}"><i
                                                class="fa-solid fa-xmark"></i></a>
                                        <a class="badge bg-label-info m-1 py-1" href="{{ route('admin.dashboard') }}"><i
                                                class="fa-solid fa-link"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
