@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');

    $genesis_title = $metaValue('genesis_title');
    $genesis_subtitle = $metaValue('genesis_subtitle');
    $genesis_image = $metaValue('genesis_image');
    $genesis_description = $metaValue('genesis_description');

    $quote_title = $metaValue('quote_title');

    $vision_title = $metaValue('vision_title');
    $vision_description = $metaValue('vision_description');
    $mission_title = $metaValue('mission_title');
    $mission_description = $metaValue('mission_description');

    $core_values_title = $metaValue('core_values_title');
    $core_values_subtitle = $metaValue('core_values_subtitle');
    $core_values = $metaArray('core_values');

    $teams_title = $metaValue('teams_title');
    $teams_subtitle = $metaValue('teams_subtitle');
    $teams_description = $metaValue('teams_description');
    $teams = $metaArray('teams');

    $donate_title = $metaValue('donate_title');
    $donate_navigation = $metaValue('donate_navigation');
    $volunteer_navigation = $metaValue('volunteer_navigation');
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
        <h4 class="text-primary">Genesis Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $genesis_title }}" name="meta[genesis_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $genesis_subtitle }}" name="meta[genesis_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="col-md-12">
        <label class="form-label">Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $genesis_image }}" type="hidden" name="meta[genesis_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[genesis_description]" class="form-control text-editor" rows="4" placeholder="Enter description">{{ $genesis_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Quote Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $quote_title }}" name="meta[quote_title]" type="text" placeholder="Enter title">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Vision &amp; Mission Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Vision Title</label>
        <input class="form-control" value="{{ $vision_title }}" name="meta[vision_title]" type="text" placeholder="Enter vision title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Vision Description</label>
        <textarea name="meta[vision_description]" class="form-control text-editor" rows="4" placeholder="Enter vision description">{{ $vision_description }}</textarea>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Mission Title</label>
        <input class="form-control" value="{{ $mission_title }}" name="meta[mission_title]" type="text" placeholder="Enter mission title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Mission Description</label>
        <textarea name="meta[mission_description]" class="form-control text-editor" rows="4" placeholder="Enter mission description">{{ $mission_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Core Values Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $core_values_title }}" name="meta[core_values_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $core_values_subtitle }}" name="meta[core_values_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="core-values-target w-100">
        @if(isset($core_values['itration']) && is_array($core_values['itration']))
            @foreach($core_values['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[core_values][itration][]" type="hidden">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[core_values][image][]" class="selected-files" value="{{ $core_values['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-8 form-group mb-2">
                                <input value="{{ $core_values['title'][$index] ?? '' }}" name="meta[core_values][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <textarea name="meta[core_values][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $core_values['description'][$index] ?? '' }}</textarea>
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
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[core_values][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-8 form-group mb-2">
                        <input value="" name="meta[core_values][title][]" type="text" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="col-md-12 form-group mb-2">
                        <textarea name="meta[core_values][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
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
        <h4 class="text-primary">Teams Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $teams_title }}" name="meta[teams_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle</label>
        <input class="form-control" value="{{ $teams_subtitle }}" name="meta[teams_subtitle]" type="text" placeholder="Enter subtitle">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[teams_description]" class="form-control" rows="3" placeholder="Enter description">{{ $teams_description }}</textarea>
    </div>

    <div class="teams-target w-100">
        @if(isset($teams['itration']) && is_array($teams['itration']))
            @foreach($teams['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[teams][itration][]" type="hidden">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[teams][image][]" class="selected-files" value="{{ $teams['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $teams['name'][$index] ?? '' }}" name="meta[teams][name][]" type="text" class="form-control" placeholder="Enter name">
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $teams['designation'][$index] ?? '' }}" name="meta[teams][designation][]" type="text" class="form-control" placeholder="Enter designation">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".teams-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[teams][itration][]" type="hidden">
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[teams][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[teams][name][]" type="text" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[teams][designation][]" type="text" class="form-control" placeholder="Enter designation">
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

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Donate Navigation</label>
        <input class="form-control" value="{{ $donate_navigation }}" name="meta[donate_navigation]" type="text" placeholder="Enter navigation URL">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Volunteer Navigation</label>
        <input class="form-control" value="{{ $volunteer_navigation }}" name="meta[volunteer_navigation]" type="text" placeholder="Enter navigation URL">
    </div>
</div>
