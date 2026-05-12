<!-- I Am Peace | I Am Peace -->

@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');

    $section_one_title = $metaValue('section_one_title');
    $section_one_description = $metaValue('section_one_description');
    $commonwealth_description = $metaValue('commonwealth_description');
    $thought_leadership_description = $metaValue('thought_leadership_description');

    $global_movements_title = $metaValue('global_movements_title');
    $global_movements_subtitle = $metaValue('global_movements_subtitle');
    $global_movements_description = $metaValue('global_movements_description');
    $global_movements_first_image = $metaValue('global_movements_first_image');
    $global_movements_first_content = $metaValue('global_movements_first_content');
    $global_movements_second_image = $metaValue('global_movements_second_image');
    $global_movements_second_content = $metaValue('global_movements_second_content');

    $where_peace_title = $metaValue('where_peace_title');
    $where_peace_description = $metaValue('where_peace_description');
    $build_peace_items = $metaArray('build_peace_items');

    $cta_title = $metaValue('cta_title');
    $cta_donate_url = $metaValue('cta_donate_url');
    $cta_volunteer_url = $metaValue('cta_volunteer_url');
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Breadcrumb Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $breadcrumb_title }}" name="meta[breadcrumb_title]" type="text" placeholder="Enter breadcrumb title">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Section 1</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $section_one_title }}" name="meta[section_one_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[section_one_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $section_one_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Commonwealth</label>
        <textarea name="meta[commonwealth_description]" class="form-control text-editor" rows="4" placeholder="Enter Commonwealth content">{{ $commonwealth_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Global Peace Gatherings & Thought Leadership</label>
        <textarea name="meta[thought_leadership_description]" class="form-control text-editor" rows="4" placeholder="Enter thought leadership content">{{ $thought_leadership_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Mobilise Global Movements</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $global_movements_title }}" name="meta[global_movements_title]" type="text" placeholder="Mobilise Global Movements">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $global_movements_subtitle }}" name="meta[global_movements_subtitle]" type="text" placeholder="#IAMPEACE MOVEMENT">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[global_movements_description]" class="form-control" rows="4" placeholder="Enter description">{{ $global_movements_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">First Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $global_movements_first_image }}" type="hidden" name="meta[global_movements_first_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">First Content</label>
        <textarea name="meta[global_movements_first_content]" class="form-control text-editor" rows="4" placeholder="Enter first content">{{ $global_movements_first_content }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Second Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $global_movements_second_image }}" type="hidden" name="meta[global_movements_second_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Second Content</label>
        <textarea name="meta[global_movements_second_content]" class="form-control text-editor" rows="4" placeholder="Enter second content">{{ $global_movements_second_content }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2 mt-2">
        <label class="form-label">Where Peace Becomes Real Title</label>
        <input class="form-control" value="{{ $where_peace_title }}" name="meta[where_peace_title]" type="text" placeholder="Where Peace Becomes Real">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Where Peace Becomes Real Description</label>
        <textarea name="meta[where_peace_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $where_peace_description }}</textarea>
    </div>

    <div class="build-peace-items-target w-100">
        @if(isset($build_peace_items['itration']) && is_array($build_peace_items['itration']))
            @foreach($build_peace_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[build_peace_items][itration][]" type="hidden">
                            <div class="col-md-2 form-group mb-2">
                                <label class="form-label">Number</label>
                                <input value="{{ $build_peace_items['number'][$index] ?? '' }}" name="meta[build_peace_items][number][]" type="text" class="form-control" placeholder="Number">
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Title</label>
                                <input value="{{ $build_peace_items['title'][$index] ?? '' }}" name="meta[build_peace_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Description</label>
                                <textarea name="meta[build_peace_items][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $build_peace_items['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-limit="4" data-target=".build-peace-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[build_peace_items][itration][]" type="hidden">
                    <div class="col-md-2 form-group mb-2">
                        <label class="form-label">Number</label>
                        <input value="" name="meta[build_peace_items][number][]" type="text" class="form-control" placeholder="Number">
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <label class="form-label">Title</label>
                        <input value="" name="meta[build_peace_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <label class="form-label">Description</label>
                        <textarea name="meta[build_peace_items][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
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
        <span class="ml-2">Add Where Peace Item</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Footer Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $cta_title }}" name="meta[cta_title]" type="text" placeholder="Be Part of the Movement">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">DONATE NOW Button URL</label>
        <input class="form-control" value="{{ $cta_donate_url }}" name="meta[cta_donate_url]" type="text" placeholder="Enter donate button URL">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">VOLUNTEER WITH US Button URL</label>
        <input class="form-control" value="{{ $cta_volunteer_url }}" name="meta[cta_volunteer_url]" type="text" placeholder="Enter volunteer button URL">
    </div>
</div>
