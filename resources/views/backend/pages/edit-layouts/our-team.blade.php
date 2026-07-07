<!-- Our Team | Our Team -->

@php
    $metaValue = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $metaArray = function ($key) use ($metaValue) {
        $value = json_decode($metaValue($key, '[]'), true);
        return is_array($value) ? $value : [];
    };

    $breadcrumb_title = $metaValue('breadcrumb_title');

    $board_image = $metaValue('board_image');
    $board_title = $metaValue('board_title');
    $board_subtitle = $metaValue('board_subtitle');

    $patron_image = $metaValue('patron_image');
    $patron_title = $metaValue('patron_title');
    $patron_subtitle = $metaValue('patron_subtitle');

    $team_section_title = $metaValue('team_section_title');
    $team_members = $metaArray('team_members');

    $advisory_section_title = $metaValue('advisory_section_title');
    $advisory_description = $metaValue('advisory_description');
    $advisory_members = $metaArray('advisory_members');
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
        <h4 class="text-primary">Section 1 - Leadership</h4>
    </div>

    <div class="col-md-12">
        <label class="form-label">Board Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $board_image }}" type="hidden" name="meta[board_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Board Title</label>
        <input class="form-control" value="{{ $board_title }}" name="meta[board_title]" type="text" placeholder="Enter board title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Board Subtitle</label>
        <input class="form-control" value="{{ $board_subtitle }}" name="meta[board_subtitle]" type="text" placeholder="Enter board subtitle">
    </div>

    <div class="col-md-12">
        <label class="form-label">Patron Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $patron_image }}" type="hidden" name="meta[patron_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Patron Title</label>
        <input class="form-control" value="{{ $patron_title }}" name="meta[patron_title]" type="text" placeholder="Enter patron title">
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Patron Subtitle</label>
        <input class="form-control" value="{{ $patron_subtitle }}" name="meta[patron_subtitle]" type="text" placeholder="Enter patron subtitle">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Section 2 - Team Members</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Section Title</label>
        <input class="form-control" value="{{ $team_section_title }}" name="meta[team_section_title]" type="text" placeholder="Enter section title">
    </div>

    <div class="team-members-target w-100">
        @if(isset($team_members['itration']) && is_array($team_members['itration']))
            @foreach($team_members['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[team_members][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[team_members][image][]" class="selected-files" value="{{ $team_members['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $team_members['title'][$index] ?? '' }}" name="meta[team_members][title][]" type="text" class="form-control" placeholder="Enter title">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".team-members-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[team_members][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[team_members][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[team_members][title][]" type="text" class="form-control" placeholder="Enter title">
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
        <span class="ml-2">Add Team Member</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Section 3 - Advisory / Members</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Section Title</label>
        <input class="form-control" value="{{ $advisory_section_title }}" name="meta[advisory_section_title]" type="text" placeholder="Enter section title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[advisory_description]" class="form-control" rows="4" placeholder="Enter description">{{ $advisory_description }}</textarea>
    </div>

    <div class="advisory-members-target w-100">
        @if(isset($advisory_members['itration']) && is_array($advisory_members['itration']))
            @foreach($advisory_members['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[advisory_members][itration][]" type="hidden">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[advisory_members][image][]" class="selected-files" value="{{ $advisory_members['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $advisory_members['name'][$index] ?? '' }}" name="meta[advisory_members][name][]" type="text" class="form-control" placeholder="Enter name">
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $advisory_members['designation'][$index] ?? '' }}" name="meta[advisory_members][designation][]" type="text" class="form-control" placeholder="Enter designation">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".advisory-members-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[advisory_members][itration][]" type="hidden">
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[advisory_members][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[advisory_members][name][]" type="text" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[advisory_members][designation][]" type="text" class="form-control" placeholder="Enter designation">
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
        <span class="ml-2">Add Advisory Member</span>
    </button>
</div>
