<!-- Contact Us | Contact -->

@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');

    $hero_title = $metaValue('hero_title');
    $hero_description = $metaValue('hero_description');

    $global_presence_title = $metaValue('global_presence_title');
    $global_presence_description = $metaValue('global_presence_description');
    $global_presence_image = $metaValue('global_presence_image');
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Breadcrumb Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $breadcrumb_title }}" name="meta[breadcrumb_title]" type="text" placeholder="Enter title">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Hero Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $hero_title }}" name="meta[hero_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[hero_description]" class="form-control" rows="4" placeholder="Enter description">{{ $hero_description }}</textarea>
    </div>

    <div class="col-md-12">
        <div class="alert alert-info mb-2">
            The system will automatically fetch Contact Information from Company Settings.
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Global Presence Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $global_presence_title }}" name="meta[global_presence_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[global_presence_description]" class="form-control" rows="4" placeholder="Enter description">{{ $global_presence_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $global_presence_image }}" type="hidden" name="meta[global_presence_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
</div>
