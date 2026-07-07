<!-- Disclaimer | Disclaimer -->

@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');

    $quick_navigation_section_title = $metaValue('quick_navigation_section_title');
    $quick_navigation_items = $metaArray('quick_navigation_items');

    $disclaimer_content = $metaValue('disclaimer_content');
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
        <h4 class="text-primary">Quick Navigation Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Section Title</label>
        <input class="form-control" value="{{ $quick_navigation_section_title }}" name="meta[quick_navigation_section_title]" type="text" placeholder="Enter section title">
    </div>

    <div class="quick-navigation-items-target w-100">
        @if(isset($quick_navigation_items['itration']) && is_array($quick_navigation_items['itration']))
            @foreach($quick_navigation_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[quick_navigation_items][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[quick_navigation_items][image][]" class="selected-files" value="{{ $quick_navigation_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $quick_navigation_items['title'][$index] ?? '' }}" name="meta[quick_navigation_items][title][]" type="text" class="form-control" placeholder="Enter title">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".quick-navigation-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[quick_navigation_items][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[quick_navigation_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[quick_navigation_items][title][]" type="text" class="form-control" placeholder="Enter title">
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
        <span class="ml-2">Add Quick Navigation Item</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Disclaimer Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Content</label>
        <textarea name="meta[disclaimer_content]" class="form-control text-editor" rows="6" placeholder="Enter content">{{ $disclaimer_content }}</textarea>
    </div>
</div>
