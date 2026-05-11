<!-- Donate | Donate -->

@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');

    $highlights = $metaArray('highlights');

    $contribution_title = $metaValue('contribution_title');
    $contribution_description = $metaValue('contribution_description');

    $financial_support_icon = $metaValue('financial_support_icon');
    $financial_support_title = $metaValue('financial_support_title');
    $financial_support_description = $metaValue('financial_support_description');
    $financial_support_navigation_text = $metaValue('financial_support_navigation_text');
    $financial_support_navigation_url = $metaValue('financial_support_navigation_url');

    $join_title = $metaValue('join_title');
    $join_bg_image = $metaValue('join_bg_image');
    $join_navigation_url = $metaValue('join_navigation_url');
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
        <h4 class="text-primary">Highlights Section</h4>
    </div>

    <div class="highlights-target w-100">
        @if(isset($highlights['itration']) && is_array($highlights['itration']))
            @foreach($highlights['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[highlights][itration][]" type="hidden">
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $highlights['title'][$index] ?? '' }}" name="meta[highlights][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $highlights['value'][$index] ?? '' }}" name="meta[highlights][value][]" type="text" class="form-control" placeholder="Enter value">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-limit="4" data-target=".highlights-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[highlights][itration][]" type="hidden">
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[highlights][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[highlights][value][]" type="text" class="form-control" placeholder="Enter value">
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
        <h4 class="text-primary">Contribution Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $contribution_title }}" name="meta[contribution_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[contribution_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $contribution_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Financial Support Section</h4>
    </div>

    <div class="col-md-12">
        <label class="form-label">Icon</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $financial_support_icon }}" type="hidden" name="meta[financial_support_icon]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $financial_support_title }}" name="meta[financial_support_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <input class="form-control" value="{{ $financial_support_description }}" name="meta[financial_support_description]" type="text" placeholder="Enter description">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Navigation Text</label>
        <input class="form-control" value="{{ $financial_support_navigation_text }}" name="meta[financial_support_navigation_text]" type="text" placeholder="Enter navigation text">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Navigation URL</label>
        <input class="form-control" value="{{ $financial_support_navigation_url }}" name="meta[financial_support_navigation_url]" type="text" placeholder="Enter navigation URL">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Join Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $join_title }}" name="meta[join_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12">
        <label class="form-label">BG Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $join_bg_image }}" type="hidden" name="meta[join_bg_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Join Navigation URL</label>
        <input class="form-control" value="{{ $join_navigation_url }}" name="meta[join_navigation_url]" type="text" placeholder="Enter navigation URL">
    </div>
</div>
