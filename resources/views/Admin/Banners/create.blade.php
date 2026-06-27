@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Add Banner</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banners</a></li>
                    <li class="breadcrumb-item">Create</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.banners.store') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control" rows="3" required>{{ old('content') }}</textarea>
                                @error('content') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Link (optional)</label>
                                <input type="text" name="link" value="{{ old('link') }}" class="form-control" placeholder="https://...">
                                @error('link') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Background Color</label>
                                <div class="input-group">
                                    <input type="color" name="background_color" value="{{ old('background_color', '#00283c') }}" class="form-control form-control-color">
                                    <input type="text" name="background_color_text" value="{{ old('background_color', '#00283c') }}" class="form-control" placeholder="#00283c" oninput="this.form.elements.background_color.value=this.value" onchange="this.form.elements.background_color.value=this.value">
                                </div>
                                @error('background_color') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Text Color</label>
                                <div class="input-group">
                                    <input type="color" name="text_color" value="{{ old('text_color', '#ffffff') }}" class="form-control form-control-color">
                                    <input type="text" name="text_color_text" value="{{ old('text_color', '#ffffff') }}" class="form-control" placeholder="#ffffff" oninput="this.form.elements.text_color.value=this.value" onchange="this.form.elements.text_color.value=this.value">
                                </div>
                                @error('text_color') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" min="0" max="9999">
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Date (optional)</label>
                                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="form-control">
                                @error('starts_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Date (optional)</label>
                                <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" class="form-control">
                                @error('ends_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-3">Translations (EN / FR / RW)</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Content (EN)</label>
                                                <textarea name="content_en" class="form-control" rows="3">{{ old('content_en') }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Content (FR)</label>
                                                <textarea name="content_fr" class="form-control" rows="3">{{ old('content_fr') }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Content (RW)</label>
                                                <textarea name="content_rw" class="form-control" rows="3">{{ old('content_rw') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Save Banner</button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
