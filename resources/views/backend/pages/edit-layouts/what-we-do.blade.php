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

    $hero_items = $metaArray('hero_items');

    $donate_title = $metaValue('donate_title');
    $donate_description = $metaValue('donate_description');
    $volunteer_navigation = $metaValue('volunteer_navigation');
    $donate_navigation = $metaValue('donate_navigation');
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Breadcrumb Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $breadcrumb_title }}" name="meta[breadcrumb_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $breadcrumb_subtitle }}" name="meta[breadcrumb_subtitle]" type="text" placeholder="Enter subtitle">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Hero Section</h4>
    </div>

    <div class="hero-items-target w-100">
        @if(isset($hero_items['itration']) && is_array($hero_items['itration']))
            @foreach($hero_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[hero_items][itration][]" type="hidden">
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $hero_items['title'][$index] ?? '' }}" name="meta[hero_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <textarea name="meta[hero_items][description][]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $hero_items['description'][$index] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $hero_items['key_highlights'][$index] ?? '' }}" name="meta[hero_items][key_highlights][]" type="text" class="form-control" placeholder="Enter key highlights">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[hero_items][icon][]" class="selected-files" value="{{ $hero_items['icon'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $hero_items['call_to_action_text'][$index] ?? '' }}" name="meta[hero_items][call_to_action_text][]" type="text" class="form-control" placeholder="Enter call to action text">
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $hero_items['navigation_url'][$index] ?? '' }}" name="meta[hero_items][navigation_url][]" type="text" class="form-control" placeholder="Enter navigation URL">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[hero_items][image][]" class="selected-files" value="{{ $hero_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".hero-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[hero_items][itration][]" type="hidden">
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[hero_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <textarea name="meta[hero_items][description][]" class="form-control text-editor" rows="4" placeholder="Enter description"></textarea>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[hero_items][key_highlights][]" type="text" class="form-control" placeholder="Enter key highlights">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[hero_items][icon][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[hero_items][call_to_action_text][]" type="text" class="form-control" placeholder="Enter call to action text">
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[hero_items][navigation_url][]" type="text" class="form-control" placeholder="Enter navigation URL">
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[hero_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
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
        <label class="form-label">Description</label>
        <textarea name="meta[donate_description]" class="form-control" rows="3" placeholder="Enter description">{{ $donate_description }}</textarea>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Volunteer Navigation</label>
        <input class="form-control" value="{{ $volunteer_navigation }}" name="meta[volunteer_navigation]" type="text" placeholder="Enter navigation URL">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Donate Navigation</label>
        <input class="form-control" value="{{ $donate_navigation }}" name="meta[donate_navigation]" type="text" placeholder="Enter navigation URL">
    </div>
</div>
