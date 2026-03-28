@extends('layouts.admin')

@section('title', 'Drivers')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fas fa-id-card text-primary me-2"></i>Drivers
        </h4>
        <p class="text-muted mb-0">Manage bus drivers</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4"
            data-bs-toggle="modal" data-bs-target="#addDriverModal">
        <i class="fas fa-plus me-2"></i>Add Driver
    </button>
</div>

<!-- ===== STATS ===== -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                     style="width:50px;height:50px;background:linear-gradient(135deg,#1a237e,#0d47a1)">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Drivers</div>
                    <div class="fw-bold fs-5">{{ $drivers->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                     style="width:50px;height:50px;background:linear-gradient(135deg,#11998e,#38ef7d)">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-muted small">Active</div>
                    <div class="fw-bold fs-5 text-success">
                        {{ $drivers->filter(fn($d) => $d->bus_id !== null)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                     style="width:50px;height:50px;background:linear-gradient(135deg,#f64f59,#c471ed)">
                    <i class="fas fa-bus"></i>
                </div>
                <div>
                    <div class="text-muted small">Assigned to Bus</div>
                    <div class="fw-bold fs-5">
                        {{ $drivers->whereNotNull('bus_id')->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== TABLE ===== -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8faff">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-600">#</th>
                        <th class="py-3 text-muted small fw-600">Driver</th>
                        <th class="py-3 text-muted small fw-600">Phone</th>
                        <th class="py-3 text-muted small fw-600">License</th>
                        <th class="py-3 text-muted small fw-600">Assigned Bus</th>
                        <th class="py-3 text-muted small fw-600">Status</th>
                        <th class="py-3 text-muted small fw-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $driver)
                    <tr>
                        <td class="px-4 py-3 text-muted small">{{ $loop->iteration }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:38px;height:38px;min-width:38px;
                                            background:linear-gradient(135deg,#1a237e,#0d47a1);
                                            font-size:0.9rem">
                                    {{ strtoupper(substr($driver->name,0,1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold small">
                                        {{ $driver->name }} {{ $driver->surname }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <small class="text-muted">
                                <i class="fas fa-phone me-1 text-primary"></i>
                                {{ $driver->telephone }}
                            </small>
                        </td>
                        <td class="py-3">
                            <span class="font-monospace small text-primary fw-bold">
                                {{ $driver->license_number }}
                            </span>
                        </td>
                        <td class="py-3">
                            @if($driver->bus)
                                <span class="badge rounded-pill px-3"
                                      style="background:#e8f0fe;color:#1a237e">
                                    <i class="fas fa-bus me-1"></i>
                                    {{ $driver->bus->mack }} — {{ $driver->bus->bus_number }}
                                </span>
                            @else
                                <span class="text-muted small">Not assigned</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($driver->bus_id)
                                <span class="badge rounded-pill px-3"
                                      style="background:#e8f5e9;color:#2e7d32">
                                    <i class="fas fa-circle me-1" style="font-size:0.5rem"></i>Active
                                </span>
                            @else
                                <span class="badge rounded-pill px-3"
                                      style="background:#ffebee;color:#c62828">
                                    <i class="fas fa-circle me-1" style="font-size:0.5rem"></i>Inactive
                                </span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="d-flex gap-2">
                                <!-- Edit -->
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDriverModal{{ $driver->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Delete -->
                                <form method="POST"
                                      action="{{ route('admin.drivers.destroy', $driver->id) }}"
                                      onsubmit="return confirm('Delete this driver?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- ===== EDIT MODAL ===== -->
                    <div class="modal fade" id="editDriverModal{{ $driver->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-3 border-0">
                                <div class="modal-header border-0"
                                     style="background:linear-gradient(135deg,#1a237e,#0d47a1)">
                                    <h5 class="modal-title text-white fw-bold">
                                        <i class="fas fa-edit me-2"></i>Edit Driver
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form method="POST"
                                          action="{{ route('admin.drivers.update', $driver->id) }}">
                                        @csrf @method('PUT')
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">First Name</label>
                                                <input type="text" name="name"
                                                       class="form-control rounded-3"
                                                       value="{{ $driver->name }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">Last Name</label>
                                                <input type="text" name="surname"
                                                       class="form-control rounded-3"
                                                       value="{{ $driver->surname }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">Phone</label>
                                                <input type="text" name="telephone"
                                                       class="form-control rounded-3"
                                                       value="{{ $driver->telephone }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">License Number</label>
                                                <input type="text" name="license_number"
                                                       class="form-control rounded-3"
                                                       value="{{ $driver->license_number }}" required
                                                       oninput="checkLicense(this.value, 'license_msg_{{ $driver->id }}', {{ $driver->id }})">
                                                <small id="license_msg_{{ $driver->id }}" class="d-none mt-1"></small>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold">Assigned Bus</label>
                                                <select name="bus_id" class="form-select rounded-3">
                                                    <option value="">— Not assigned (Inactive) —</option>
                                                    @foreach($buses as $bus)
                                                        <option value="{{ $bus->id }}"
                                                            {{ $driver->bus_id == $bus->id ? 'selected' : '' }}>
                                                            {{ $bus->mack }} — {{ $bus->bus_number }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Status is automatic: assigned = Active, unassigned = Inactive
                                                </small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-3 mt-4">
                                            <button type="button"
                                                    class="btn btn-light rounded-pill flex-grow-1"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit"
                                                    class="btn btn-primary rounded-pill flex-grow-1">
                                                <i class="fas fa-save me-2"></i>Save
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-id-card fa-3x mb-3 d-block opacity-25"></i>
                            No drivers registered yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($drivers->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $drivers->links() }}
</div>
@endif

<!-- ===== ADD MODAL ===== -->
<div class="modal fade" id="addDriverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header border-0"
                 style="background:linear-gradient(135deg,#1a237e,#0d47a1)">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-plus me-2"></i>Add Driver
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('admin.drivers.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">First Name</label>
                            <input type="text" name="name" class="form-control rounded-3"
                                   placeholder="John" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Last Name</label>
                            <input type="text" name="surname" class="form-control rounded-3"
                                   placeholder="Doe" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Phone</label>
                            <input type="text" name="telephone" class="form-control rounded-3"
                                   placeholder="6XXXXXXXX" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">License Number</label>
                            <input type="text" name="license_number" id="license_add"
                                   class="form-control rounded-3"
                                   placeholder="DRV-2024-001" required
                                   oninput="checkLicense(this.value, 'license_msg_add', null)">
                            <small id="license_msg_add" class="d-none mt-1"></small>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Assign Bus</label>
                            <select name="bus_id" class="form-select rounded-3">
                                <option value="">— Not assigned (Inactive) —</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}">
                                        {{ $bus->mack }} — {{ $bus->bus_number }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Status is automatic: assigned = Active, unassigned = Inactive
                            </small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-4">
                        <button type="button" class="btn btn-light rounded-pill flex-grow-1"
                                data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="addDriverBtn"
                                class="btn btn-primary rounded-pill flex-grow-1">
                            <i class="fas fa-plus me-2"></i>Add Driver
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let licenseTimer = null;

function checkLicense(value, msgId, excludeId) {
    const msgEl = document.getElementById(msgId);
    if (!value || value.length < 3) {
        msgEl.className = 'd-none';
        return;
    }

    clearTimeout(licenseTimer);
    licenseTimer = setTimeout(() => {
        fetch(`{{ route('admin.drivers.check') }}?license=${encodeURIComponent(value)}&exclude_id=${excludeId || ''}`)
        .then(r => r.json())
        .then(data => {
            if (data.taken) {
                msgEl.className = 'd-block text-danger small fw-bold mt-1';
                msgEl.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>
                    ⚠️ This license is already assigned to <strong>${data.name}</strong>!`;

                // Disable submit button if in add modal
                const addBtn = document.getElementById('addDriverBtn');
                if (addBtn) addBtn.disabled = true;

                // Show alert popup
                showLicenseAlert(data.name, value);
            } else {
                msgEl.className = 'd-block text-success small fw-bold mt-1';
                msgEl.innerHTML = `<i class="fas fa-check-circle me-1"></i> License number available ✅`;

                const addBtn = document.getElementById('addDriverBtn');
                if (addBtn) addBtn.disabled = false;
            }
        });
    }, 500);
}

function showLicenseAlert(driverName, licenseNumber) {
    // Create toast notification
    const existing = document.getElementById('licenseToast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'licenseToast';
    toast.style.cssText = `
        position:fixed; top:80px; right:20px; z-index:99999;
        background:white; border-radius:12px;
        box-shadow:0 10px 40px rgba(0,0,0,0.15);
        padding:16px 20px; max-width:340px;
        border-left:4px solid #f44336;
        animation: slideIn 0.3s ease;
    `;
    toast.innerHTML = `
        <style>
            @keyframes slideIn { from { transform:translateX(100px); opacity:0; } to { transform:translateX(0); opacity:1; } }
        </style>
        <div class="d-flex align-items-start gap-3">
            <div style="width:40px;height:40px;min-width:40px;background:#ffebee;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-exclamation-triangle text-danger"></i>
            </div>
            <div>
                <div class="fw-bold text-danger mb-1">License Already Exists!</div>
                <div class="small text-muted">
                    License <strong class="text-dark">${licenseNumber}</strong> is already registered
                    to driver <strong class="text-dark">${driverName}</strong>.
                    Please use a different license number.
                </div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()"
                    style="background:none;border:none;color:#999;cursor:pointer;padding:0;margin-left:8px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => { if (toast.parentElement) toast.remove(); }, 5000);
}
</script>
@endpush
