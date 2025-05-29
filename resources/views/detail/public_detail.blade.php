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

    <div class="modal-group">
        <div class="modal fade" id="familyModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Tambah Anggota Keluarga</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('anggota.keluarga.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            @if ($trah->id)
                                <input type="hidden" name="tree_id" value="{{ $trah->id }}">
                            @endif
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="nama_anggota_keluarga" class="form-label">Nama</label>
                                    <input type="text" id="nama_anggota_keluarga" name="nama_anggota_keluarga"
                                        class="form-control" placeholder="Nama Lengkap" required>
                                </div>
                                <div class="col mb-4">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control">
                                </div>
                                <div class="col mb-4">
                                    <label for="urutan" class="form-label">Urutan</label>
                                    <select id="urutan" name="urutan" class="form-select" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="parent1_id" class="form-label">Orang Tua 1</label>
                                    <select id="parent1_id" name="parent1_id" class="form-select" onchange="loadPartners()">
                                        <option value="">Pilih Orang Tua</option>
                                        @foreach ($anggota_keluarga as $member)
                                            <option value="{{ $member->id }}"
                                                {{ old('parent1_id') == $member->id ? 'selected' : '' }}>
                                                {{ $member->nama }}
                                                @if ($member->jenis_kelamin === 'Laki-Laki')
                                                    (Pak)
                                                @else
                                                    (Ibu)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col mb-4">
                                    <label for="parent2_id" class="form-label">Orang Tua 2 (Pasangan)</label>
                                    <select id="parent2_id" name="parent2_id" class="form-select" disabled>
                                        <option value="">Pilih Pasangan</option>
                                        <!-- Partners will be loaded via JavaScript -->
                                    </select>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="status_kehidupan" class="form-label">Status Kehidupan</label>
                                    <select id="status_kehidupan" name="status_kehidupan" class="form-select" required>
                                        <option value="Hidup">Hidup</option>
                                        <option value="Wafat">Wafat</option>
                                    </select>
                                </div>
                                <div class="col mb-0">
                                    <label for="tanggal_kematian" class="form-label">Tanggal Kematian</label>
                                    <input type="date" id="tanggal_kematian" class="form-control"
                                        name="tanggal_kematian">
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" required></textarea>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <div class="mb-3">
                                        <label for="keluarga_image_link" class="form-label">Link (Media Sosial, Foto,
                                            Drive)</label>
                                        <img src="" class="img-thumbnail image-preview mb-3"
                                            style="display: none; max-width: 100px; max-height: 100px; object-fit: cover; aspect-ratio: 1/1; border-radius: 10px;">
                                        <input class="form-control" type="url" id="keluarga_image_link"
                                            name="keluarga_image_link" placeholder="https://example.com/image.jpg"
                                            onchange="previewImageFromLink()">
                                    </div>
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

        @foreach ($anggota_keluarga as $anggota)
            <div class="modal fade" id="editMemberModal{{ $anggota->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel3">Edit Anggota Keluarga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('anggota.keluarga.update', $anggota->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <input type="hidden" name="tree_id" value="{{ $anggota->tree_id }}">
                                <div class="row">
                                    <div class="col mb-4">
                                        <label for="nama_anggota_keluarga_edit" class="form-label">Nama</label>
                                        <input type="text" id="nama_anggota_keluarga_edit"
                                            name="nama_anggota_keluarga_edit" class="form-control"
                                            placeholder="Nama Lengkap" value="{{ $anggota->nama }}" required>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col mb-4">
                                        <label for="jenis_kelamin_edit" class="form-label">Jenis Kelamin</label>
                                        <select id="jenis_kelamin_edit" name="jenis_kelamin_edit" class="form-select"
                                            required>
                                            <option value="Laki-laki"
                                                {{ $anggota->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="Perempuan"
                                                {{ $anggota->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col mb-0">
                                        <label for="tanggal_lahir_edit" class="form-label">Tanggal Lahir</label>
                                        <input type="date" id="tanggal_lahir_edit" name="tanggal_lahir_edit"
                                            class="form-control" value="{{ $anggota->tanggal_lahir }}">
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col mb-4">
                                        <label for="parent_id_edit" class="form-label">Orang Tua</label>
                                        <select id="parent_id_edit" name="parent_id_edit" class="form-select">
                                            <option value="">Pilih Orang Tua</option>
                                            @foreach ($existingMembers as $member)
                                                <option value="{{ $member->id }}"
                                                    {{ $anggota->parent_id == $member->id || old('parent_id') == $member->id ? 'selected' : '' }}>
                                                    {{ $member->nama }}
                                                    @if ($member->jenis_kelamin === 'Laki-Laki')
                                                        (Pak)
                                                    @else
                                                        (Ibu)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col mb-0">
                                        <label for="urutan_edit" class="form-label">Urutan</label>
                                        <select id="urutan_edit" name="urutan_edit" class="form-select" required>
                                            @for ($i = 1; $i <= 14; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $anggota->urutan == $i ? 'selected' : '' }}>{{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col mb-4">
                                        <label for="status_kehidupan_edit" class="form-label">Status Kehidupan</label>
                                        <select id="status_kehidupan_edit" name="status_kehidupan_edit"
                                            class="form-select" required>
                                            <option value="Hidup"
                                                {{ $anggota->status_kehidupan == 'Hidup' ? 'selected' : '' }}>Hidup
                                            </option>
                                            <option value="Wafat"
                                                {{ $anggota->status_kehidupan == 'Wafat' ? 'selected' : '' }}>Wafat
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col mb-0">
                                        <label for="tanggal_kematian_edit" class="form-label">Tanggal Kematian</label>
                                        <input type="date" id="tanggal_kematian_edit" class="form-control"
                                            name="tanggal_kematian_edit">
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col mb-4">
                                        <label for="alamat_edit" class="form-label">Alamat</label>
                                        <textarea class="form-control" name="alamat_edit" required>{{ $anggota->alamat }}</textarea>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col mb-4">
                                        <div class="mb-3">
                                            <label for="keluarga_image_edit" class="form-label">Upload Avatar</label>
                                            <img class="img-thumbnail edit-image-preview mb-3"
                                                style="max-width: 100px; max-height: 100px; object-fit: cover; aspect-ratio: 1/1; border-radius: 10px; 
                                                @if ($anggota->photo == null) display: none; @endif"
                                                src="{{ asset('storage/' . $anggota->photo) }}">
                                            <input class="form-control edit-file-uploader" type="file"
                                                id="keluarga_image_edit" name="keluarga_image_edit" accept="image/*"
                                                onchange="edit_upload()">
                                        </div>
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

            <div class="modal fade" id="deleteMemberModal{{ $anggota->id }}" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content"> <!-- Ubah dari <form> ke <div> untuk wrapper -->
                        <div class="modal-header text-center">
                            <h5 class="modal-title" id="backDropModalTitle">Delete This Member?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body justify-content-center text-center">
                            <i class="fa-solid fa-triangle-exclamation fa-beat"
                                style="color: #FF0000; font-size: 100px"></i>
                            <span class="d-block mt-5">kamu Yakin Ingin Mengahapus Anggota Keluarga Ini? Data yang terhapus
                                tidak dapat dipulihkan</span>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form method="POST" action="{{ route('anggota.keluarga.delete', $anggota->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="modal fade" id="addPartnerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Tambah Pasangan Keluarga</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pasangan.anggota.keluarga.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="tree_id" value="{{ $trahs->id }}">
                            <div class="row">
                                <div class="col mb-4">
                                    <label for="nama_pasangan_anggota_keluarga" class="form-label">Nama <span
                                            style="color: red">*</span></label>
                                    <input type="text" id="nama_pasangan_anggota_keluarga"
                                        name="nama_pasangan_anggota_keluarga" class="form-control"
                                        placeholder="Nama Lengkap" required>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span
                                            style="color: red">*</span></label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col mb-0">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control">
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="partner_id" class="form-label">Pasangan Dari <span
                                            style="color: red">*</span></label>
                                    <select id="partner_id" name="partner_id" class="form-select">
                                        <option value="">Pilih Anggota Keluarga</option>
                                        @foreach ($existingMembers as $member)
                                            <option value="{{ $member->id }}">
                                                {{ $member->nama }}
                                                @if ($member->jenis_kelamin === 'Laki-Laki')
                                                    (Tn)
                                                @else
                                                    (Ny)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mb-0">
                                    <label for="urutan" class="form-label">Urutan</label>
                                    <select id="urutan" name="urutan" class="form-select" required>
                                        @for ($i = 1; $i <= 14; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="status_kehidupan" class="form-label">Status Kehidupan</label>
                                    <select id="status_kehidupan" name="status_kehidupan" class="form-select" required>
                                        <option value="Hidup" selected>Hidup</option>
                                        <option value="Wafat">Wafat</option>
                                    </select>
                                </div>
                                <div class="col mb-0">
                                    <label for="tanggal_kematian" class="form-label">Tanggal Kematian</label>
                                    <input type="date" id="tanggal_kematian" name="tanggal_kematian"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-4">
                                    <div class="mb-3">
                                        <label for="partner_image" class="form-label">Upload Foto</label>
                                        <img src="" class="img-thumbnail image-preview mb-3"
                                            style="display: none; max-width: 100px; max-height: 100px;">
                                        <input class="form-control" type="file" id="partner_image"
                                            name="partner_image" accept="image/*" onchange="previewImage(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach ($anggota_keluarga as $anggota)
            @foreach ($anggota->partners as $partner)
                <div class="modal fade" id="editPartnerMemberModal{{ $partner->id }}" tabindex="-1"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel3">Edit Pasangan Keluarga</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('pasangan.anggota.keluarga.update', $partner->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <input type="hidden" name="tree_id" value="{{ $trah->id }}">
                                    <div class="row">
                                        <div class="col mb-4">
                                            <label for="nama_pasangan_edit" class="form-label">Nama <span
                                                    style="color: red">*</span></label>
                                            <input type="text" id="nama_pasangan_edit" name="nama_pasangan_edit"
                                                class="form-control" placeholder="Nama Lengkap"
                                                value="{{ $partner->nama }}" required>
                                        </div>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col mb-4">
                                            <label for="jenis_kelamin_edit" class="form-label">Jenis Kelamin <span
                                                    style="color: red">*</span></label>
                                            <select id="jenis_kelamin_edit" name="jenis_kelamin_edit" class="form-select"
                                                required>
                                                <option value="Laki-laki"
                                                    {{ $partner->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                                    Laki-laki</option>
                                                <option value="Perempuan"
                                                    {{ $partner->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col mb-0">
                                            <label for="tanggal_lahir_edit" class="form-label">Tanggal Lahir</label>
                                            <input type="date" id="tanggal_lahir_edit" name="tanggal_lahir_edit"
                                                class="form-control"
                                                value="{{ $partner->tanggal_lahir ? \Carbon\Carbon::parse($partner->tanggal_lahir)->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col mb-4">
                                            <label for="partner_id_edit" class="form-label">Pasangan Dari <span
                                                    style="color: red">*</span></label>
                                            <select id="partner_id_edit" name="partner_id_edit" class="form-select"
                                                required>
                                                @foreach ($existingMembers as $member)
                                                    <option value="{{ $member->id }}"
                                                        {{ $partner->anggota_keluarga_id == $member->id ? 'selected' : '' }}>
                                                        {{ $member->nama }}
                                                        @if ($member->jenis_kelamin === 'Laki-Laki')
                                                            (Tn)
                                                        @else
                                                            (Ny)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col mb-0">
                                            <label for="urutan_edit" class="form-label">Urutan</label>
                                            <select id="urutan_edit" name="urutan_edit" class="form-select" required>
                                                @for ($i = 1; $i <= 14; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ $partner->urutan == $i ? 'selected' : '' }}>{{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col mb-4">
                                            <label for="status_kehidupan_edit" class="form-label">Status Kehidupan</label>
                                            <select id="status_kehidupan_edit" name="status_kehidupan_edit"
                                                class="form-select" required>
                                                <option value="Hidup"
                                                    {{ $partner->status_kehidupan == 'Hidup' ? 'selected' : '' }}>Hidup
                                                </option>
                                                <option value="Wafat"
                                                    {{ $partner->status_kehidupan == 'Wafat' ? 'selected' : '' }}>Wafat
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col mb-0">
                                            <label for="tanggal_kematian_edit" class="form-label">Tanggal Kematian</label>
                                            <input type="date" id="tanggal_kematian_edit" name="tanggal_kematian_edit"
                                                class="form-control"
                                                value="{{ $partner->tanggal_kematian ? \Carbon\Carbon::parse($partner->tanggal_kematian)->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>
                                    {{-- <div class="row g-4">
                                <div class="col mb-4">
                                    <label for="alamat_edit" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat_edit" name="alamat_edit" required>{{ $partner->alamat }}</textarea>
                                </div>
                            </div> --}}
                                    <div class="row g-4">
                                        <div class="col mb-4">
                                            <div class="mb-3">
                                                <label for="foto_pasangan_edit" class="form-label">Upload Foto</label>
                                                @if ($partner->photo)
                                                    <img src="{{ asset('storage/' . $partner->photo) }}"
                                                        class="img-thumbnail image-preview mb-3"
                                                        style="max-width: 100px; max-height: 100px;">
                                                @else
                                                    <img src="" class="img-thumbnail image-preview mb-3"
                                                        style="display: none; max-width: 100px; max-height: 100px;">
                                                @endif
                                                <input class="form-control" type="file" id="foto_pasangan_edit"
                                                    name="foto_pasangan_edit" accept="image/*"
                                                    onchange="previewImage(this)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

    </div>

    <div class="card mb-3">
        @if ($trahs->trah_name)
            <h5 class="card-header d-flex flex-column fw-bold">
                {{ $trahs->trah_name }}
                @if ($trahs->description)
                    <small class="fw-light">{{ $trahs->description }}</small>
                @endif
            </h5>
        @endif
    </div>

    <div class="nav-align-top">
        <ul class="nav nav-pills mb-4 nav-fill bg-white p-2" role="tablist">
            <li class="nav-item mb-1 mb-sm-0">
                <button type="button" class="nav-link {{ !request()->has('compare') ? 'active' : '' }}" role="tab"
                    data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home"
                    aria-controls="navs-pills-justified-home"
                    aria-selected="{{ !request()->has('compare') ? 'true' : 'false' }}">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-person me-2"></i>Data Keluarga
                    </span>
                    <i class="fa-solid fa-person icon-sm d-sm-none"></i>
                </button>
            </li>
            <li class="nav-item mb-1 mb-sm-0">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                    data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile"
                    aria-selected="false">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-sitemap me-2"></i>Pohon Keluarga
                    </span>
                    <i class="fa-solid fa-sitemap icon-sm d-sm-none"></i>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link {{ request()->has('compare') ? 'active' : '' }}" role="tab"
                    data-bs-toggle="tab" data-bs-target="#navs-pills-justified-messages"
                    aria-controls="navs-pills-justified-messages"
                    aria-selected="{{ request()->has('compare') ? 'true' : 'false' }}">
                    <span class="d-none d-sm-inline-flex align-items-center"><i
                            class="fa-solid fa-link me-2"></i>Hubungan</span>
                    <i class="fa-solid fa-link icon-sm d-sm-none"></i>
                </button>
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="tab-content">
            <div class="tab-pane fade {{ !request()->has('compare') ? 'show active' : '' }}"
                id="navs-pills-justified-home" role="tabpanel">
                <div class="">
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-align-home" aria-controls="navs-top-align-home"
                                    aria-selected="true">
                                    Anggota Keluarga
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-align-profile" aria-controls="navs-top-align-profile"
                                    aria-selected="false">
                                    Pasangan Anggota Keluarga
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-top-align-home" role="tabpanel">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal"
                                        data-bs-target="#familyModal">
                                        Tambah Anggota Keluarga
                                    </button>

                                    <div class="">
                                        <div class="card-body pt-0">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="dataTables_length">
                                                        <label>
                                                            Menampilkan
                                                            <select id="entriesPerPage" class="form-select form-select-sm"
                                                                style="width: 80px; display: inline-block;">
                                                                <option value="5" selected>5</option>
                                                                <option value="10">10</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select> data
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="dataTables_filter">
                                                        <label class="float-end">
                                                            Search:
                                                            <input type="search" id="searchInput"
                                                                class="form-control form-control-sm"
                                                                style="width: 200px; display: inline-block;"
                                                                placeholder="Type to search...">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Table -->
                                            <div class="table-responsive md:overflow-auto">
                                                <table
                                                    class="table table-responsive-lg table-bordered table-striped dataTable no-footer"
                                                    id="datatables" aria-describedby="datatables_info">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="text-center sorting col-no" scope="col"
                                                                tabindex="0" aria-controls="datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Nomor: activate to sort column ascending"
                                                                style="width: 49.7px;">No
                                                            </th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Nama: activate to sort column ascending"
                                                                style="width: 65px;">Nama
                                                                Lengkap</th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Pengusul: activate to sort column ascending"
                                                                style="width: 64px;">
                                                                Jenis Kelamin</th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Penerima: activate to sort column ascending"
                                                                style="width: 40px;">
                                                                Tanggal lahir</th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Penerima: activate to sort column ascending"
                                                                style="width: 67px;">
                                                                Status Kehidupan</th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Aksi: activate to sort column ascending"
                                                                style="width: 130px;">Aksi
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($anggota_keluarga as $anggota)
                                                            <tr class="odd">
                                                                <td class="text-center col-no" style="overflow: hidden;">
                                                                    {{ $loop->iteration }}
                                                                </td>
                                                                <td class="" style="overflow: hidden">
                                                                    <div class="ellipsis text-center">
                                                                        {{ $anggota->nama }}
                                                                    </div>
                                                                </td>
                                                                <td class="" style="overflow: hidden">
                                                                    <div class="ellipsis text-center">
                                                                        {{ $anggota->jenis_kelamin }}
                                                                    </div>
                                                                </td>
                                                                <td class="" style="overflow: hidden">
                                                                    <div class="ellipsis text-center">
                                                                        {{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d-F-Y') : 'belum diketahui' }}
                                                                    </div>
                                                                </td>
                                                                <td class="" style="overflow: hidden">
                                                                    <div class="ellipsis text-center">
                                                                        <span
                                                                            class="badge @if ($anggota->status_kehidupan == 'Hidup') text-bg-primary @else text-bg-danger @endif">
                                                                            {{ $anggota->status_kehidupan }}
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="justify-content-center text-center"
                                                                    style="overflow: hidden">
                                                                    <a class="badge bg-label-warning m-1 py-1"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editMemberModal{{ $anggota->id }}">
                                                                        <i class="fa-solid fa-pencil"></i>
                                                                    </a>
                                                                    <a class="badge bg-label-danger m-1 py-1"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#deleteMemberModal{{ $anggota->id }}">
                                                                        <i class="fa-solid fa-xmark"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Table Footer -->
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="dataTables_info" id="datatables_info" role="status"
                                                        aria-live="polite">
                                                        Showing 1 to 10 of {{ count($anggota_keluarga) }} entries
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="dataTables_paginate paging_simple_numbers float-end">
                                                        <ul class="pagination" id="pagination">
                                                            <li class="paginate_button page-item previous disabled">
                                                                <a href="#" class="page-link">Previous</a>
                                                            </li>
                                                            <li class="paginate_button page-item active">
                                                                <a href="#" class="page-link">1</a>
                                                            </li>
                                                            <li class="paginate_button page-item next disabled">
                                                                <a href="#" class="page-link">Next</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-top-align-profile" role="tabpanel">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal"
                                        data-bs-target="#addPartnerModal">
                                        Tambah Pasangan
                                    </button>

                                    <div class="">
                                        <div class="card-body pt-0">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="dataTables_length">
                                                        <label>
                                                            Menampilkan
                                                            <select id="entriesPerPage" class="form-select form-select-sm"
                                                                style="width: 80px; display: inline-block;">
                                                                <option value="5">5</option>
                                                                <option value="10" selected>10</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select> data
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="dataTables_filter">
                                                        <label class="float-end">
                                                            Search:
                                                            <input type="search" id="searchInput"
                                                                class="form-control form-control-sm"
                                                                style="width: 200px; display: inline-block;"
                                                                placeholder="Type to search...">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Table -->
                                            <div class="table-responsive md:overflow-auto">
                                                <table
                                                    class="table table-responsive-lg table-bordered table-striped dataTable no-footer"
                                                    id="datatables" aria-describedby="datatables_info">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="text-center sorting col-no" scope="col"
                                                                tabindex="0" aria-controls="datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Nomor: activate to sort column ascending"
                                                                style="width: 49.7px;">No
                                                            </th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Nama: activate to sort column ascending"
                                                                style="width: 65px;">Nama
                                                                Lengkap</th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Pengusul: activate to sort column ascending"
                                                                style="width: 64px;">
                                                                Jenis Kelamin</th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Penerima: activate to sort column ascending"
                                                                style="width: 40px;">
                                                                Tanggal lahir</th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Penerima: activate to sort column ascending"
                                                                style="width: 67px;">
                                                                Status Kehidupan</th>
                                                            <th class="text-center sorting" scope="col" tabindex="0"
                                                                aria-controls="datatables" rowspan="1" colspan="1"
                                                                aria-label="Aksi: activate to sort column ascending"
                                                                style="width: 130px;">Aksi
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($anggota_keluarga as $anggota)
                                                            @foreach ($anggota->partners as $partner)
                                                                <tr class="odd">
                                                                    <td class="text-center col-no"
                                                                        style="overflow: hidden;">
                                                                        {{ $loop->iteration }}
                                                                    </td>
                                                                    <td class="" style="overflow: hidden">
                                                                        <div class="ellipsis text-center">
                                                                            {{ $partner->nama }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="" style="overflow: hidden">
                                                                        <div class="ellipsis text-center">
                                                                            {{ $partner->jenis_kelamin }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="" style="overflow: hidden">
                                                                        <div class="ellipsis text-center">
                                                                            {{ \Carbon\Carbon::parse($partner->tanggal_lahir)->translatedFormat('d-F-Y') }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="" style="overflow: hidden">
                                                                        <div class="ellipsis text-center">
                                                                            <span
                                                                                class="badge @if ($partner->status_kehidupan == 'Hidup') text-bg-primary @else text-bg-danger @endif">
                                                                                {{ $partner->status_kehidupan }}
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="justify-content-center text-center"
                                                                        style="overflow: hidden">
                                                                        <a class="badge bg-label-warning m-1 py-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editPartnerMemberModal{{ $partner->id }}">
                                                                            <i class="fa-solid fa-pencil"></i>
                                                                        </a>
                                                                        <a class="badge bg-label-danger m-1 py-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#deletePartnerMemberModal{{ $partner->id }}">
                                                                            <i class="fa-solid fa-xmark"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Table Footer -->
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="dataTables_info" id="datatables_info" role="status"
                                                        aria-live="polite">
                                                        Showing 1 to 10 of {{ count($anggota_keluarga) }} entries
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="dataTables_paginate paging_simple_numbers float-end">
                                                        <ul class="pagination" id="pagination">
                                                            <li class="paginate_button page-item previous disabled">
                                                                <a href="#" class="page-link">Previous</a>
                                                            </li>
                                                            <li class="paginate_button page-item active">
                                                                <a href="#" class="page-link">1</a>
                                                            </li>
                                                            <li class="paginate_button page-item next disabled">
                                                                <a href="#" class="page-link">Next</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade grab" id="navs-pills-justified-profile" role="tabpanel">
                <div class="container flex-wrap overflow-auto">
                    <div class="row justify-center">
                        <h1 class="fw-bold" style="color: #000 !important; text-transform: capitalize;">
                            {{ $trah->tree_name }}</h1>
                        <div class="tree">
                            <ul>
                                @foreach ($rootMember as $member)
                                    @include('partials.family-member', ['member' => $member])
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BAGIAN HUBUNGAN KELUARGA -->
            <div class="tab-pane fade {{ request()->has('compare') ? 'show active' : '' }}"
                id="navs-pills-justified-messages" role="tabpanel">
                {{-- ini page 3 --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-center w-100">Perbandingan Hubungan Keluarga</h5>

                        <form action="{{ route('keluarga.detail.public', $tree_id) }}" method="GET">
                            @csrf
                            <input type="hidden" name="tree_id" value="{{ $tree_id }}">
                            <input type="hidden" name="compare" value="true">

                            <div class="container px-4">
                                <div class="row gx-5">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="person1" class="form-label text-center w-100">Pilih Anggota
                                                Keluarga 1:</label>
                                            <div class="d-flex justify-content-center">
                                                <select name="name1" id="person1" class="form-control"
                                                    style="width: 450px;" required>
                                                    <option value="" style="color: gray;">-- Pilih --</option>
                                                    @foreach ($anggota_keluarga as $trah)
                                                        <option value="{{ $trah->nama }}" style="color: black;"
                                                            {{ old('name1', $person1->nama ?? '') == $trah->nama ? 'selected' : '' }}>
                                                            {{ $trah->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button type="submit" class="btn btn-primary rounded-circle"
                                            style="width: 50px; height: 50px; font-size: 24px;">VS</button>
                                    </div>


                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="person2" class="form-label text-center w-100">Pilih Anggota
                                                Keluarga 2:</label>
                                            <select name="name2" id="person2" class="form-control"
                                                style="width: 450px;" required>
                                                <option value="" style="color: gray;">-- Pilih --</option>
                                                @foreach ($anggota_keluarga as $trah)
                                                    <option value="{{ $trah->nama }}"
                                                        {{ old('name2', $person2->nama ?? '') == $trah->nama ? 'selected' : '' }}>
                                                        {{ $trah->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </form>


                        <!-- tabel hasil hubungan -->
                        @if (isset($relationshipDetails) && isset($relationshipDetailsReversed))
                            <div class="row" id="relationship-details">
                                <h3 class="text-center text-lg font-semibold mb-1 mt-1">Hasil Perbandingan</h3>

                                <!-- Kolom hubungan anggota 1 -->
                                <div class="col-md-6">
                                    <div class="bg-white shadow-md p-5 rounded-md mt-3">
                                        @if (isset($person1->photo) && $person1->photo)
                                            <div class="flex justify-center mb-3">
                                                <img src="{{ asset('storage/' . $person1->photo) }}"
                                                    alt="{{ $person1->name }}"
                                                    class="w-48 h-48 rounded-full object-cover border-4 border-green-700" />
                                            </div>
                                        @endif
                                        @if (is_array($relationshipDetails))
                                            <div
                                                class="bg-[#FEF3C7] flex justify-center text-gray-800 p-3 rounded-md mb-3">
                                                {{ $relationshipDetails['relation'] }}
                                            </div>
                                            @if (!empty($relationshipDetails['detailedPath']))
                                                <div class="bg-[#FEF3C7] text-gray-800 p-3 rounded-md mb-3">
                                                    <strong class="flex justify-center mb-3">Jalur Hubungan
                                                        Keluarga:</strong>
                                                    <ul class="list-group mt-2">
                                                        @foreach ($relationshipDetails['detailedPath'] as $detail)
                                                            <li class="list-group-item">{{ $detail }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @elseif(isset($path) && count($path))
                                                <div class="bg-[#FEF3C7] text-gray-800 p-3 rounded-md mb-3">
                                                    <strong>Jalur (BFS fallback):</strong>
                                                    <p>
                                                        {{ implode(' → ', array_map(fn($m) => $m->name, $path)) }}
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
                                        @if (isset($person2->photo) && $person2->photo)
                                            <div class="flex justify-center mb-3">
                                                <img src="{{ asset('storage/' . $person2->photo) }}"
                                                    alt="{{ $person2->name }}"
                                                    class="w-48 h-48 rounded-full object-cover border-4 border-green-700" />
                                            </div>
                                        @endif
                                        @if (is_array($relationshipDetailsReversed))
                                            <div
                                                class="bg-[#FEF3C7] flex justify-center text-gray-800 p-3 rounded-md mb-3">
                                                {{ $relationshipDetailsReversed['relation'] }}
                                            </div>
                                            @if (!empty($relationshipDetailsReversed['detailedPath']))
                                                <div class="bg-[#FEF3C7] text-gray-800 p-3 rounded-md mb-3">
                                                    <strong class="flex justify-center mb-3">Jalur Hubungan
                                                        Keluarga:</strong>
                                                    <ul class="list-group mt-2">
                                                        @foreach ($relationshipDetailsReversed['detailedPath'] as $detail)
                                                            <li class="list-group-item">{{ $detail }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @elseif(isset($path) && count($path))
                                                <div class="bg-[#FEF3C7] text-gray-800 p-3 rounded-md mb-3">
                                                    <strong>Jalur (BFS fallback):</strong>
                                                    <p>
                                                        {{ implode(' → ', array_map(fn($m) => $m->name, $path)) }}
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

                        <div class="d-grid gap-2 mt-4">
                            <button type="button" class="btn btn-danger" onclick="resetForm();">Reset</button>
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
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#relationshipComparisonForm').submit(function(e) {
                e.preventDefault();

                // Tampilkan loading
                $('#relationInfo').html(
                    '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i> Memproses...</div>'
                );
                $('#relationshipResult').show();

                $.ajax({
                    url: "{{ route('pasangan.anggota.keluarga.compare') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        // Tampilkan hasil hubungan
                        let html = `
                    <div class="relation-result">
                        <p><strong>${response.person1.name}</strong> → <strong>${response.person2.name}</strong>: 
                        ${response.relationship1to2.relation}</p>
                        <p><strong>${response.person2.name}</strong> → <strong>${response.person1.name}</strong>: 
                        ${response.relationship2to1.relation}</p>
                    </div>
                `;
                        $('#relationInfo').html(html);

                        // Tampilkan detail jalur hubungan jika ada
                        if (response.relationship1to2.detailedPath.length > 0) {
                            let detailsHtml =
                                '<h6>Detail Hubungan:</h6><ul class="list-group">';
                            response.relationship1to2.detailedPath.forEach(step => {
                                detailsHtml +=
                                    `<li class="list-group-item">${step}</li>`;
                            });
                            detailsHtml += '</ul>';
                            $('#relationDetails').html(detailsHtml);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat memproses';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#relationInfo').html(
                            `<div class="alert alert-danger">${errorMessage}</div>`);
                        $('#relationDetails').empty();
                    }
                });
            });
        });
    </script>

    <script>
        const partnersData = {
            @foreach ($anggota_keluarga as $member)
                "{{ $member->id }}": [
                    @foreach ($member->partners as $partner)
                        {
                            id: "{{ $partner->id }}",
                            nama: "{{ $partner->nama }}",
                            jenis_kelamin: "{{ $partner->jenis_kelamin }}"
                        },
                    @endforeach
                ],
            @endforeach
        };

        function loadPartners() {
            const parent1Select = document.getElementById('parent1_id');
            const parent2Select = document.getElementById('parent2_id');
            const selectedId = parent1Select.value;

            // Reset partner dropdown
            parent2Select.innerHTML = '<option value="">Pilih Pasangan</option>';
            parent2Select.disabled = true;

            if (!selectedId) return;

            // Enable and load partners if available
            const partners = partnersData[selectedId];
            if (partners && partners.length > 0) {
                parent2Select.disabled = false;

                partners.forEach(partner => {
                    const option = document.createElement('option');
                    option.value = partner.id;
                    option.textContent =
                        `${partner.nama} (${partner.jenis_kelamin === 'Laki-Laki' ? 'Pak' : 'Ibu'})`;

                    // Set selected if this was the old value
                    if ("{{ old('parent2_id') }}" == partner.id) {
                        option.selected = true;
                    }

                    parent2Select.appendChild(option);
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('parent1_id').value) {
                loadPartners();
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('datatables');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const entriesSelect = document.getElementById('entriesPerPage');
            const searchInput = document.getElementById('searchInput');
            const infoLabel = document.getElementById('datatables_info');
            const pagination = document.getElementById('pagination');

            let currentPage = 1;
            let entriesPerPage = parseInt(entriesSelect.value);
            let filteredRows = rows;

            // Fungsi untuk memfilter baris berdasarkan pencarian
            function filterRows() {
                const searchTerm = searchInput.value.toLowerCase();

                if (searchTerm === '') {
                    filteredRows = rows;
                } else {
                    filteredRows = rows.filter(row => {
                        const cells = row.querySelectorAll('td');
                        return Array.from(cells).some(cell =>
                            cell.textContent.toLowerCase().includes(searchTerm)
                        );
                    });
                }

                currentPage = 1;
                updateTable();
            }

            // Fungsi untuk memperbarui tampilan tabel
            function updateTable() {
                const startIndex = (currentPage - 1) * entriesPerPage;
                const endIndex = startIndex + entriesPerPage;
                const paginatedRows = filteredRows.slice(startIndex, endIndex);

                // Sembunyikan semua baris
                rows.forEach(row => row.style.display = 'none');

                // Tampilkan baris yang sesuai dengan halaman saat ini
                paginatedRows.forEach(row => row.style.display = '');

                // Update info label
                const totalRows = filteredRows.length;
                const startRow = totalRows > 0 ? startIndex + 1 : 0;
                const endRow = Math.min(endIndex, totalRows);

                infoLabel.textContent = `Showing ${startRow} to ${endRow} of ${totalRows} entries`;

                // Update pagination
                updatePagination(totalRows);
            }

            // Fungsi untuk memperbarui tampilan pagination
            function updatePagination(totalRows) {
                pagination.innerHTML = '';
                const totalPages = Math.ceil(totalRows / entriesPerPage);

                // Tombol Previous
                const prevLi = document.createElement('li');
                prevLi.className = `paginate_button page-item previous ${currentPage === 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = '<a href="#" class="page-link">Previous</a>';
                prevLi.addEventListener('click', e => {
                    e.preventDefault();
                    if (currentPage > 1) {
                        currentPage--;
                        updateTable();
                    }
                });
                pagination.appendChild(prevLi);

                // Tombol halaman
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                if (startPage > 1) {
                    const firstLi = document.createElement('li');
                    firstLi.className = 'paginate_button page-item';
                    firstLi.innerHTML = '<a href="#" class="page-link">1</a>';
                    firstLi.addEventListener('click', e => {
                        e.preventDefault();
                        currentPage = 1;
                        updateTable();
                    });
                    pagination.appendChild(firstLi);

                    if (startPage > 2) {
                        const ellipsisLi = document.createElement('li');
                        ellipsisLi.className = 'paginate_button page-item disabled';
                        ellipsisLi.innerHTML = '<a href="#" class="page-link">...</a>';
                        pagination.appendChild(ellipsisLi);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageLi = document.createElement('li');
                    pageLi.className = `paginate_button page-item ${i === currentPage ? 'active' : ''}`;
                    pageLi.innerHTML = `<a href="#" class="page-link">${i}</a>`;
                    pageLi.addEventListener('click', e => {
                        e.preventDefault();
                        currentPage = i;
                        updateTable();
                    });
                    pagination.appendChild(pageLi);
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        const ellipsisLi = document.createElement('li');
                        ellipsisLi.className = 'paginate_button page-item disabled';
                        ellipsisLi.innerHTML = '<a href="#" class="page-link">...</a>';
                        pagination.appendChild(ellipsisLi);
                    }

                    const lastLi = document.createElement('li');
                    lastLi.className = 'paginate_button page-item';
                    lastLi.innerHTML = `<a href="#" class="page-link">${totalPages}</a>`;
                    lastLi.addEventListener('click', e => {
                        e.preventDefault();
                        currentPage = totalPages;
                        updateTable();
                    });
                    pagination.appendChild(lastLi);
                }

                // Tombol Next
                const nextLi = document.createElement('li');
                nextLi.className = `paginate_button page-item next ${currentPage === totalPages ? 'disabled' : ''}`;
                nextLi.innerHTML = '<a href="#" class="page-link">Next</a>';
                nextLi.addEventListener('click', e => {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        currentPage++;
                        updateTable();
                    }
                });
                pagination.appendChild(nextLi);
            }

            // Event listeners
            entriesSelect.addEventListener('change', function() {
                entriesPerPage = parseInt(this.value);
                currentPage = 1;
                updateTable();
            });

            searchInput.addEventListener('input', function() {
                filterRows();
            });

            // Inisialisasi tabel
            updateTable();
        });

        function upload() {
            const fileUploadInput = document.querySelector('.file-uploader');
            const image = fileUploadInput.files[0];

            // Check if file was selected
            if (!image) {
                return alert('Please select an image file!');
            }

            // Validate file type
            if (!image.type.includes('image')) {
                return alert('Only images are allowed!');
            }

            // Validate file size (10MB)
            if (image.size > 10_000_000) {
                return alert('Maximum upload size is 10MB!');
            }

            const fileReader = new FileReader();
            fileReader.readAsDataURL(image);

            fileReader.onload = (fileReaderEvent) => {
                const profilePicture = document.querySelector('.image-preview');
                profilePicture.src = fileReaderEvent.target.result;
                profilePicture.style.display = 'block'; // Show the image
            }
        }

        function uploadpartner() {
            const fileUploadInput = document.querySelector('.file-uploader-partner');
            const image = fileUploadInput.files[0];

            // Check if file was selected
            if (!image) {
                return alert('Please select an image file!');
            }

            // Validate file type
            if (!image.type.includes('image')) {
                return alert('Only images are allowed!');
            }

            // Validate file size (10MB)
            if (image.size > 10_000_000) {
                return alert('Maximum upload size is 10MB!');
            }

            const fileReader = new FileReader();
            fileReader.readAsDataURL(image);

            fileReader.onload = (fileReaderEvent) => {
                const profilePicture = document.querySelector('.image-partner-preview');
                profilePicture.src = fileReaderEvent.target.result;
                profilePicture.style.display = 'block'; // Show the image
            }
        }

        function edit_upload() {
            const fileUploadInput = document.querySelector('.edit-file-uploader');
            const image = fileUploadInput.files[0];

            // Check if file was selected
            if (!image) {
                return alert('Please select an image file!');
            }

            // Validate file type
            if (!image.type.includes('image')) {
                return alert('Only images are allowed!');
            }

            // Validate file size (10MB)
            if (image.size > 10_000_000) {
                return alert('Maximum upload size is 10MB!');
            }

            const fileReader = new FileReader();
            fileReader.readAsDataURL(image);

            fileReader.onload = (fileReaderEvent) => {
                const profilePicture = document.querySelector('.edit-image-preview');
                profilePicture.src = fileReaderEvent.target.result;
                profilePicture.style.display = 'block'; // Show the image
            }
        }
    </script>
@endsection
