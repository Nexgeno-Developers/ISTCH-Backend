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
    $breadcrumb_subtitle = $metaValue('breadcrumb_subtitle');

    $section_one_description = $metaValue('section_one_description');
    $section_one_image = $metaValue('section_one_image');
    $section_one_button_one_text = $metaValue('section_one_button_one_text');
    $section_one_button_one_url = $metaValue('section_one_button_one_url');
    $section_one_button_two_text = $metaValue('section_one_button_two_text');
    $section_one_button_two_url = $metaValue('section_one_button_two_url');

    $global_movements_title = $metaValue('global_movements_title');
    $global_movements_subtitle = $metaValue('global_movements_subtitle');
    $global_movements_description = $metaValue('global_movements_description');
    $global_movements_post_categories = $metaArray('global_movements_post_categories');
    $power_of_peace_title = $metaValue('power_of_peace_title');
    $power_of_peace_first_content = $metaValue('power_of_peace_first_content');
    $power_of_peace_second_content = $metaValue('power_of_peace_second_content');
    $power_of_peace_third_content = $metaValue('power_of_peace_third_content');
    $power_of_peace_fifth_content = $metaValue('power_of_peace_fifth_content');

    $where_peace_bg_image = $metaValue('where_peace_bg_image');
    $where_peace_title = $metaValue('where_peace_title');
    $where_peace_description = $metaValue('where_peace_description');
    $where_peace_button_text = $metaValue('where_peace_button_text');
    $where_peace_button_url = $metaValue('where_peace_button_url');
    $build_peace_items = $metaArray('build_peace_items');

    $cta_title = $metaValue('cta_title');
    $cta_donate_url = $metaValue('cta_donate_url');
    $cta_volunteer_url = $metaValue('cta_volunteer_url');

    $postCategoryOptions = \App\Models\Category::query()
        ->where('company_id', $pageData->company_id)
        ->where('is_active', 1)
        ->orderBy('name')
        ->get(['id', 'name']);
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

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">SubTitle</label>
        <input class="form-control" value="{{ $breadcrumb_subtitle }}" name="meta[breadcrumb_subtitle]" type="text" placeholder="Enter breadcrumb subtitle">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Section 1</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[section_one_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $section_one_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $section_one_image }}" type="hidden" name="meta[section_one_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button 1 Text</label>
        <input class="form-control" value="{{ $section_one_button_one_text }}" name="meta[section_one_button_one_text]" type="text" placeholder="Enter button text">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button 1 URL</label>
        <input class="form-control" value="{{ $section_one_button_one_url }}" name="meta[section_one_button_one_url]" type="text" placeholder="Enter button URL">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button 2 Text</label>
        <input class="form-control" value="{{ $section_one_button_two_text }}" name="meta[section_one_button_two_text]" type="text" placeholder="Enter button text">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button 2 URL</label>
        <input class="form-control" value="{{ $section_one_button_two_url }}" name="meta[section_one_button_two_url]" type="text" placeholder="Enter button URL">
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

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Post Categories</label>
        <select class="form-control select2" name="meta[global_movements_post_categories][]" multiple>
            @foreach($postCategoryOptions as $category)
                <option value="{{ $category->id }}" {{ in_array($category->id, $global_movements_post_categories) || in_array((string) $category->id, $global_movements_post_categories) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Power Of Peace</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $power_of_peace_title }}" name="meta[power_of_peace_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">First Content</label>
        <textarea name="meta[power_of_peace_first_content]" class="form-control text-editor" rows="4" placeholder="Enter first content">{{ $power_of_peace_first_content }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Second Content</label>
        <textarea name="meta[power_of_peace_second_content]" class="form-control text-editor" rows="4" placeholder="Enter second content">{{ $power_of_peace_second_content }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Third Content</label>
        <textarea name="meta[power_of_peace_third_content]" class="form-control text-editor" rows="4" placeholder="Enter third content">{{ $power_of_peace_third_content }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Fourth Content</label>
        <textarea name="meta[power_of_peace_fifth_content]" class="form-control text-editor" rows="4" placeholder="Enter fourth content">{{ $power_of_peace_fifth_content }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Where Peace Becomes Real</h4>
    </div>

    <div class="col-md-12">
        <label class="form-label">BG Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $where_peace_bg_image }}" type="hidden" name="meta[where_peace_bg_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
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

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button Text</label>
        <input class="form-control" value="{{ $where_peace_button_text }}" name="meta[where_peace_button_text]" type="text" placeholder="Enter button text">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button URL</label>
        <input class="form-control" value="{{ $where_peace_button_url }}" name="meta[where_peace_button_url]" type="text" placeholder="Enter button URL">
    </div>
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
        <label class="form-label">Button Text</label>
        <input class="form-control" value="{{ $cta_donate_url }}" name="meta[cta_donate_url]" type="text" placeholder="Enter button text">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Button URL</label>
        <input class="form-control" value="{{ $cta_volunteer_url }}" name="meta[cta_volunteer_url]" type="text" placeholder="Enter button URL">
    </div>
</div>
