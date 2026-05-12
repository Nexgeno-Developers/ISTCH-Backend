@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $banner_desktop_image = $metaValue('banner_desktop_image');
    $banner_mobile_image = $metaValue('banner_mobile_image');
    $banner_title = $metaValue('banner_title');
    $banner_subtitle = $metaValue('banner_subtitle');
    $banner_description = $metaValue('banner_description');
    $banner_join_navigation = $metaValue('banner_join_navigation');
    $banner_learn_more_navigation = $metaValue('banner_learn_more_navigation');
    $banner_active_community_count = $metaValue('banner_active_community_count');

    $highlights = $metaArray('highlights');

    $about_title = $metaValue('about_title');
    $about_subtitle = $metaValue('about_subtitle');
    $about_items = $metaArray('about_items');

    $why_peace_needs_title = $metaValue('why_peace_needs_title');
    $why_peace_needs_description = $metaValue('why_peace_needs_description');
    $why_peace_needs_image = $metaValue('why_peace_needs_image');

    $core_values_title = $metaValue('core_values_title');
    $core_values = $metaArray('core_values');

    $peace_building_title = $metaValue('peace_building_title');
    $peace_building_items = $metaArray('peace_building_items');

    $our_approach_title = $metaValue('our_approach_title');
    $our_approach_subtitle = $metaValue('our_approach_subtitle');
    $our_approach_items = $metaArray('our_approach_items');
    $our_approach_description = $metaValue('our_approach_description');
    $our_approach_images = $metaValue('our_approach_images');

    $what_we_do_title = $metaValue('what_we_do_title');
    $what_we_do_subtitle = $metaValue('what_we_do_subtitle');
    $what_we_do_items = $metaArray('what_we_do_items');

    $events_title = $metaValue('events_title');
    $events_subtitle = $metaValue('events_subtitle');
    $events_description = $metaValue('events_description');
    $events_posts = $metaArray('events_posts');

    $our_inspiration_title = $metaValue('our_inspiration_title');
    $our_inspiration_subtitle = $metaValue('our_inspiration_subtitle');
    $our_inspiration_key_highlights = $metaArray('our_inspiration_key_highlights');
    $our_inspiration_button_url = $metaValue('our_inspiration_button_url');

    $i_am_piece_title = $metaValue('i_am_piece_title');
    $i_am_piece_subtitle = $metaValue('i_am_piece_subtitle');
    $i_am_piece_items = $metaArray('i_am_piece_items');

    $support_title = $metaValue('support_title');
    $support_description = $metaValue('support_description');
    $support_image = $metaValue('support_image');
    $support_joined_text = $metaValue('support_joined_text');

    $testimonials_title = $metaValue('testimonials_title');
    $testimonials = $metaArray('testimonials');

    $engagement_title = $metaValue('engagement_title');
    $engagement_subtitle = $metaValue('engagement_subtitle');
    $engagement_ambassador_navigation_url = $metaValue('engagement_ambassador_navigation_url');
    $engagement_items = $metaArray('engagement_items');

    $postCategoryOptions = \App\Models\Category::query()
        ->where('company_id', $pageData->company_id)
        ->where('is_active', 1)
        ->orderBy('name')
        ->get(['id', 'name']);
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Banner Section</h4>
    </div>

    <div class="col-md-6">
        <label class="form-label">Desktop Banner</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $banner_desktop_image }}" type="hidden" name="meta[banner_desktop_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Mobile Banner</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $banner_mobile_image }}" type="hidden" name="meta[banner_mobile_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $banner_title }}" name="meta[banner_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $banner_subtitle }}" name="meta[banner_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[banner_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $banner_description }}</textarea>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Join Navigation</label>
        <input class="form-control" value="{{ $banner_join_navigation }}" name="meta[banner_join_navigation]" type="text" placeholder="Enter navigation URL">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Learn More Navigation</label>
        <input class="form-control" value="{{ $banner_learn_more_navigation }}" name="meta[banner_learn_more_navigation]" type="text" placeholder="Enter navigation URL">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Active Community Count</label>
        <input class="form-control" value="{{ $banner_active_community_count }}" name="meta[banner_active_community_count]" type="text" placeholder="Enter active community count">
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
        <h4 class="text-primary">Why Peace Needs Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $why_peace_needs_title }}" name="meta[why_peace_needs_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[why_peace_needs_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $why_peace_needs_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $why_peace_needs_image }}" type="hidden" name="meta[why_peace_needs_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">About Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $about_title }}" name="meta[about_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $about_subtitle }}" name="meta[about_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="about-items-target w-100">
        @if(isset($about_items['itration']) && is_array($about_items['itration']))
            @foreach($about_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[about_items][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[about_items][image][]" class="selected-files" value="{{ $about_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $about_items['title'][$index] ?? '' }}" name="meta[about_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[about_items][description][]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $about_items['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".about-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[about_items][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[about_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[about_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[about_items][description][]" class="form-control text-editor" rows="4" placeholder="Enter description"></textarea>
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
        <h4 class="text-primary">Core Values Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $core_values_title }}" name="meta[core_values_title]" type="text" placeholder="Enter title">
    </div>

    <div class="core-values-target w-100">
        @if(isset($core_values['itration']) && is_array($core_values['itration']))
            @foreach($core_values['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[core_values][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[core_values][icon][]" class="selected-files" value="{{ $core_values['icon'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $core_values['title'][$index] ?? '' }}" name="meta[core_values][title][]" type="text" class="form-control" placeholder="Enter title">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".core-values-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[core_values][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[core_values][icon][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[core_values][title][]" type="text" class="form-control" placeholder="Enter title">
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

{{-- Our Action Section --}}
<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Peace Building Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $peace_building_title }}" name="meta[peace_building_title]" type="text" placeholder="Enter title">
    </div>

    <div class="peace-building-items-target w-100">
        @if(isset($peace_building_items['itration']) && is_array($peace_building_items['itration']))
            @foreach($peace_building_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[peace_building_items][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[peace_building_items][image][]" class="selected-files" value="{{ $peace_building_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $peace_building_items['title'][$index] ?? '' }}" name="meta[peace_building_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[peace_building_items][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $peace_building_items['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-limit="3" data-target=".peace-building-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[peace_building_items][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[peace_building_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[peace_building_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[peace_building_items][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
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
        <h4 class="text-primary">Our Approach Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $our_approach_title }}" name="meta[our_approach_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $our_approach_subtitle }}" name="meta[our_approach_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="our-approach-items-target w-100">
        @if(isset($our_approach_items['itration']) && is_array($our_approach_items['itration']))
            @foreach($our_approach_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[our_approach_items][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[our_approach_items][image][]" class="selected-files" value="{{ $our_approach_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $our_approach_items['title'][$index] ?? '' }}" name="meta[our_approach_items][title][]" type="text" class="form-control" placeholder="Enter title">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-limit="6" data-target=".our-approach-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[our_approach_items][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[our_approach_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[our_approach_items][title][]" type="text" class="form-control" placeholder="Enter title">
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
        <label class="form-label">Description</label>
        <textarea name="meta[our_approach_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $our_approach_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Images</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $our_approach_images }}" type="hidden" name="meta[our_approach_images]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">What We Do Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $what_we_do_title }}" name="meta[what_we_do_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $what_we_do_subtitle }}" name="meta[what_we_do_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="what-we-do-items-target w-100">
        @if(isset($what_we_do_items['itration']) && is_array($what_we_do_items['itration']))
            @foreach($what_we_do_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[what_we_do_items][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[what_we_do_items][image][]" class="selected-files" value="{{ $what_we_do_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $what_we_do_items['title'][$index] ?? '' }}" name="meta[what_we_do_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[what_we_do_items][description][]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $what_we_do_items['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-limit="6" data-target=".what-we-do-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[what_we_do_items][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[what_we_do_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[what_we_do_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[what_we_do_items][description][]" class="form-control text-editor" rows="4" placeholder="Enter description"></textarea>
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
        <h4 class="text-primary">Events Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $events_title }}" name="meta[events_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $events_subtitle }}" name="meta[events_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[events_description]" class="form-control" rows="3" placeholder="Enter description">{{ $events_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Post / Event Dropdown</label>
        <select class="form-control select2" name="meta[events_posts][]" multiple>
            @foreach($postCategoryOptions as $category)
                <option value="{{ $category->id }}" {{ in_array($category->id, $events_posts) || in_array((string) $category->id, $events_posts) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Our Inspiration Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $our_inspiration_title }}" name="meta[our_inspiration_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $our_inspiration_subtitle }}" name="meta[our_inspiration_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="our-inspiration-key-highlights-target w-100">
        @if(isset($our_inspiration_key_highlights['itration']) && is_array($our_inspiration_key_highlights['itration']))
            @foreach($our_inspiration_key_highlights['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[our_inspiration_key_highlights][itration][]" type="hidden">
                            <div class="col-md-12 form-group mb-2">
                                <input value="{{ $our_inspiration_key_highlights['key_highlights'][$index] ?? '' }}" name="meta[our_inspiration_key_highlights][key_highlights][]" type="text" class="form-control" placeholder="Enter key highlight">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".our-inspiration-key-highlights-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[our_inspiration_key_highlights][itration][]" type="hidden">
                    <div class="col-md-12 form-group mb-2">
                        <input value="" name="meta[our_inspiration_key_highlights][key_highlights][]" type="text" class="form-control" placeholder="Enter key highlight">
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
        <label class="form-label">Get Inspired Button URL</label>
        <input class="form-control" value="{{ $our_inspiration_button_url }}" name="meta[our_inspiration_button_url]" type="text" placeholder="Enter button URL">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">I Am Piece Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $i_am_piece_title }}" name="meta[i_am_piece_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $i_am_piece_subtitle }}" name="meta[i_am_piece_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="i-am-piece-items-target w-100">
        @if(isset($i_am_piece_items['itration']) && is_array($i_am_piece_items['itration']))
            @foreach($i_am_piece_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[i_am_piece_items][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[i_am_piece_items][image][]" class="selected-files" value="{{ $i_am_piece_items['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $i_am_piece_items['title'][$index] ?? '' }}" name="meta[i_am_piece_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[i_am_piece_items][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $i_am_piece_items['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-limit="3" data-target=".i-am-piece-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[i_am_piece_items][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[i_am_piece_items][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[i_am_piece_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[i_am_piece_items][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
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
        <h4 class="text-primary">Support Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $support_title }}" name="meta[support_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[support_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $support_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Joined Text Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $support_image }}" type="hidden" name="meta[support_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
    
    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Joined Text</label>
        <input class="form-control" value="{{ $support_joined_text }}" name="meta[support_joined_text]" type="text" placeholder="Joined by 45,000+ donors">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Testimonials Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $testimonials_title }}" name="meta[testimonials_title]" type="text" placeholder="Enter title">
    </div>

    <div class="testimonials-target w-100">
        @if(isset($testimonials['itration']) && is_array($testimonials['itration']))
            @foreach($testimonials['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[testimonials][itration][]" type="hidden">
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $testimonials['name'][$index] ?? '' }}" name="meta[testimonials][name][]" type="text" class="form-control" placeholder="Enter name">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[testimonials][image][]" class="selected-files" value="{{ $testimonials['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $testimonials['rating'][$index] ?? '' }}" name="meta[testimonials][rating][]" type="number" min="0" max="5" step="0.1" class="form-control" placeholder="Enter rating">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[testimonials][review][]" class="form-control" rows="3" placeholder="Enter review">{{ $testimonials['review'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".testimonials-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[testimonials][itration][]" type="hidden">
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[testimonials][name][]" type="text" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[testimonials][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[testimonials][rating][]" type="number" min="0" max="5" step="0.1" class="form-control" placeholder="Enter rating">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[testimonials][review][]" class="form-control" rows="3" placeholder="Enter review"></textarea>
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

{{-- Engagement Section --}}
@if(false)
<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Engagement Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $engagement_title }}" name="meta[engagement_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $engagement_subtitle }}" name="meta[engagement_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Ambassador Navigation Url</label>
        <input class="form-control" value="{{ $engagement_ambassador_navigation_url }}" name="meta[engagement_ambassador_navigation_url]" type="text" placeholder="Enter navigation URL">
    </div>

    <div class="engagement-items-target w-100">
        @if(isset($engagement_items['itration']) && is_array($engagement_items['itration']))
            @foreach($engagement_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[engagement_items][itration][]" type="hidden">
                            <div class="col-md-12 form-group mb-2">
                                <input value="{{ $engagement_items['title'][$index] ?? '' }}" name="meta[engagement_items][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[engagement_items][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $engagement_items['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".engagement-items-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[engagement_items][itration][]" type="hidden">
                    <div class="col-md-12 form-group mb-2">
                        <input value="" name="meta[engagement_items][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[engagement_items][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
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
@endif
