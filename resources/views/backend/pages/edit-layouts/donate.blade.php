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

    $financial_support_title = $metaValue('financial_support_title');
    $financial_support_description = $metaValue('financial_support_description');
    $financial_support_content = $metaValue('financial_support_content');
    $financial_support_image = $metaValue('financial_support_image');
    $financial_support_items = $metaArray('financial_support_items');

    if (!isset($financial_support_items['image']) && isset($financial_support_items['icon'])) {
        $financial_support_items['image'] = $financial_support_items['icon'];
    }

    $join_title = $metaValue('join_title');
    $join_navigation_text = $metaValue('join_navigation_text');
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
        <h4 class="text-primary">Financial Support Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $financial_support_title }}" name="meta[financial_support_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[financial_support_description]" class="form-control" rows="4" placeholder="Enter description">{{ $financial_support_description }}</textarea>
    </div>

    <div class="financial-support-items-target w-100">
        @if(isset($financial_support_items['itration']) && is_array($financial_support_items['itration']))
            @foreach($financial_support_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[financial_support_items][itration][]" type="hidden">

                            <div class="col-md-12">
                                <label class="form-label">Image</label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input value="{{ $financial_support_items['image'][$index] ?? '' }}" type="hidden" name="meta[financial_support_items][image][]" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>

                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Title</label>
                                <input class="form-control" value="{{ $financial_support_items['title'][$index] ?? '' }}" name="meta[financial_support_items][title][]" type="text" placeholder="Enter title">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".financial-support-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[financial_support_items][itration][]" type="hidden">

                    <div class="col-md-12">
                        <label class="form-label">Image</label>
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input value="" type="hidden" name="meta[financial_support_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>

                    <div class="col-md-12 form-group mb-2">
                        <label class="form-label">Title</label>
                        <input class="form-control" value="" name="meta[financial_support_items][title][]" type="text" placeholder="Enter title">
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

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Content</label>
        <textarea name="meta[financial_support_content]" class="form-control text-editor" rows="4" placeholder="Enter content">{{ $financial_support_content }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $financial_support_image }}" type="hidden" name="meta[financial_support_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
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

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Join Navigation Text</label>
        <input class="form-control" value="{{ $join_navigation_text }}" name="meta[join_navigation_text]" type="text" placeholder="Enter navigation text">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Join Navigation URL</label>
        <input class="form-control" value="{{ $join_navigation_url }}" name="meta[join_navigation_url]" type="text" placeholder="Enter navigation URL">
    </div>
</div>
