@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Banners</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Banners</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto d-flex align-items-center gap-2">
                <button type="button" class="btn btn-light no-print" onclick="printAdminReport('Banners Report')">
                    <i class="feather-printer me-2"></i>
                    Print Report
                </button>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                    <i class="feather-plus me-2"></i>
                    Add Banner
                </a>
            </div>
        </div>

        <div class="main-content">
            @if (session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="active" @selected(request('status') === 'active')>Active</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Deleted</label>
                            <select name="deleted" class="form-select">
                                <option value="">Exclude</option>
                                <option value="with" @selected(request('deleted') === 'with')>Include</option>
                                <option value="only" @selected(request('deleted') === 'only')>Only</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Per Page</label>
                            <select name="per_page" class="form-select">
                                <option value="5" @selected(request('per_page') == 5)>5</option>
                                <option value="10" @selected(request('per_page') == 10)>10</option>
                                <option value="25" @selected(request('per_page') == 25)>25</option>
                                <option value="50" @selected(request('per_page') == 50)>50</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Content</th>
                                    <th>Link</th>
                                    <th>Order</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($banners as $banner)
                                    <tr>
                                        <td class="fw-semibold text-dark" style="max-width: 300px;">
                                            <div class="text-truncate">{{ $banner->content }}</div>
                                            @if ($banner->background_color)
                                                <span class="badge bg-light text-muted fs-11 mt-1">bg: {{ $banner->background_color }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($banner->link)
                                                <a href="{{ $banner->link }}" target="_blank" class="text-muted fs-12">{{ str($banner->link)->limit(40) }}</a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $banner->sort_order }}</td>
                                        <td>
                                            @if ($banner->starts_at || $banner->ends_at)
                                                <div class="fs-12">
                                                    @if ($banner->starts_at)
                                                        <div>From: {{ $banner->starts_at->format('M j, Y') }}</div>
                                                    @endif
                                                    @if ($banner->ends_at)
                                                        <div>Until: {{ $banner->ends_at->format('M j, Y') }}</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">Always</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($banner->trashed())
                                                <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                            @elseif ($banner->is_active)
                                                <span class="badge bg-soft-success text-success">Active</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($banner->trashed())
                                                <form action="{{ route('admin.banners.restore', $banner->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success">Restore</button>
                                                </form>
                                                <form action="{{ route('admin.banners.force-delete', $banner->id) }}" method="POST" class="d-inline" data-confirm="Permanently delete this banner?" data-confirm-action="Permanent Delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No banners found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $banners->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
