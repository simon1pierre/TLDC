@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Banner</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banners</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            @php
                $translations = [
                    'en' => $banner->translationFor('en'),
                    'fr' => $banner->translationFor('fr'),
                    'rw' => $banner->translationFor('rw'),
                ];
            @endphp
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.banners.update', $banner) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control" rows="3" required>{{ old('content', $banner->content) }}</textarea>
                                @error('content') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Link (optional)</label>
                                <input type="text" name="link" value="{{ old('link', $banner->link) }}" class="form-control" placeholder="https://...">
                                @error('link') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Background Color</label>
                                <div class="input-group">
                                    <input type="color" name="background_color" value="{{ old('background_color', $banner->background_color ?? '#00283c') }}" class="form-control form-control-color">
                                    <input type="text" name="background_color_text" value="{{ old('background_color', $banner->background_color ?? '#00283c') }}" class="form-control" placeholder="#00283c" oninput="this.form.elements.background_color.value=this.value" onchange="this.form.elements.background_color.value=this.value">
                                </div>
                                @error('background_color') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Text Color</label>
                                <div class="input-group">
                                    <input type="color" name="text_color" value="{{ old('text_color', $banner->text_color ?? '#ffffff') }}" class="form-control form-control-color">
                                    <input type="text" name="text_color_text" value="{{ old('text_color', $banner->text_color ?? '#ffffff') }}" class="form-control" placeholder="#ffffff" oninput="this.form.elements.text_color.value=this.value" onchange="this.form.elements.text_color.value=this.value">
                                </div>
                                @error('text_color') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" class="form-control" min="0" max="9999">
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active))>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                                @error('starts_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $banner->ends_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                                @error('ends_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-3">Translations (EN / FR / RW)</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Content (EN)</label>
                                                <textarea name="content_en" class="form-control" rows="3">{{ old('content_en', $translations['en']?->description) }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Content (FR)</label>
                                                <textarea name="content_fr" class="form-control" rows="3">{{ old('content_fr', $translations['fr']?->description) }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Content (RW)</label>
                                                <textarea name="content_rw" class="form-control" rows="3">{{ old('content_rw', $translations['rw']?->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Update Banner</button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
