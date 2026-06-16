@extends('layouts.admin')

@section('title', 'Absensi — ' . $organisasi->nama_organisasi)

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.absensi-ekskul') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Organisasi
        </a>
    </div>

    {{-- Filter Semester & Tahun Ajaran --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end" id="adminFilterForm">
                @if (count($semesterList) > 0 || count($tahunAjaranList) > 0)
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Semester</label>
                        <select id="filterSemester" class="form-select">
                            <option value="">Semua Semester</option>
                            @foreach ($semesterList as $sem)
                                <option value="{{ $sem }}" {{ $semester === $sem ? 'selected' : '' }}>{{ $sem }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <select id="filterTahunAjaran" class="form-select">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach ($tahunAjaranList as $ta)
                                <option value="{{ $ta }}" {{ $tahunAjaran === $ta ? 'selected' : '' }}>
                                    {{ $ta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <button type="button" class="btn btn-primary" id="btnApplyFilter">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.absensi-ekskul.show', $organisasi) }}"
                            class="btn btn-outline-secondary">
                            Reset
                        </a>
                        @if ($totalPertemuan > 0)
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </button>
                        @endif
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
        {{-- Rekap Kehadiran Anggota --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Rekap Kehadiran</h6>
                <span class="badge bg-primary">{{ $totalPertemuan }} Pertemuan</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>No. HP</th>
                                <th class="text-center"><span class="badge bg-success">Hadir</span></th>
                                <th class="text-center"><span class="badge bg-warning text-dark">Izin</span></th>
                                <th class="text-center"><span class="badge bg-info">Sakit</span></th>
                                <th class="text-center"><span class="badge bg-danger">Alfa</span></th>
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

        {{-- Riwayat Pertemuan --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Riwayat Pertemuan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pertemuan</th>
                                <th>Tanggal</th>
                                <th>Semester</th>
                                <th>Tahun Ajaran</th>
                                <th class="text-center"><span class="badge bg-success">H</span></th>
                                <th class="text-center"><span class="badge bg-warning text-dark">I</span></th>
                                <th class="text-center"><span class="badge bg-info">S</span></th>
                                <th class="text-center"><span class="badge bg-danger">A</span></th>
                                <th style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pertemuan as $item)
                                <tr>
                                    <td class="fw-semibold">Pertemuan {{ $item->pertemuan_ke }}</td>
                                    <td>{{ $item->tanggal->format('d M Y') }}</td>
                                    <td>{{ $item->semester }}</td>
                                    <td>{{ $item->tahun_ajaran }}</td>
                                    <td class="text-center">{{ $item->hadir_count }}</td>
                                    <td class="text-center">{{ $item->izin_count }}</td>
                                    <td class="text-center">{{ $item->sakit_count }}</td>
                                    <td class="text-center">{{ $item->alfa_count }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.absensi-ekskul.detail', [$organisasi, $item]) }}"
                                                class="btn btn-info btn-sm text-white" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.absensi-ekskul.export-pertemuan-pdf', [$organisasi, $item]) }}"
                                                class="btn btn-danger btn-sm text-white" title="Export PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        Belum ada data pertemuan.
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

    {{-- Modal Export PDF --}}
    @if ($totalPertemuan > 0)
        <div class="modal fade" id="exportPdfModal" tabindex="-1" aria-labelledby="exportPdfModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.absensi-ekskul.export-pdf', $organisasi) }}" method="GET" target="_blank">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exportPdfModalLabel"><i class="fas fa-file-pdf text-danger me-2"></i>Pilihan Export PDF</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Jenis Laporan</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="export_type" id="typeSemester" value="semester" checked>
                                        <label class="form-check-label" for="typeSemester">
                                            Per Semester
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="export_type" id="typeBulan" value="bulan">
                                        <label class="form-check-label" for="typeBulan">
                                            Per Bulan
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Per Semester Section -->
                            <div id="sectionSemester">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Semester</label>
                                    <select name="semester" id="exportSemester" class="form-select">
                                        <option value="">Semua Semester</option>
                                        @foreach ($semesterList as $sem)
                                            <option value="{{ $sem }}">{{ $sem }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tahun Ajaran</label>
                                    <select name="tahun_ajaran" id="exportTahunAjaran" class="form-select">
                                        <option value="">Semua Tahun Ajaran</option>
                                        @foreach ($tahunAjaranList as $ta)
                                            <option value="{{ $ta }}">{{ $ta }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Per Bulan Section -->
                            <div id="sectionBulan" class="d-none">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Bulan</label>
                                    <select name="bulan" id="exportBulan" class="form-select">
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tahun</label>
                                    <select name="tahun" id="exportTahun" class="form-select">
                                        @foreach ($tahunList as $th)
                                            <option value="{{ $th }}">{{ $th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">
                                <i class="fas fa-download me-1"></i> Unduh PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseUrl = @json(route('admin.absensi-ekskul.show', $organisasi));

            function getFilterParams() {
                const params = new URLSearchParams();
                const semester = document.getElementById('filterSemester')?.value || '';
                const tahunAjaran = document.getElementById('filterTahunAjaran')?.value || '';
                if (semester) params.set('semester', semester);
                if (tahunAjaran) params.set('tahun_ajaran', tahunAjaran);
                return params;
            }

            function applyFilter() {
                const params = getFilterParams();
                const queryStr = params.toString();
                window.location.href = baseUrl + (queryStr ? '?' + queryStr : '');
            }

            // Filter button
            document.getElementById('btnApplyFilter')?.addEventListener('click', applyFilter);

            // Auto-filter on dropdown change
            document.getElementById('filterSemester')?.addEventListener('change', applyFilter);
            document.getElementById('filterTahunAjaran')?.addEventListener('change', applyFilter);

            // Modal radio toggle logic
            const typeSemester = document.getElementById('typeSemester');
            const typeBulan = document.getElementById('typeBulan');
            const sectionSemester = document.getElementById('sectionSemester');
            const sectionBulan = document.getElementById('sectionBulan');

            if (typeSemester && typeBulan) {
                typeSemester.addEventListener('change', function() {
                    if (this.checked) {
                        sectionSemester.classList.remove('d-none');
                        sectionBulan.classList.add('d-none');
                    }
                });

                typeBulan.addEventListener('change', function() {
                    if (this.checked) {
                        sectionBulan.classList.remove('d-none');
                        sectionSemester.classList.add('d-none');
                    }
                });
            }
        });
    </script>
@endsection
