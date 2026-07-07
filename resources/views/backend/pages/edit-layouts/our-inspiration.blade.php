<!-- Our Inspiration | Our Inspiration -->

@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');
    $breadcrumb_subtitle = $metaValue('breadcrumb_subtitle');
    $hero_title = $metaValue('hero_title');

    $vision_image = $metaValue('vision_image');
    $vision_title = $metaValue('vision_title');
    $vision_description = $metaValue('vision_description');
    $vision_person_image = $metaValue('vision_person_image');

    $impact_items = $metaArray('impact_items');

    $philosophy_title = $metaValue('philosophy_title');
    $philosophy_subtitle = $metaValue('philosophy_subtitle');
    $philosophy_items = $metaArray('philosophy_items');

    $footer_title = $metaValue('footer_title');
    $footer_button_text = $metaValue('footer_button_text');
    $footer_button_url = $metaValue('footer_button_url');
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Hero / Breadcrumb Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Breadcrumb Title</label>
        <input class="form-control" value="{{ $breadcrumb_title }}" name="meta[breadcrumb_title]" type="text" placeholder="Enter breadcrumb title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Breadcrumb Subtitle</label>
        <input class="form-control" value="{{ $breadcrumb_subtitle }}" name="meta[breadcrumb_subtitle]" type="text" placeholder="Enter breadcrumb subtitle">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Vision Section</h4>
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $vision_image }}" type="hidden" name="meta[vision_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $vision_title }}" name="meta[vision_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[vision_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $vision_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Person Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $vision_person_image }}" type="hidden" name="meta[vision_person_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Impact Section</h4>
    </div>

    <div class="impact-items-target w-100">
        @if(isset($impact_items['itration']) && is_array($impact_items['itration']))
            @foreach($impact_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[impact_items][itration][]" type="hidden">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[impact_items][image][]" class="selected-files" value="{{ $impact_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $impact_items['title'][$index] ?? '' }}" name="meta[impact_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <textarea name="meta[impact_items][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $impact_items['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".impact-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[impact_items][itration][]" type="hidden">
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[impact_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[impact_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <textarea name="meta[impact_items][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
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
        <span class="ml-2">Add Impact Item</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Living Philosophy Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $philosophy_title }}" name="meta[philosophy_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <textarea name="meta[philosophy_subtitle]" class="form-control text-editor" rows="3" placeholder="Enter subtitle">{{ $philosophy_subtitle }}</textarea>
    </div>

    <div class="philosophy-items-target w-100">
        @if(isset($philosophy_items['itration']) && is_array($philosophy_items['itration']))
            @foreach($philosophy_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[philosophy_items][itration][]" type="hidden">
                            <div class="col-md-2 form-group mb-2">
                                <input value="{{ $philosophy_items['number'][$index] ?? '' }}" name="meta[philosophy_items][number][]" type="text" class="form-control" placeholder="Number">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[philosophy_items][icon][]" class="selected-files" value="{{ $philosophy_items['icon'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <input value="{{ $philosophy_items['title'][$index] ?? '' }}" name="meta[philosophy_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <textarea name="meta[philosophy_items][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $philosophy_items['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-limit="3" data-target=".philosophy-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[philosophy_items][itration][]" type="hidden">
                    <div class="col-md-2 form-group mb-2">
                        <input value="" name="meta[philosophy_items][number][]" type="text" class="form-control" placeholder="Number">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[philosophy_items][icon][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <input value="" name="meta[philosophy_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <textarea name="meta[philosophy_items][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
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
        <span class="ml-2">Add Philosophy Item</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Footer Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $footer_title }}" name="meta[footer_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button Text</label>
        <input class="form-control" value="{{ $footer_button_text }}" name="meta[footer_button_text]" type="text" placeholder="Enter button text">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button URL</label>
        <input class="form-control" value="{{ $footer_button_url }}" name="meta[footer_button_url]" type="text" placeholder="Enter button URL">
    </div>
</div>
