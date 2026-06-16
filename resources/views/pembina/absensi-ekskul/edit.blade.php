@extends('layouts.pembina')

@section('title', 'Edit Pertemuan ' . $pertemuan->pertemuan_ke)

@section('content')

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Pertemuan {{ $pertemuan->pertemuan_ke }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pembina.absensi-ekskul.update', $pertemuan) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Info Pertemuan --}}
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Tanggal Pertemuan <span
                                class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control"
                            value="{{ old('tanggal', $pertemuan->tanggal->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Pertemuan Ke <span class="text-danger">*</span></label>
                        <input type="number" name="pertemuan_ke" class="form-control" min="1"
                            value="{{ old('pertemuan_ke', $pertemuan->pertemuan_ke) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Organisasi</label>
                        <input type="text" class="form-control" value="{{ $organisasi->nama_organisasi }}" disabled>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Semester <span
                                class="text-danger">*</span></label>
                        <select name="semester" class="form-select" required>
                            <option value="Ganjil" {{ old('semester', $pertemuan->semester) === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester', $pertemuan->semester) === 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tahun Ajaran <span
                                class="text-danger">*</span></label>
                        <select name="tahun_ajaran" class="form-select" required>
                            @php
                                $currYear = (int) date('Y');
                            @endphp
                            @for ($i = -2; $i <= 2; $i++)
                                @php
                                    $yearStr = ($currYear + $i) . '/' . ($currYear + $i + 1);
                                @endphp
                                <option value="{{ $yearStr }}" {{ old('tahun_ajaran', $pertemuan->tahun_ajaran) === $yearStr ? 'selected' : '' }}>
                                    {{ $yearStr }}
                                </option>
                            @endfor
                        </select>
                        @error('tahun_ajaran')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" class="form-control" rows="3">{{ old('deskripsi_kegiatan', $pertemuan->deskripsi_kegiatan) }}</textarea>
                </div>

                {{-- Foto yang Sudah Ada --}}
                @if ($pertemuan->fotoKegiatan->isNotEmpty())
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Kegiatan yang Sudah Ada</label>
                        <div class="row g-3">
                            @foreach ($pertemuan->fotoKegiatan as $foto)
                                <div class="col-md-3 col-sm-4">
                                    <div class="card border position-relative">
                                        <img src="{{ asset('storage/' . $foto->file_path) }}" class="card-img-top"
                                            style="height: 120px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="hapus_foto[]" value="{{ $foto->id }}"
                                                    id="hapusFoto{{ $foto->id }}">
                                                <label class="form-check-label text-danger small"
                                                    for="hapusFoto{{ $foto->id }}">
                                                    Hapus foto ini
                                                </label>
                                            </div>
                                            @if ($foto->keterangan)
                                                <small class="text-muted d-block mt-1">{{ $foto->keterangan }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Upload Foto Baru --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Tambah Foto Baru</label>
                    <div id="foto-container">
                        <div class="input-group mb-2 foto-row">
                            <input type="file" name="foto_kegiatan[]" class="form-control form-control-sm"
                                accept="image/jpeg,image/png,image/webp">
                            <input type="text" name="keterangan_foto[]" class="form-control form-control-sm"
                                placeholder="Keterangan foto (opsional)">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-foto"
                                style="display:none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="tambah-foto">
                        <i class="fas fa-plus me-1"></i> Tambah Foto
                    </button>
                    <div class="form-text">Format: JPG, PNG, WEBP. Maks: 5MB per file.</div>
                </div>

                <hr>

                {{-- Daftar Absensi --}}
                <h6 class="fw-bold mb-3"><i class="fas fa-user-check me-2"></i>Daftar Absensi Anggota</h6>

                <div class="mb-3 d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="setAllStatus('hadir')">
                        <i class="fas fa-check me-1"></i> Semua Hadir
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="setAllStatus('alfa')">
                        <i class="fas fa-times me-1"></i> Semua Alfa
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" data-has-global-search="1">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Anggota</th>
                                <th>Kelas</th>
                                <th class="text-center" style="width: 100px;">Hadir</th>
                                <th class="text-center" style="width: 100px;">Izin</th>
                                <th class="text-center" style="width: 100px;">Sakit</th>
                                <th class="text-center" style="width: 100px;">Alfa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anggota as $member)
                                @php
                                    $currentStatus = $absensiMap[$member->id] ?? 'alfa';
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $member->nama }}</td>
                                    <td>{{ $member->kelas ?? '-' }}</td>
                                    @foreach (['hadir', 'izin', 'sakit', 'alfa'] as $status)
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input absensi-radio" type="radio"
                                                    name="absensi[{{ $member->id }}]"
                                                    value="{{ $status }}"
                                                    data-status="{{ $status }}"
                                                    {{ old("absensi.{$member->id}", $currentStatus) === $status ? 'checked' : '' }}
                                                    required>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('pembina.absensi-ekskul.show', $pertemuan) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function setAllStatus(status) {
            document.querySelectorAll(`.absensi-radio[data-status="${status}"]`).forEach(radio => {
                radio.checked = true;
            });
        }

        document.getElementById('tambah-foto')?.addEventListener('click', function() {
            const container = document.getElementById('foto-container');
            const row = document.createElement('div');
            row.className = 'input-group mb-2 foto-row';
            row.innerHTML = `
                <input type="file" name="foto_kegiatan[]" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
                <input type="text" name="keterangan_foto[]" class="form-control form-control-sm" placeholder="Keterangan foto (opsional)">
                <button type="button" class="btn btn-outline-danger btn-sm remove-foto"><i class="fas fa-times"></i></button>
            `;
            container.appendChild(row);
            updateRemoveButtons();
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-foto')) {
                e.target.closest('.foto-row').remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.foto-row');
            rows.forEach((row) => {
                const btn = row.querySelector('.remove-foto');
                btn.style.display = rows.length > 1 ? '' : 'none';
            });
        }
    </script>
@endsection
