@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Aset')

@section('content_header')
    <h1>Daftar Pengajuan Aset</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Antrean & Histori Pengajuan</h3>
                @if(Auth::user()->role === 'staff' || Auth::user()->role === 'admin')
                <div class="card-tools">
                    <a href="{{ route('asset-requests.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Buat Pengajuan Baru
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                @if(Auth::user()->role !== 'staff')
                                <th>Pengajuan</th>
                                @endif
                                <th>Barang</th>
                                <th>Jml</th>
                                <th>Estimasi Harga</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                @if(Auth::user()->role !== 'staff')
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @if(Auth::user()->role !== 'staff')
                                <td>{{ $req->user->name ?? '-' }}</td>
                                @endif
                                <td>{{ $req->item_name }}</td>
                                <td>{{ $req->quantity }}</td>
                                <td>Rp {{ number_format($req->estimated_price, 0, ',', '.') }}</td>
                                <td>{{ $req->reason }}</td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                        <br><small class="text-muted">Alasan: {{ $req->reject_reason }}</small>
                                    @endif
                                </td>
                                <td>{{ $req->created_at->format('d M Y H:i') }}</td>
                                
                                @if(Auth::user()->role !== 'staff')
                                <td>
                                    @if($req->status === 'pending')
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal-{{ $req->id }}">
                                        <i class="fas fa-check"></i> ACC
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal-{{ $req->id }}">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>

                                    <!-- Modal Approve -->
                                    <div class="modal fade" id="approveModal-{{ $req->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('asset-requests.approve', $req->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin menyetujui pengajuan <b>{{ $req->item_name }}</b> ({{ $req->quantity }} unit)?<br>
                                                        Barang akan otomatis ditambahkan ke Master Aset.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Modal Reject -->
                                    <div class="modal fade" id="rejectModal-{{ $req->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('asset-requests.reject', $req->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Pengajuan</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Alasan Penolakan <span class="text-danger">*</span></label>
                                                            <textarea name="reject_reason" class="form-control" required placeholder="Tuliskan alasan mengapa ditolak..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data pengajuan aset.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
