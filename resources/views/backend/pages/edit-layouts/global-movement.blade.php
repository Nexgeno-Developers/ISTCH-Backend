@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');

    $leadership_title = $metaValue('leadership_title');
    $leadership_subtitle = $metaValue('leadership_subtitle');
    $leadership_image = $metaValue('leadership_image');
    $leadership_description = $metaValue('leadership_description');
    $leadership_key_points = $metaArray('leadership_key_points');

    $engage_title = $metaValue('engage_title');
    $engage_description = $metaValue('engage_description');
    $engage_items = $metaArray('engage_items');

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
        <h4 class="text-primary">Leadership Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $leadership_title }}" name="meta[leadership_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $leadership_subtitle }}" name="meta[leadership_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="col-md-12 d-none">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $leadership_image }}" type="hidden" name="meta[leadership_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[leadership_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $leadership_description }}</textarea>
    </div>

    <div class="leadership-key-points-target w-100 d-none ">
        @if(isset($leadership_key_points['itration']) && is_array($leadership_key_points['itration']))
            @foreach($leadership_key_points['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[leadership_key_points][itration][]" type="hidden">
                            <div class="col-md-12 form-group mb-2">
                                <input value="{{ $leadership_key_points['key_points'][$index] ?? '' }}" name="meta[leadership_key_points][key_points][]" type="text" class="form-control" placeholder="Enter key point">
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

    <button type="button" class="d-none mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".leadership-key-points-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[leadership_key_points][itration][]" type="hidden">
                    <div class="col-md-12 form-group mb-2">
                        <input value="" name="meta[leadership_key_points][key_points][]" type="text" class="form-control" placeholder="Enter key point">
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
        <h4 class="text-primary">Engage Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $engage_title }}" name="meta[engage_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[engage_description]" class="form-control" rows="4" placeholder="Enter description">{{ $engage_description }}</textarea>
    </div>

    <div class="engage-items-target w-100">
        @if(isset($engage_items['itration']) && is_array($engage_items['itration']))
            @foreach($engage_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[engage_items][itration][]" type="hidden">
                            <div class="col-md-6">
                                <label class="form-label">Icon</label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[engage_items][icon][]" class="selected-files" value="{{ $engage_items['icon'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[engage_items][image][]" class="selected-files" value="{{ $engage_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $engage_items['title'][$index] ?? '' }}" name="meta[engage_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $engage_items['description'][$index] ?? '' }}" name="meta[engage_items][description][]" type="text" class="form-control" placeholder="Enter description">
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $engage_items['button_text'][$index] ?? '' }}" name="meta[engage_items][button_text][]" type="text" class="form-control" placeholder="Enter button text">
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $engage_items['button_navigation'][$index] ?? '' }}" name="meta[engage_items][button_navigation][]" type="text" class="form-control" placeholder="Enter button navigation">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".engage-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[engage_items][itration][]" type="hidden">
                    <div class="col-md-6">
                        <label class="form-label">Icon</label>
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[engage_items][icon][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Image</label>
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[engage_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[engage_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[engage_items][description][]" type="text" class="form-control" placeholder="Enter description">
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[engage_items][button_text][]" type="text" class="form-control" placeholder="Enter button text">
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[engage_items][button_navigation][]" type="text" class="form-control" placeholder="Enter button navigation">
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
