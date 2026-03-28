@extends('layouts.guichet')
@section('title', 'Drivers')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-id-card text-warning me-2"></i>Drivers</h4>
        <p class="text-muted mb-0">List of all bus drivers</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#fff3e0">
                    <tr>
                        <th class="px-4 py-3 text-muted small">#</th>
                        <th class="py-3 text-muted small">Driver</th>
                        <th class="py-3 text-muted small">Phone</th>
                        <th class="py-3 text-muted small">License</th>
                        <th class="py-3 text-muted small">Assigned Bus</th>
                        <th class="py-3 text-muted small">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $driver)
                    <tr>
                        <td class="px-4 py-3 text-muted small">{{ $loop->iteration }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:38px;height:38px;min-width:38px;background:linear-gradient(135deg,#e65100,#ff6d00);font-size:0.9rem">
                                    {{ strtoupper(substr($driver->name,0,1)) }}
                                </div>
                                <div class="fw-bold small">{{ $driver->name }} {{ $driver->surname }}</div>
                            </div>
                        </td>
                        <td class="py-3"><small class="text-muted"><i class="fas fa-phone me-1 text-warning"></i>{{ $driver->telephone }}</small></td>
                        <td class="py-3"><span class="font-monospace small text-warning fw-bold">{{ $driver->license_number }}</span></td>
                        <td class="py-3">
                            @if($driver->bus)
                                <span class="badge rounded-pill px-3" style="background:#fff3e0;color:#e65100">
                                    <i class="fas fa-bus me-1"></i>{{ $driver->bus->mack }} — {{ $driver->bus->bus_number }}
                                </span>
                            @else
                                <span class="text-muted small">Not assigned</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($driver->status === 'actif')
                                <span class="badge rounded-pill px-3" style="background:#e8f5e9;color:#2e7d32">Active</span>
                            @else
                                <span class="badge rounded-pill px-3" style="background:#ffebee;color:#c62828">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No drivers registered yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@if($drivers->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $drivers->links() }}</div>
@endif
@endsection