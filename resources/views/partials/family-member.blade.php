<li>
    <a href="#" data-bs-toggle="modal" data-bs-target="#MemberModal{{ $member->id }}" title="{{ $member->nama }}">
        <small
            style="
            position: absolute; 
            background-color: {{ $member->jenis_kelamin == 'Laki-Laki' ? '#3b82f6' : '#ec4899' }};
            color: white;
            font-weight: bold;
            padding: 3px 8px; 
            border-radius: 9999px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-size: 0.75rem;
            line-height: 1;
            min-width: 24px;
            text-align: center;
            z-index: 100;
            ">{{ $member->urutan }}
        </small>

        <div class="d-flex">
            @if ($member->jenis_kelamin == 'Laki-Laki')
                <img src="{{ asset('assets/img/placeholder/male.png') }}" alt="Foto Default Laki-laki"
                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
            @elseif ($member->jenis_kelamin == 'Perempuan')
                <img src="{{ asset('assets/img/placeholder/female.png') }}" alt="Foto Default Perempuan"
                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
            @endif
        </div>
        <span class="capitalize"
            style="display: inline-block; max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $member->nama }}</span>
    </a>

    <!-- Jika anggota memiliki anak, tampilkan daftar anak -->
    @if ($member->children->count() > 0)
        <ul>
            @foreach ($member->children->sortBy('urutan') as $child)
                @include('partials.family-member', ['member' => $child])
            @endforeach
        </ul>
    @endif

    <div class="modal fade" id="MemberModal{{ $member->id }}" tabindex="-1" aria-labelledby="memberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center pb-4">
                    <!-- Profile Image -->
                    @if ($member->jenis_kelamin == 'Laki-Laki')
                        <img src="{{ asset('assets/img/placeholder/male.png') }}" alt="Foto Default Laki-laki"
                            class="rounded-circle position-absolute top-0 start-50 translate-middle"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                    @elseif ($member->jenis_kelamin == 'Perempuan')
                        <img src="{{ asset('assets/img/placeholder/female.png') }}" alt="Foto Default Perempuan"
                            class="rounded-circle position-absolute top-0 start-50 translate-middle"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                    @endif

                    <!-- Tabs Navigation (using div instead of ul/li) -->
                    <div class="nav nav-tabs nav-justified mt-5 mb-3" role="tablist">
                        <div class="nav-item" role="presentation">
                            <button class="nav-link active" id="bio-tab" data-bs-toggle="tab"
                                data-bs-target="#bio-{{ $member->id }}" type="button" role="tab"
                                aria-controls="bio" aria-selected="true">
                                Biodata
                            </button>
                        </div>
                        <div class="nav-item" role="presentation">
                            <button class="nav-link" id="family-tab" data-bs-toggle="tab"
                                data-bs-target="#family-{{ $member->id }}" type="button" role="tab"
                                aria-controls="family" aria-selected="false">
                                Pasangan
                            </button>
                        </div>
                        <div class="nav-item" role="presentation">
                            <button class="nav-link" id="notes-tab" data-bs-toggle="tab"
                                data-bs-target="#notes-{{ $member->id }}" type="button" role="tab"
                                aria-controls="notes" aria-selected="false">
                                Anak
                            </button>
                        </div>
                    </div>

                    <!-- Tabs Content -->
                    <div class="tab-content text-start p-2">
                        <!-- Biodata Tab -->
                        <div class="tab-pane fade show active" id="bio-{{ $member->id }}" role="tabpanel"
                            aria-labelledby="bio-tab">
                            <h6 class="text-center mb-3">{{ $member->nama }}</h6>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar me-2"></i>
                                <span>Lahir:
                                    {{ \Carbon\Carbon::parse($member->tanggal_lahir)->format('d-m-Y') }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-gender-ambiguous me-2"></i>
                                <span>Jenis Kelamin: {{ $member->jenis_kelamin }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-gender-ambiguous me-2"></i>
                                <span>Anak Ke: {{ $member->urutan }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-gender-ambiguous me-2"></i>
                                @if ($member->parent)
                                <span>Anak Dari : </span> 
                                    <a href="#" class="border-none p-0 m-0 text-decoration-none bg-primary-hover" data-bs-toggle="modal"
                                        data-bs-target="#MemberModal{{ $member->parent->id }}">
                                         {{ $member->parent->nama }}
                                    </a>
                                @endif
                            </div>
                            @if ($member->photo)
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-image me-2"></i>
                                    <a href="{{ $member->photo }}" target="_blank" class="badge badge-primary text-black">Buka Tautan</a>
                                </div>
                            @endif
                        </div>

                        <!-- Family Tab -->
                        <div class="tab-pane fade" id="family-{{ $member->id }}" role="tabpanel"
                            aria-labelledby="family-tab">
                            @if ($member->partners->count() > 0)
                                <div class="mb-3">
                                    <h6 class="d-flex align-items-center">
                                        <i class="bi bi-heart me-2"></i>Pasangan
                                    </h6>
                                    <div>
                                        @foreach ($member->partners as $partner)
                                            <a href="#" class="text-decoration-none me-1" data-bs-toggle="modal"
                                                data-bs-target="#PartnerModal{{ $partner->id }}">
                                                {{ $partner->nama }}
                                            </a>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Notes Tab -->
                        <div class="tab-pane fade" id="notes-{{ $member->id }}" role="tabpanel"
                            aria-labelledby="notes-tab">
                            @if ($member->children->count() > 0)
                                <div>
                                    <h6 class="d-flex align-items-center">
                                        <i class="bi bi-people me-2"></i>Anak-anak
                                    </h6>
                                    <div>
                                        @foreach ($member->children as $child)
                                            <a href="#" class="text-decoration-none me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#MemberModal{{ $child->id }}">
                                                {{ $child->nama }}
                                            </a>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($member->partners->count() > 0)
        @foreach ($member->partners as $partner)
            <div class="modal fade" id="PartnerModal{{ $partner->id }}" tabindex="-1" aria-labelledby="partnerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center pb-4">
                            <!-- Profile Image -->
                            @if ($partner->jenis_kelamin == 'Laki-Laki')
                                <img src="{{ asset('assets/img/placeholder/male.png') }}" alt="Foto Default Laki-laki"
                                    class="rounded-circle position-absolute top-0 start-50 translate-middle"
                                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                            @elseif ($partner->jenis_kelamin == 'Perempuan')
                                <img src="{{ asset('assets/img/placeholder/female.png') }}" alt="Foto Default Perempuan"
                                    class="rounded-circle position-absolute top-0 start-50 translate-middle"
                                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                            @endif

                            <!-- Tabs Navigation -->
                            <div class="nav nav-tabs nav-justified mt-5 mb-3" role="tablist">
                                <div class="nav-item" role="presentation">
                                    <button class="nav-link active" id="partner-bio-tab" data-bs-toggle="tab"
                                        data-bs-target="#partner-bio-{{ $partner->id }}" type="button" role="tab"
                                        aria-controls="partner-bio" aria-selected="true">
                                        Biodata
                                    </button>
                                </div>
                                <div class="nav-item" role="presentation">
                                    <button class="nav-link" id="partner-family-tab" data-bs-toggle="tab"
                                        data-bs-target="#partner-family-{{ $partner->id }}" type="button" role="tab"
                                        aria-controls="partner-family" aria-selected="false">
                                        Pasangan
                                    </button>
                                </div>
                                <div class="nav-item" role="presentation">
                                    <button class="nav-link" id="partner-children-tab" data-bs-toggle="tab"
                                        data-bs-target="#partner-children-{{ $partner->id }}" type="button" role="tab"
                                        aria-controls="partner-children" aria-selected="false">
                                        Anak
                                    </button>
                                </div>
                            </div>

                            <!-- Tabs Content -->
                            <div class="tab-content text-start p-2">
                                <!-- Biodata Tab -->
                                <div class="tab-pane fade show active" id="partner-bio-{{ $partner->id }}" role="tabpanel"
                                    aria-labelledby="partner-bio-tab">
                                    <h6 class="text-center mb-3">{{ $partner->nama }}</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar me-2"></i>
                                        <span>Lahir: {{ \Carbon\Carbon::parse($partner->tanggal_lahir)->format('d-m-Y') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-gender-ambiguous me-2"></i>
                                        <span>Jenis Kelamin: {{ $partner->jenis_kelamin }}</span>
                                    </div>
                                    @if ($partner->photo)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-image me-2"></i>
                                            <a href="{{ $partner->photo }}" target="_blank" class="badge badge-primary text-black">Buka Tautan</a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Family Tab -->
                                <div class="tab-pane fade" id="partner-family-{{ $partner->id }}" role="tabpanel"
                                    aria-labelledby="partner-family-tab">
                                    <div class="mb-3">
                                        <h6 class="d-flex align-items-center">
                                            <i class="bi bi-heart me-2"></i>Pasangan
                                        </h6>
                                        <div>
                                            <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                                data-bs-target="#MemberModal{{ $member->id }}">
                                                {{ $member->nama }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Children Tab -->
                                <div class="tab-pane fade" id="partner-children-{{ $partner->id }}" role="tabpanel"
                                    aria-labelledby="partner-children-tab">
                                    @if ($member->children->count() > 0)
                                        <div>
                                            <h6 class="d-flex align-items-center">
                                                <i class="bi bi-people me-2"></i>Anak-anak
                                            </h6>
                                            <div>
                                                @foreach ($member->children as $child)
                                                    <a href="#" class="text-decoration-none me-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#MemberModal{{ $child->id }}">
                                                        {{ $child->nama }}
                                                    </a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</li>
