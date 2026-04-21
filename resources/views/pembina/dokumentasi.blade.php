@extends('layouts.pembina')

@section('title', 'Dokumentasi Kegiatan')

@section('content')

    <div class="row">
        @php
            $dokumentasiPerKegiatan = $dokumentasi->groupBy('kegiatan_id');
        @endphp

        @forelse ($dokumentasiPerKegiatan as $kegiatanId => $items)
            @php
                $firstItem = $items->first();
                $coverUrl = \Illuminate\Support\Str::startsWith($firstItem->file_dokumentasi ?? '', 'http')
                    ? $firstItem->file_dokumentasi
                    : asset('storage/' . $firstItem->file_dokumentasi);
            @endphp
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ $coverUrl }}" class="card-img-top"
                        alt="Dokumentasi {{ $firstItem->kegiatan?->nama_kegiatan }}"
                        style="height: 220px; object-fit: cover;">

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">{{ $firstItem->kegiatan?->nama_kegiatan ?? '-' }}</h6>
                        <p class="text-muted small mb-2">{{ $firstItem->kegiatan?->organisasi?->nama_organisasi ?? '-' }}</p>
                        <p class="text-muted small mb-2">Total {{ $items->count() }} gambar</p>

                        <p class="card-text mb-3">
                            {{ \Illuminate\Support\Str::limit($firstItem->keterangan ?? '-', 90) }}
                        </p>

                        <div class="mt-auto d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#detailDokumentasiModal{{ $kegiatanId }}">
                                <i class="bi bi-images"></i> Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white border rounded">
                    <p class="text-muted mb-0">Belum ada dokumentasi kegiatan.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- MODAL DETAIL PER KEGIATAN -->
    @foreach ($dokumentasiPerKegiatan as $kegiatanId => $items)
        @php
            $firstItem = $items->first();
        @endphp
        <div class="modal fade" id="detailDokumentasiModal{{ $kegiatanId }}" tabindex="-1"
            aria-labelledby="detailDokumentasiModalLabel{{ $kegiatanId }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="detailDokumentasiModalLabel{{ $kegiatanId }}">
                            Detail Dokumentasi - {{ $firstItem->kegiatan?->nama_kegiatan ?? '-' }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            @foreach ($items as $item)
                                @php
                                    $imageUrl = \Illuminate\Support\Str::startsWith(
                                        $item->file_dokumentasi ?? '',
                                        'http',
                                    )
                                        ? $item->file_dokumentasi
                                        : asset('storage/' . $item->file_dokumentasi);
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border">
                                        <img src="{{ $imageUrl }}" class="card-img-top"
                                            alt="Dokumentasi {{ $item->kegiatan?->nama_kegiatan }}"
                                            style="height: 210px; object-fit: cover;">

                                        <div class="card-body d-flex flex-column">
                                            <p class="text-muted small mb-2">{{ basename($item->file_dokumentasi ?? '-') }}
                                            </p>
                                            <p class="card-text mb-3">
                                                {{ $item->keterangan ?: '-' }}
                                            </p>

                                            <div class="mt-auto text-muted small">
                                                <i class="bi bi-calendar-event"></i> {{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
