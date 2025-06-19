@php
    $isMenu = false;
    $navbarHideToggle = false;
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Data Keluarga')

@section('page-script')
@endsection


<style>

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
                                        <i class="icon-base bx bx-group bx-sm"></i>
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
                                        <i class="icon-base bx bx-user-plus bx-sm"></i>
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
                                        <i class="icon-base bx bx-user-check bx-sm"></i>
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
                                            @if ($admincount)
                                                {{ $admincount }}
                                            @endif
                                        </h4>
                                    </div>
                                    <small class="mb-0">Jumlah Total Admin</small>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="icon-base bx bx-user-voice bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="row g-3 align-items-center">
                    <!-- Entries Per Page -->
                    <div class="col-md-4 col-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label mb-0 me-2">Menampilkan</label>
                            <select id="entriesPerPage" class="form-select form-select-sm" style="width: 80px;">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="ms-2">data</span>
                        </div>
                    </div>

                    <!-- Role Filter -->
                    <div class="col-md-4 col-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label mb-0 me-2">Role</label>
                            <select id="filterRole" class="form-select form-select-sm" style="width: 120px;">
                                <option value="">Semua</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="search" id="searchInput" class="form-control" placeholder="Search...">
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('datatables');
        const rows = table.querySelectorAll('tbody tr');
        const entriesPerPage = document.getElementById('entriesPerPage');
        const filterRole = document.getElementById('filterRole');
        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
    
    // Variabel untuk pagination
    let currentPage = 1;
    let entries = parseInt(entriesPerPage.value);
    
    // Fungsi untuk memfilter data
    function filterData() {
    const roleValue = filterRole.value; // Jangan di lowercase dulu
    const searchValue = searchInput.value.toLowerCase();
    
    rows.forEach(row => {
        // Gunakan selektor yang lebih spesifik
        const roleCell = row.querySelector('td:nth-child(3) div.ellipsis.text-center');
        const role = roleCell ? roleCell.textContent.trim() : "";
        
        const nameCell = row.querySelector('td:nth-child(2) div.ellipsis.text-center');
        const name = nameCell ? nameCell.textContent.toLowerCase() : "";
        
        const emailCell = row.querySelector('td:nth-child(4) div.text-ellipsis.text-center');
        const email = emailCell ? emailCell.textContent.toLowerCase() : "";
        
        // Filter role (case sensitive jika diperlukan)
        const roleMatch = !roleValue || role.toLowerCase() === roleValue.toLowerCase();
        
        // Filter search
        const searchMatch = !searchValue || 
                          name.includes(searchValue) || 
                          email.includes(searchValue);
        
        row.style.display = (roleMatch && searchMatch) ? "" : "none";
    });
    
    updatePagination();
}
    
    // Fungsi untuk update pagination
    function updatePagination() {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        const totalPages = Math.ceil(visibleRows.length / entries);
        
        // Reset ke halaman 1 jika currentPage melebihi totalPages
        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }
        
        // Sembunyikan semua baris
        rows.forEach(row => row.style.display = 'none');
        
        // Tampilkan baris untuk halaman saat ini
        const start = (currentPage - 1) * entries;
        const end = start + entries;
        
        visibleRows.slice(start, end).forEach(row => {
            row.style.display = '';
        });
        
        // TODO: Tambahkan UI pagination di sini jika diperlukan
    }
    
    // Event listeners
    entriesPerPage.addEventListener('change', function() {
        entries = parseInt(this.value);
        currentPage = 1;
        filterData();
    });
    
    filterRole.addEventListener('change', function() {
        currentPage = 1;
        filterData();
    });
    
    searchInput.addEventListener('input', function() {
        currentPage = 1;
        filterData();
    });
    
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        currentPage = 1;
        filterData();
    });
    
    // Inisialisasi awal
    filterData();
});
    </script>
@endsection
