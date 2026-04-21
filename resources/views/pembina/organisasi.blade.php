@extends('layouts.pembina')

@section('title', 'Data Organisasi')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Data Organisasi Binaan</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Nama Organisasi</th>
                        <th class="text-nowrap">Deskripsi</th>
                        <th class="text-nowrap">Ketua</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($organisasi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_organisasi }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 80) }}</td>
                            <td>{{ $item->ketua?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada organisasi binaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
