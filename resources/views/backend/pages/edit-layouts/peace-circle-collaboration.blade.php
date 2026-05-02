<!-- Peace Circle & Collaborate | Collaboration -->

@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');

    $hero_title = $metaValue('hero_title');
    $hero_subtitle = $metaValue('hero_subtitle');
    $hero_image = $metaValue('hero_image');
    $hero_description = $metaValue('hero_description');

    $collaborate_title = $metaValue('collaborate_title');
    $collaborate_subtitle = $metaValue('collaborate_subtitle');
    $collaborate_image = $metaValue('collaborate_image');
    $collaborate_description = $metaValue('collaborate_description');
    $collaborate_items = $metaArray('collaborate_items');

    $donate_title = $metaValue('donate_title');
    $donate_navigation = $metaValue('donate_navigation');
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

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $hero_title }}" name="meta[hero_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $hero_subtitle }}" name="meta[hero_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $hero_image }}" type="hidden" name="meta[hero_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[hero_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $hero_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Collaborate Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $collaborate_title }}" name="meta[collaborate_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $collaborate_subtitle }}" name="meta[collaborate_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $collaborate_image }}" type="hidden" name="meta[collaborate_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[collaborate_description]" class="form-control" rows="4" placeholder="Enter description">{{ $collaborate_description }}</textarea>
    </div>

    <div class="col-md-12">
        <hr>
        <h5 class="text-primary">Collaborate Items</h5>
    </div>

    <div class="collaborate-items-target w-100">
        @if(isset($collaborate_items['itration']) && is_array($collaborate_items['itration']))
            @foreach($collaborate_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[collaborate_items][itration][]" type="hidden">
                            <div class="col-md-12 form-group mb-2">
                                <input value="{{ $collaborate_items['title'][$index] ?? '' }}" name="meta[collaborate_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[collaborate_items][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $collaborate_items['description'][$index] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 btn-dynamic-fields">
                        <button type="button" class="btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".collaborate-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[collaborate_items][itration][]" type="hidden">
                    <div class="col-md-12 form-group mb-2">
                        <input value="" name="meta[collaborate_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[collaborate_items][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-1 btn-dynamic-fields">
                <button type="button" class="btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>'>
        <i class="ti ti-plus"></i>
        <span class="ml-2">Add More</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Donate Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $donate_title }}" name="meta[donate_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Donate Navigation</label>
        <input class="form-control" value="{{ $donate_navigation }}" name="meta[donate_navigation]" type="text" placeholder="Enter navigation URL">
    </div>
</div>
