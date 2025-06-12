@php
    $isMenu = false;
    $navbarHideToggle = false;
@endphp


@extends('layouts/contentNavbarLayout')

@section('title', 'Data Keluarga')

@section('page-script')
@endsection
  <style>

    th{
      background-color: #8c8efd !important;
      border-color: #8c8efd !important;
      color: white !important;
      text-align: center !important;
      text-transform: capitalize !important;
      font-size: 16px !important;
      vertical-align: middle;
    }
    
    td{
      font-size: 14px !important;
    }

    @media (max-width: 768px) {
      th{
        font-size: 12px !important;
        font-weight: 400 !important;
        min-width: 120px !important;
      }

      td{
        font-size: 12px !important;
      }

      .col-no {
        min-width: 60px !important;
      }

    }

    table{
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

@if(session('success'))
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
      {{-- Modal Add --}}
      <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
              <form action="{{ route('user.keluarga.store') }}" method="POST">
                  @csrf
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel1">Tambah Keluarga Baru</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col mb-4">
                              <label for="nama-trah" class="form-label">Nama Keluarga</label>
                              <input type="text" id="nama-trah" name="family_name" class="form-control" placeholder="Masukkan Nama Keluarga Anda" required>
                          </div>
                      </div>
                      <div class="row g-4 mb-4">
                          <div class="col mb-0">
                              <label for="deskripsi-trah" class="form-label">Deskripsi</label>
                              <input type="text" id="deskripsi-trah" name="description" class="form-control" placeholder="Deskripsi Singkat Keluarga">
                          </div>
                          <div class="col mb-0">
                              <label for="created-by" class="form-label">Pemilik</label>
                              <input type="text" id="created-by" name="owner" class="form-control" value="{{ auth()->user()->name }}" required readonly>
                          </div>
                      </div>
                      <div class="row g-x-4">
                          <label class="form-label">Password Visibility</label>
                          <div class="input-group mb-4">
                              <input type="password" id="password" name="password" class="form-control" aria-label="Password input">
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
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
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body justify-content-center text-center">
              <i class="fa-solid fa-triangle-exclamation fa-beat" style="color: #FF0000; font-size: 100px"></i>
              <span class="d-block mt-5">kamu Yakin Ingin Mengahapus Keluarga Ini? Data yang terhapus tidak dapat dipulihkan</span>
            </div>
            <div class="modal-footer justify-content-center">
              <form method="POST" action="{{ route('user.keluarga.delete', $Trah->id) }}">
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
                  <form action="{{ route('user.keluarga.update', $Trah->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel1">Edit Keluarga</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col mb-4">
                              <label for="nama-trah" class="form-label">Nama Keluarga</label>
                              <input type="text" id="nama-trah" name="family_name" class="form-control" placeholder="Masukkan Nama Keluarga Anda" value="{{ $Trah->trah_name }}" required>
                          </div>
                      </div>
                      <div class="row g-4 mb-4">
                          <div class="col mb-0">
                              <label for="deskripsi-trah" class="form-label">Deskripsi</label>
                              <input type="text" id="deskripsi-trah" name="description" class="form-control" placeholder="Deskripsi Singkat Keluarga" value="{{ $Trah->description }}">
                          </div>
                          <div class="col mb-0">
                              <label for="created-by" class="form-label">Pemilik</label>
                              <input type="text" id="created-by" name="owner" class="form-control" value="{{ auth()->user()->name }}" required readonly>
                          </div>
                      </div>
                      <div class="row g-x-4">
                          <label class="form-label">Password (*Kosongkan Jika Tidak Diubah)</label>
                          <div class="input-group mb-4">
                              <input type="password" id="password" name="password" class="form-control" aria-label="Password input">
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
                  </form>
              </div>
          </div>
      </div>

      {{-- Modal Password --}}
      <div class="modal fade" id="passwordModal{{ $Trah->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <form action="{{ route('user.keluarga.check.pass', $Trah->id) }}" method="POST">
                      @csrf
                      <div class="modal-header">
                      <mil class="modal-title" id="exampleModalLabel1">Masukkan Password Keluarga</mil>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col mb-4">
                              <label for="nama-trah" class="form-label">Nama Keluarga</label>
                              <input type="text" id="nama-trah" name="family_name" class="form-control" placeholder="Masukkan Nama Keluarga Anda" value="{{ $Trah->trah_name }}" required>
                          </div>
                      </div>
                      <div class="row g-4 mb-4">
                          <div class="col mb-0">
                              <label for="deskripsi-trah" class="form-label">Deskripsi</label>
                              <input type="text" id="deskripsi-trah" name="description" class="form-control" placeholder="Deskripsi Singkat Keluarga" value="{{ $Trah->description }}">
                          </div>
                          <div class="col mb-0">
                              <label for="created-by" class="form-label">Pemilik</label>
                              <input type="text" id="created-by" name="owner" class="form-control" value="{{ auth()->user()->name }}" required readonly>
                          </div>
                      </div>
                      <div class="row g-x-4">
                          <label class="form-label">Password</label>
                          <div class="input-group mb-4">
                              <input type="password" id="password" name="password" class="form-control" aria-label="Password input" value="">
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
                  </form>
              </div>
          </div>
      </div>
      @endforeach
    </div>

    <h5 class="card-header">Daftar Trah Keluarga</h5>
    <div class="card-body">
      <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#addModal">
      Tambah Keluarga
      </button>
        <div class="table-responsive md:overflow-auto">
          <table class="table table-responsive-lg table-bordered table-striped dataTable no-footer" style="width: 100%;" id="datatables" aria-describedby="datatables_info">
            <thead class="table-dark">
                <tr>
                  <th class="text-center sorting col-no" scope="col" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="Nomor: activate to sort column ascending" style="width: 49.7px;">No</th>
                  <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="Nama: activate to sort column ascending" style="width: 65px;">Nama Trah</th>
                  <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="Pengusul: activate to sort column ascending" style="width: 64px;">Pemilik</th>
                  <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="Penerima: activate to sort column ascending" style="width: 40px;">Tipe Keluarga</th>
                  <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="Penerima: activate to sort column ascending" style="width: 67px;">Deskripsi</th>
                  <th class="text-center sorting" scope="col" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="Aksi: activate to sort column ascending" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($trah as $Trah )  
              <tr class="odd">  
                <td class="text-center col-no" style="overflow: hidden;;">
                    {{ $loop->iteration }}
                </td>
                <td class="" style="overflow: hidden">
                  <div class="ellipsis text-center">
                    {{ $Trah->trah_name }}
                  </div>
                </td>                    
                <td class="" style="overflow: hidden">
                  <div class="ellipsis text-center">
                    {{ $Trah->created_by }}
                  </div>
                </td>                    
                <td class="" style="overflow: hidden">
                  <div class="ellipsis text-center">
                    {{ $Trah->visibility }}
                  </div>
                </td>                    
                <td class="" style="overflow: hidden">
                  <div class="ellipsis text-center">
                    {{ $Trah->description ?? 'Deskripsi Kosong' }}
                  </div>
                </td>                    
                <td class="justify-content-center" style="overflow: hidden; ">
                  <div class="ellipsis" style="justify-content: center !important;">
                      @if($Trah->visibility === 'public')
                        <a class="badge bg-label-success m-1 py-1" href="{{ route('keluarga.detail.public', $Trah->id) }}">
                          {{-- <i class="fa-solid fa-arrow-up-right-from-square"></i> --}}
                          Detail
                        </a>
                      @else
                        <a class="badge bg-label-success m-1 py-1" data-bs-toggle="modal" data-bs-target="#passwordModal{{ $Trah->id }}">
                          {{-- <i class="fa-solid fa-arrow-up-right-from-square"></i> --}}
                          Detail
                        </a>
                      @endif
                      <a class="badge bg-label-warning m-1 py-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $Trah->id }}"><i class="fa-solid fa-pencil"></i></a>
                      <a class="badge bg-label-danger m-1 py-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $Trah->id }}"><i class="fa-solid fa-xmark"></i></a>
                      <a class="badge bg-label-info m-1 py-1" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-link"></i></a>
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
    document.getElementById('show-password').addEventListener('change', function() {
      const passwordField = document.getElementById('password');
      passwordField.type = this.checked ? 'text' : 'password';
    });    
  </script>
@endsection