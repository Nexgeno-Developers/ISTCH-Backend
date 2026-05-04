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
    $about_description = $metaValue('about_description');
    $about_quote_text = $metaValue('about_quote_text');

    $core_values_title = $metaValue('core_values_title');
    $core_values = $metaArray('core_values');

    $our_action_title = $metaValue('our_action_title');
    $our_action_subtitle = $metaValue('our_action_subtitle');
    $our_actions = $metaArray('our_actions');

    $events_title = $metaValue('events_title');
    $events_subtitle = $metaValue('events_subtitle');
    $events_posts = $metaArray('events_posts');

    $i_am_piece_title = $metaValue('i_am_piece_title');
    $i_am_piece_subtitle = $metaValue('i_am_piece_subtitle');

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
        <textarea name="meta[banner_description]" class="form-control" rows="3" placeholder="Enter description">{{ $banner_description }}</textarea>
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

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[about_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $about_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Quote Text</label>
        <input class="form-control" value="{{ $about_quote_text }}" name="meta[about_quote_text]" type="text" placeholder="Enter quote text">
    </div>
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

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Our Action Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $our_action_title }}" name="meta[our_action_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $our_action_subtitle }}" name="meta[our_action_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="our-actions-target w-100">
        @if(isset($our_actions['itration']) && is_array($our_actions['itration']))
            @foreach($our_actions['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[our_actions][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[our_actions][icon][]" class="selected-files" value="{{ $our_actions['icon'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $our_actions['title'][$index] ?? '' }}" name="meta[our_actions][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[our_actions][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $our_actions['description'][$index] ?? '' }}</textarea>
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".our-actions-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[our_actions][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[our_actions][icon][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[our_actions][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[our_actions][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
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
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Support Section</h4>
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
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
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $support_title }}" name="meta[support_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[support_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $support_description }}</textarea>
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
