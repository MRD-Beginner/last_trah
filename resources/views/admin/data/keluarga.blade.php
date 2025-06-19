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
        font-size: 16px !important;
        vertical-align: middle;
    }

    td {
        font-size: 14px !important;
    }

    @media (max-width: 768px) {
        th {
            font-size: 12px !important;
            font-weight: 400 !important;
            min-width: 120px !important;
        }

        td {
            font-size: 12px !important;
        }

        .col-no {
            min-width: 60px !important;
        }

    }

    table {
        justify-content: center
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

    .high-z-index {
        z-index: 99999 !important;
    }
</style>

@section('content')

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        container: 'high-z-index' // Kelas custom untuk container
                    }
                });
            });
        </script>
    @endif

    <div class="card">
        <div class="modal-group">
            <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('keluarga.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Tambah Keluarga Baru</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-4">
                                        <label for="nama-trah" class="form-label">Nama Keluarga<span
                                                style="color: red">*</span></label>
                                        <input type="text" id="nama-trah" name="family_name" class="form-control"
                                            placeholder="Masukkan Nama Keluarga Anda" required>
                                    </div>
                                </div>
                                <div class="row g-4 mb-4">
                                    <div class="col mb-0">
                                        <label for="deskripsi-trah" class="form-label">Deskripsi</label>
                                        <input type="text" id="deskripsi-trah" name="description" class="form-control"
                                            placeholder="Deskripsi Singkat Keluarga">
                                    </div>
                                    <div class="col mb-0">
                                        <label for="created-by" class="form-label">Pemilik<span
                                                style="color: red">*</span></label>
                                        <input type="text" id="created-by" name="owner" class="form-control"
                                            value="{{ auth()->user()->name }}" required readonly>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col d-flex justify-content-start">
                                        <div class="form-check-reverse form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" />
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Keluarga
                                                Privat</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-x-4 password-section" style="display: none;">
                                    <!-- Tambahkan class dan sembunyikan awal -->
                                    <label class="form-label">Password<span style="color: red">*</span></label>
                                    <div class="input-group mb-4">
                                        <input type="password" id="passwordSection" name="password" class="form-control"
                                            aria-label="Password input">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @foreach ($trah as $Trah)
                {{-- Modal Delete --}}
                <div class="modal fade" id="deleteModal{{ $Trah->id }}" data-bs-backdrop="static" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content"> <!-- Ubah dari <form> ke <div> untuk wrapper -->
                            <div class="modal-header text-center">
                                <h5 class="modal-title" id="backDropModalTitle">Delete This Family</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body justify-content-center text-center">
                                <i class="fa-solid fa-triangle-exclamation fa-beat"
                                    style="color: #FF0000; font-size: 100px"></i>
                                <span class="d-block mt-5">kamu Yakin Ingin Mengahapus Keluarga Ini? Data yang terhapus
                                    tidak dapat dipulihkan</span>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <form method="POST" action="{{ route('keluarga.delete', $Trah->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $Trah->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('keluarga.update', $Trah->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">Edit Keluarga</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-4">
                                            <label for="nama-trah" class="form-label">Nama Keluarga</label>
                                            <input type="text" id="nama-trah" name="family_name" class="form-control"
                                                placeholder="Masukkan Nama Keluarga Anda" value="{{ $Trah->trah_name }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col mb-0">
                                            <label for="deskripsi-trah" class="form-label">Deskripsi</label>
                                            <input type="text" id="deskripsi-trah" name="description"
                                                class="form-control" placeholder="Deskripsi Singkat Keluarga"
                                                value="{{ $Trah->description }}">
                                        </div>
                                        <div class="col mb-0">
                                            <label for="created-by" class="form-label">Pemilik</label>
                                            <input type="text" id="created-by" name="owner" class="form-control"
                                                value="{{ auth()->user()->name }}" required readonly>
                                        </div>
                                    </div>
                                    <div class="row g-x-4">
                                        <label class="form-label">Password (*Kosongkan Jika Tidak Diubah)</label>
                                        <div class="input-group mb-4">
                                            <input type="password" id="password" name="password" class="form-control"
                                                aria-label="Password input">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            @foreach ($trah as $Trah)
                @if ($Trah->visibility === 'private')
                    <!-- Modal untuk setiap trah private -->
                    <div class="modal fade" id="passwordModal{{ $Trah->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('keluarga.verify.password', $Trah->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Akses Trah {{ $Trah->trah_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="password{{ $Trah->id }}" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password{{ $Trah->id }}"
                                                name="password" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Masuk</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <h5 class="card-header">Daftar Trah Keluarga</h5>
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#addModal">
                Tambah Keluarga
            </button>
            <div class="table-responsive md:overflow-auto">
                <table class="table table-responsive-lg table-bordered table-striped dataTable no-footer"
                    style="width: 100%;" id="datatables" aria-describedby="datatables_info">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center sorting col-no" scope="col" tabindex="0"
                                aria-controls="datatables" rowspan="1" colspan="1"
                                aria-label="Nomor: activate to sort column ascending" style="width: 49.7px;">No</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Nama: activate to sort column ascending"
                                style="width: 65px;">Nama Trah</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Pengusul: activate to sort column ascending"
                                style="width: 64px;">Pemilik</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Penerima: activate to sort column ascending"
                                style="width: 40px;">Tipe Keluarga</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Penerima: activate to sort column ascending"
                                style="width: 67px;">Deskripsi</th>
                            <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables"
                                rowspan="1" colspan="1" aria-label="Aksi: activate to sort column ascending"
                                style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trah as $Trah)
                            <tr class="odd">
                                <td class="text-center col-no" style="overflow: hidden;">
                                    <span class="mobile-font">{{ $loop->iteration }}</span>
                                </td>
                                <td class="" style="overflow: hidden">
                                    <div class="ellipsis text-center mobile-font">
                                        {{ $Trah->trah_name }}
                                    </div>
                                </td>
                                <td class="" style="overflow: hidden">
                                    <div class="ellipsis text-center mobile-font">
                                        {{ $Trah->created_by }}
                                    </div>
                                </td>
                                <td class="" style="overflow: hidden">
                                    <div class="ellipsis text-center mobile-font">
                                        {{ $Trah->visibility }}
                                    </div>
                                </td>
                                <td class="" style="overflow: hidden">
                                    <div class="ellipsis text-center mobile-font">
                                        {{ $Trah->description ?? 'Deskripsi Kosong' }}
                                    </div>
                                </td>
                                <td style="overflow: hidden;">
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-1 p-1">
                                        <!-- Baris Pertama Mobile (2 ikon) -->
                                        <div class="d-flex d-md-none justify-content-center gap-1 w-100">
                                            @if ($Trah->visibility === 'public')
                                                <a class="btn btn-sm btn-success rounded-circle p-2 mobile-icon"
                                                    href="{{ route('keluarga.detail.public', $Trah->id) }}"
                                                    data-bs-toggle="tooltip" data-bs-title="Detail">
                                                    <i class="fas fa-eye fa-md"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-success rounded-circle p-2 mobile-icon"
                                                    href="{{ route('keluarga.detail.private', $Trah->id) }}"
                                                    data-bs-toggle="tooltip" data-bs-title="Detail">
                                                    <i class="fas fa-eye fa-md"></i>
                                                </a>
                                            @endif

                                            <button class="btn btn-sm btn-warning rounded-circle p-2 mobile-icon"
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $Trah->id }}"
                                                data-bs-toggle="tooltip" data-bs-title="Edit">
                                                <i class="fas fa-edit fa-md"></i>
                                            </button>
                                        </div>

                                        <!-- Baris Kedua Mobile (2 ikon) -->
                                        <div class="d-flex d-md-none justify-content-center gap-1 w-100">
                                            <button class="btn btn-sm btn-danger rounded-circle p-2 mobile-icon"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $Trah->id }}"
                                                data-bs-toggle="tooltip" data-bs-title="Hapus">
                                                <i class="fas fa-trash fa-md"></i>
                                            </button>

                                            {{-- <a class="btn btn-sm btn-info rounded-pill px-2 py-1" href="#"
                                                onclick="copyShareLink(event, '{{ route('keluarga.detail.public', ['Trah' => $id]) }}')">
                                                Bagikan
                                            </a> --}}

                                            <script>
                                                function copyShareLink(event, url) {
                                                    event.preventDefault(); // Mencegah perilaku default link

                                                    // Buat elemen input sementara
                                                    const tempInput = document.createElement('input');
                                                    tempInput.value = url;
                                                    document.body.appendChild(tempInput);

                                                    // Pilih teks dan salin
                                                    tempInput.select();
                                                    document.execCommand('copy');

                                                    // Hapus elemen input
                                                    document.body.removeChild(tempInput);

                                                    // Beri feedback ke pengguna (opsional)
                                                    alert('Link berhasil disalin: ' + url);

                                                    // Jika Anda ingin menggunakan toast notification atau tooltip sebagai ganti alert
                                                    // Anda bisa menambahkan kode untuk menampilkan notifikasi di sini
                                                }
                                            </script>
                                        </div>

                                        <!-- Desktop Version -->
                                        <div class="d-none d-md-flex flex-wrap justify-content-center gap-2">
                                            @if ($Trah->visibility === 'public')
                                                <a class="btn btn-sm btn-success rounded-pill px-2 py-1"
                                                    href="{{ route('keluarga.detail.public', $Trah->id) }}">
                                                    Detail
                                                </a>
                                            @else
                                                {{-- <a class="btn btn-sm btn-success rounded-pill px-2 py-1"
                                                    href="{{ route('keluarga.detail.private', $Trah->id) }}">
                                                    Detail
                                                </a> --}}
                                                <button class="btn btn-sm btn-success rounded-pill px-2 py-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#passwordModal{{ $Trah->id }}"
                                                    data-trah-id="{{ $Trah->id }}">
                                                    Detail
                                                </button>
                                            @endif

                                            <button class="btn btn-sm btn-warning rounded-pill px-2 py-1"
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $Trah->id }}">
                                                Edit
                                            </button>

                                            <button class="btn btn-sm btn-danger rounded-pill px-2 py-1"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $Trah->id }}">
                                                Hapus
                                            </button>

                                            {{-- <a class="btn btn-sm btn-info rounded-pill px-2 py-1" href="#"
                                                onclick="copyShareLink(event, '{{ route('keluarga.detail.public', ['id' => $id]) }}')">
                                                Bagikan
                                            </a> --}}

                                            <script>
                                                function copyShareLink(event, url) {
                                                    event.preventDefault(); // Mencegah perilaku default link

                                                    // Buat elemen input sementara
                                                    const tempInput = document.createElement('input');
                                                    tempInput.value = url;
                                                    document.body.appendChild(tempInput);

                                                    // Pilih teks dan salin
                                                    tempInput.select();
                                                    document.execCommand('copy');

                                                    // Hapus elemen input
                                                    document.body.removeChild(tempInput);

                                                    // Beri feedback ke pengguna (opsional)
                                                    alert('Link berhasil disalin: ' + url);

                                                    // Jika Anda ingin menggunakan toast notification atau tooltip sebagai ganti alert
                                                    // Anda bisa menambahkan kode untuk menampilkan notifikasi di sini
                                                }
                                            </script>
                                        </div>
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
        const checkbox = document.getElementById('flexSwitchCheckDefault');
        const passwordSection = document.querySelector('.password-section'); // Ubah target ke div container

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                passwordSection.style.display = 'block';
            } else {
                passwordSection.style.display = 'none';
            }
        });
    </script>
@endsection
