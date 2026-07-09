<!-- Global Summits & Events | Events -->

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
    $hero_bottom_title = $metaValue('hero_bottom_title');
    $hero_description = $metaValue('hero_description');
    $hero_post_categories = $metaArray('hero_post_categories');

    $upcoming_convocations_title = $metaValue('upcoming_convocations_title');
    $upcoming_convocations_description = $metaValue('upcoming_convocations_description');
    $upcoming_convocations_bottom_title = $metaValue('upcoming_convocations_bottom_title');
    $upcoming_convocations_post_categories = $metaArray('upcoming_convocations_post_categories');

    $global_peace_title = $metaValue('global_peace_title');
    $global_peace_description = $metaValue('global_peace_description');
    $global_peace_image = $metaValue('global_peace_image');

    $thought_title = $metaValue('thought_title');
    $thought_subtitle = $metaValue('thought_subtitle');
    $thought_video_urls = $metaArray('thought_video_urls');
    $thought_know_more_button_url = $metaValue('thought_know_more_button_url');
    $thought_images = $metaValue('thought_images');
    $thought_description = $metaValue('thought_description');
    $thought_partner_with_us_button_url = $metaValue('thought_partner_with_us_button_url');

    $footer_title = $metaValue('footer_title');
    $footer_button_text = $metaValue('footer_button_text');
    $footer_button_url = $metaValue('footer_button_url');

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
        <input class="form-control" value="{{ $breadcrumb_title }}" name="meta[breadcrumb_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $breadcrumb_subtitle }}" name="meta[breadcrumb_subtitle]" type="text" placeholder="Enter subtitle">
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
        <textarea name="meta[hero_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $hero_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Bottom Title</label>
        <input class="form-control" value="{{ $hero_bottom_title }}" name="meta[hero_bottom_title]" type="text" placeholder="Enter bottom title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Post Categories</label>
        <select class="form-control select2" name="meta[hero_post_categories][]" multiple>
            @foreach($postCategoryOptions as $category)
                <option value="{{ $category->id }}" {{ in_array($category->id, $hero_post_categories) || in_array((string) $category->id, $hero_post_categories) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Upcoming Convocations Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $upcoming_convocations_title }}" name="meta[upcoming_convocations_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[upcoming_convocations_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $upcoming_convocations_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Bottom Title</label>
        <input class="form-control" value="{{ $upcoming_convocations_bottom_title }}" name="meta[upcoming_convocations_bottom_title]" type="text" placeholder="Enter bottom title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Post Categories</label>
        <select class="form-control select2" name="meta[upcoming_convocations_post_categories][]" multiple>
            @foreach($postCategoryOptions as $category)
                <option value="{{ $category->id }}" {{ in_array($category->id, $upcoming_convocations_post_categories) || in_array((string) $category->id, $upcoming_convocations_post_categories) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Global Peace Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $global_peace_title }}" name="meta[global_peace_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[global_peace_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $global_peace_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Images</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $global_peace_image }}" type="hidden" name="meta[global_peace_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Thought Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $thought_title }}" name="meta[thought_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $thought_subtitle }}" name="meta[thought_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="thought-video-urls-target w-100">
        @if(isset($thought_video_urls['itration']) && is_array($thought_video_urls['itration']))
            @foreach($thought_video_urls['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[thought_video_urls][itration][]" type="hidden">
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Video URL</label>
                                <input value="{{ $thought_video_urls['url'][$index] ?? '' }}" name="meta[thought_video_urls][url][]" type="text" class="form-control" placeholder="Enter video URL">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-limit="3" data-target=".thought-video-urls-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[thought_video_urls][itration][]" type="hidden">
                    <div class="col-md-12 form-group mb-2">
                        <label class="form-label">Video URL</label>
                        <input value="" name="meta[thought_video_urls][url][]" type="text" class="form-control" placeholder="Enter video URL">
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
        <span class="ml-2">Add Video URL</span>
    </button>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Know More Button URL</label>
        <input class="form-control" value="{{ $thought_know_more_button_url }}" name="meta[thought_know_more_button_url]" type="text" placeholder="Enter button URL">
    </div>

    <div class="col-md-12">
        <label class="form-label">Images</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $thought_images }}" type="hidden" name="meta[thought_images]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[thought_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $thought_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Partner With Us Button URL</label>
        <input class="form-control" value="{{ $thought_partner_with_us_button_url }}" name="meta[thought_partner_with_us_button_url]" type="text" placeholder="Enter button URL">
    </div>
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
