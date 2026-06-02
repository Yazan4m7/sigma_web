@extends('layouts.app' ,[ 'pageSlug' => "Edit Media"])

@section('content')
<form enctype="multipart/form-data" class="card" style="padding:20px" method="POST" action="{{ route('edit-media-post', $media->id) }}">
    @csrf


    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h6 class="kt-portlet__head-title">
                <i class="fa fa-pencil" style="width:3%"></i> Edit Media Info:
            </h6>
        </div>
    </div>
    <hr style="margin-top: 0;">

    <div class="row">
        <div class="col-md-3 col-xs-6 col-l-3 col-xl-3">
            <div class="col-md-12 col-xs-12"><label>Media title:</label></div>
            <div class="col-md-12 col-xs-12">
                <input type="hidden" name="media_id" value="{{$media->id}}">
                <input class="form-control" type="text" name="title" required placeholder="Media title" value="{{ old('title', $media->text) }}" />
                <span class="help-block text-muted"><small>English | 3-40 Char.</small></span>
            </div>
        </div>
    </div>

    <br/>
    <hr style="margin-top: 0;">

    <div class="row">
        <div class="col-md-3 col-xs-6 col-l-3 col-xl-3">
            <div class="col-md-12 col-xs-12"><label>Current Thumbnail:</label></div>
            <div class="col-md-12 col-xs-12 mb-3">
                @if($media)
                    <img src="{{ '/gallery/' . $media->id . '/' . 'thumbnail.jpg' }}" alt="Current Thumbnail" style="max-width: 150px; height: auto;">
                @else
                    <p>No thumbnail uploaded.</p>
                @endif
            </div>
            <div class="col-md-12 col-xs-12"><label>New Thumbnail (optional):</label></div>
            <div class="col-md-12 col-xs-12">
                <input class="form-control" type="file" name="image" accept="image/jpg, image/jpeg" />
                <span class="help-block text-muted"><small>*.JPG (Leave empty to keep current)</small></span>
            </div>
        </div>
    </div>

    <br/>
    <hr style="margin-top: 0;">

    <div class="row">
        <div class="col-md-3 col-xs-6 col-l-3 col-xl-3">
            <div class="col-md-12 col-xs-12"><label>Current Video:</label></div>
            <div class="col-md-12 col-xs-12 mb-3">
                @if($media)
                    <video controls width="300">
                        <source src="{{ '/gallery/' . $media->id . '/' . 'video.mp4' }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <p>No video uploaded.</p>
                @endif
            </div>
            <div class="col-md-12 col-xs-12"><label>New Video (optional):</label></div>
            <div class="col-md-12 col-xs-12">
                <input class="form-control" type="file" name="video" accept="video/mp4" />
                <span class="help-block text-muted"><small>*.MP4 (Leave empty to keep current)</small></span>
            </div>
        </div>
    </div>

    <br>
    <br/>
    <div class="form-group">
        <div class="form-group mb-0">
            <div>
                <button type="submit" class="btn btn-info waves-effect waves-light" id="submitBtn">
                    Update
                </button>
                <a href="{{ route('media-index') }}" class="btn btn-secondary waves-effect m-l-5">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
<script type="text/javascript" src="{{asset('assets/plugins/parsleyjs/dist/parsley.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var $form = $('form');
        var $submitBtn = $('#submitBtn');

        // Re-enable button when any input changes after validation error
        $form.find('input, select, textarea').on('input change', function() {
            $submitBtn.prop('disabled', false);
        });

        // Re-enable button if validation fails
        $form.on('submit', function(e) {
            var parsleyForm = $form.parsley();
            if (!parsleyForm.validate()) {
                e.preventDefault();
                $submitBtn.prop('disabled', false);
                return false;
            }
        });
    });
</script>
@endpush
