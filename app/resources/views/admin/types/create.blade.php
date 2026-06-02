@extends('layouts.app', ['pageSlug' => 'Create Type'])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Type</h4>
                    <p class="card-category">Add a new material sub-type</p>
                </div>
                
                <form method="POST" action="{{ route('types.store') }}">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material_id">Material *</label>
                                    <select class="form-control @error('material_id') is-invalid @enderror" 
                                            name="material_id" id="material_id" required>
                                        <option value="">Select a material</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" 
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                                {{ $material->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('material_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Type Name *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name') }}"
                                           placeholder="e.g., Full Contour, Layered, Monolithic"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" 
                                              id="description" 
                                              rows="3"
                                              placeholder="Optional description of this type">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               name="is_enabled" 
                                               id="is_enabled" 
                                               value="1"
                                               {{ old('is_enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_enabled">
                                            Enable this type (users can select it)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Create Type
                        </button>
                        <a href="{{ route('types.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Type Examples</h5>
                </div>
                <div class="card-body">
                    <h6>Zirconia Types:</h6>
                    <ul class="list-unstyled">
                        <li>• Full Contour</li>
                        <li>• Layered</li>
                        <li>• Monolithic</li>
                    </ul>
                    
                    <h6>PMMA Types:</h6>
                    <ul class="list-unstyled">
                        <li>• Temporary Crown</li>
                        <li>• Surgical Guide</li>
                        <li>• Try-in</li>
                    </ul>
                    
                    <h6>Lithium Disilicate Types:</h6>
                    <ul class="list-unstyled">
                        <li>• Pressed</li>
                        <li>• CAD/CAM</li>
                        <li>• Stained</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection