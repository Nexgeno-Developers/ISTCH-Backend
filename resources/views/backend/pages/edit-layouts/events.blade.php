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

    $hero_title = $metaValue('hero_title');
    $hero_description = $metaValue('hero_description');
    $hero_post_categories = $metaArray('hero_post_categories');

    $upcoming_convocations_title = $metaValue('upcoming_convocations_title');
    $upcoming_convocations_description = $metaValue('upcoming_convocations_description');
    $upcoming_convocations_post_categories = $metaArray('upcoming_convocations_post_categories');

    $global_peace_title = $metaValue('global_peace_title');
    $global_peace_description = $metaValue('global_peace_description');
    $global_peace_image = $metaValue('global_peace_image');
    $global_peace_gallery_images = $metaValue('global_peace_gallery_images');

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
        <textarea name="meta[hero_description]" class="form-control" rows="4" placeholder="Enter description">{{ $hero_description }}</textarea>
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
        <textarea name="meta[upcoming_convocations_description]" class="form-control" rows="4" placeholder="Enter description">{{ $upcoming_convocations_description }}</textarea>
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
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $global_peace_image }}" type="hidden" name="meta[global_peace_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Gallery Images</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $global_peace_gallery_images }}" type="hidden" name="meta[global_peace_gallery_images]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
</div>
