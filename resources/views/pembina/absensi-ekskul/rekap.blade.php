@extends('layouts.pembina')

@section('title', 'Rekap Kehadiran')

@section('content')

    @if ($organisasi)
        <div class="mb-3">
            <a href="{{ route('pembina.absensi-ekskul') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>

        {{-- Filter Semester & Tahun Ajaran --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end" id="rekapFilterForm">
                    @if ($organisasiList->count() > 1)
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Organisasi / Ekskul</label>
                            <select id="filterOrgId" class="form-select">
                                @foreach ($organisasiList as $org)
                                    <option value="{{ $org->id }}"
                                        {{ $selectedOrgId == $org->id ? 'selected' : '' }}>
                                        {{ $org->nama_organisasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    @if (count($semesterList) > 0 || count($tahunAjaranList) > 0)
                        <div class="{{ $organisasiList->count() > 1 ? 'col-md-3' : 'col-md-4' }}">
                            <label class="form-label fw-semibold">Semester</label>
                            <select id="filterSemester" class="form-select">
                                <option value="">Semua Semester</option>
                                @foreach ($semesterList as $sem)
                                    <option value="{{ $sem }}" {{ $semester === $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="{{ $organisasiList->count() > 1 ? 'col-md-3' : 'col-md-4' }}">
                            <label class="form-label fw-semibold">Tahun Ajaran</label>
                            <select id="filterTahunAjaran" class="form-select">
                                <option value="">Semua Tahun Ajaran</option>
                                @foreach ($tahunAjaranList as $ta)
                                    <option value="{{ $ta }}" {{ $tahunAjaran === $ta ? 'selected' : '' }}>
                                        {{ $ta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="{{ $organisasiList->count() > 1 ? 'col-md-3' : 'col-md-4' }} d-flex gap-2">
                            <button type="button" class="btn btn-primary" id="btnApplyFilter">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('pembina.absensi-ekskul.rekap', ['organisasi_id' => $selectedOrgId]) }}"
                                class="btn btn-outline-secondary">
                                Reset
                            </a>
                        </div>
                    @else
                        <div class="col text-muted py-2">
                            <i class="fas fa-info-circle me-1 text-info"></i> Belum ada data pertemuan untuk ekskul ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($totalPertemuan > 0)
            {{-- Info --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Rekap Kehadiran — {{ $organisasi->nama_organisasi }}
                    @if ($semester || $tahunAjaran)
                        <span class="fs-6 fw-normal text-muted">
                            ({{ $semester ? 'Semester ' . $semester : '' }}
                            {{ $tahunAjaran ? 'Tahun Ajaran ' . $tahunAjaran : '' }})
                        </span>
                    @endif
                </h5>
                <span class="badge bg-primary fs-6">{{ $totalPertemuan }} Pertemuan</span>
            </div>

            {{-- Tabel Rekap --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Kelas</th>
                                    <th>No. HP</th>
                                    <th class="text-center">
                                        <span class="badge bg-success">Hadir</span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge bg-warning text-dark">Izin</span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge bg-info">Sakit</span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge bg-danger">Alfa</span>
                                    </th>
                                    <th class="text-center">% Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($anggota as $member)
                                    @php
                                        $total = $member->hadir_count + $member->izin_count + $member->sakit_count + $member->alfa_count;
                                        $persen = $total > 0 ? round(($member->hadir_count / $total) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ $member->nama }}</td>
                                        <td>{{ $member->kelas }}</td>
                                        <td>{{ $member->no_hp ?? '-' }}</td>
                                        <td class="text-center">{{ $member->hadir_count }}</td>
                                        <td class="text-center">{{ $member->izin_count }}</td>
                                        <td class="text-center">{{ $member->sakit_count }}</td>
                                        <td class="text-center">{{ $member->alfa_count }}</td>
                                        <td class="text-center">
                                            @if ($persen >= 75)
                                                <span class="badge bg-success">{{ $persen }}%</span>
                                            @elseif ($persen >= 50)
                                                <span class="badge bg-warning text-dark">{{ $persen }}%</span>
                                            @else
                                                <span class="badge bg-danger">{{ $persen }}%</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            Belum ada data anggota.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times text-warning fa-3x"></i>
                    </div>
                    <h5 class="text-secondary fw-semibold">Tidak Ada Data Kehadiran</h5>
                    <p class="text-muted mb-0">Tidak ditemukan data pertemuan atau absensi untuk ekskul ini pada kombinasi filter yang Anda pilih.</p>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Anda belum menjadi pembina di organisasi manapun.
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseUrl = @json(route('pembina.absensi-ekskul.rekap'));
            const defaultOrgId = @json($selectedOrgId);

            function applyFilter() {
                const orgSelect = document.getElementById('filterOrgId');
                const semesterSelect = document.getElementById('filterSemester');
                const tahunAjaranSelect = document.getElementById('filterTahunAjaran');

                const params = new URLSearchParams();

                // Org ID — from dropdown or use the default (hidden) value
                const orgId = orgSelect ? orgSelect.value : defaultOrgId;
                if (orgId) params.set('organisasi_id', orgId);

                // Semester — only add if a specific value is selected
                const semester = semesterSelect ? semesterSelect.value : '';
                if (semester) params.set('semester', semester);

                // Tahun Ajaran — only add if a specific value is selected
                const tahunAjaran = tahunAjaranSelect ? tahunAjaranSelect.value : '';
                if (tahunAjaran) params.set('tahun_ajaran', tahunAjaran);

                // Navigate to the URL with query parameters
                const url = baseUrl + '?' + params.toString();
                window.location.href = url;
            }

            // Filter button
            const btnFilter = document.getElementById('btnApplyFilter');
            if (btnFilter) {
                btnFilter.addEventListener('click', applyFilter);
            }

            // Org dropdown auto-filter on change
            const orgSelect = document.getElementById('filterOrgId');
            if (orgSelect) {
                orgSelect.addEventListener('change', applyFilter);
            }

            // Semester dropdown auto-filter on change
            const semesterSelect = document.getElementById('filterSemester');
            if (semesterSelect) {
                semesterSelect.addEventListener('change', applyFilter);
            }

            // Tahun Ajaran dropdown auto-filter on change
            const tahunAjaranSelect = document.getElementById('filterTahunAjaran');
            if (tahunAjaranSelect) {
                tahunAjaranSelect.addEventListener('change', applyFilter);
            }
        });
    </script>
@endsection
