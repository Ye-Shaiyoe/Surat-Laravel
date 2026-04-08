@extends('layouts.admin')

@section('title', 'Data Pegawai')

@section('content')
<div class="space-y-24">

    {{-- STATISTICS --}}
    <div class="stat-grid">
        <div class="stat-card blue">
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-sub">{{ $stats['total_users_registered'] }} user, {{ $stats['total_admins'] }} admin</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Total Surat</div>
            <div class="stat-value">{{ $stats['total_surats'] }}</div>
            <div class="stat-sub">{{ $stats['total_surats_selesai'] }} selesai</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-label">Surat Proses</div>
            <div class="stat-value">{{ $stats['total_surats_proses'] }}</div>
            <div class="stat-sub">Menunggu approval</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Surat Ditolak</div>
            <div class="stat-value">{{ $stats['total_surats_ditolak'] }}</div>
            <div class="stat-sub">Perlu revisi</div>
        </div>
    </div>

    {{-- FILTERS & SEARCH --}}
    <div class="card">
        <form method="GET" class="space-y-12">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 12px; align-items: end;">
                {{-- Search --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">
                        Cari Nama / Email
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari pegawai..."
                           style="width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                </div>

                {{-- Filter Role --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">
                        Filter Role
                    </label>
                    <select name="role" style="width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                {{-- Sort --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">
                        Urutkan
                    </label>
                    <select name="sort" style="width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama</option>
                        <option value="total_surats" {{ request('sort') === 'total_surats' ? 'selected' : '' }}>Jumlah Surat</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        🔍 Cari
                    </button>
                </div>
            </div>

            @if(request()->hasAny(['search', 'role', 'sort']))
                <div style="text-align: right;">
                    <a href="{{ route('admin.users.index') }}" class="btn" style="font-size: 12px;">
                        ✕ Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- DATA PEGAWAI TABLE --}}
    <div class="card">
        <div class="section-header" style="margin-bottom: 16px;">
            <h2>📋 Data Pegawai / Pengguna</h2>
            <small>Total: {{ $users->total() }} pengguna</small>
        </div>

        @if($users->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nama</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 12%;">Role</th>
                            <th style="width: 12%; text-align: center;">Total Surat</th>
                            <th style="width: 10%; text-align: center;">Selesai</th>
                            <th style="width: 10%; text-align: center;">Ditolak</th>
                            <th style="width: 11%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 12px; color: #6b7280;">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'badge-blue' : 'badge-gray' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <strong style="font-size: 14px;">{{ $user->total_surats }}</strong>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-green">{{ $user->surats_selesai }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-red">{{ $user->surats_ditolak }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center;">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm" title="Lihat detail">
                                            👁️
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Yakin hapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm" style="color: #b91c1c;" title="Hapus">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px; color: #9ca3af;">
                <p style="font-size: 14px;">Tidak ada pengguna ditemukan</p>
            </div>
        @endif
    </div>

</div>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 1fr 1fr 1fr"] {
            grid-template-columns: 1fr 1fr !important;
        }
        
        table { font-size: 12px; }
        thead th { padding: 8px 10px; }
        tbody td { padding: 8px 10px; }
        
        th[style*="width: 25%"],
        th[style*="width: 20%"],
        th[style*="width: 12%"],
        th[style*="width: 10%"],
        th[style*="width: 11%"] {
            width: auto !important;
        }
    }
</style>
@endsection
