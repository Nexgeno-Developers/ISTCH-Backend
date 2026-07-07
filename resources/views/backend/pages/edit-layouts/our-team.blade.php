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

    $board_title = $metaValue('board_title');
    $board_members = $metaArray('board_members');

    $patron_title = $metaValue('patron_title');
    $patron_members = $metaArray('patron_members');

    $charter_council_title = $metaValue('charter_council_title');
    $charter_council_members = $metaArray('charter_council_members');

    $team_section_title = $metaValue('team_section_title');
    $team_description = $metaValue('team_description');
    $team_members = $metaArray('team_members');

    $footer_title = $metaValue('footer_title');
    $footer_button_text = $metaValue('footer_button_text');
    $footer_button_url = $metaValue('footer_button_url');
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
        <h4 class="text-primary">The Board Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $board_title }}" name="meta[board_title]" type="text" placeholder="Enter title">
    </div>

    <div class="board-members-target w-100">
        @if(isset($board_members['itration']) && is_array($board_members['itration']))
            @foreach($board_members['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[board_members][itration][]" type="hidden">
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[board_members][image][]" class="selected-files" value="{{ $board_members['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <textarea name="meta[board_members][description][]" class="form-control" rows="3" placeholder="Enter content">{{ $board_members['description'][$index] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <input value="{{ $board_members['name'][$index] ?? '' }}" name="meta[board_members][name][]" type="text" class="form-control" placeholder="Enter name">
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <input value="{{ $board_members['designation'][$index] ?? '' }}" name="meta[board_members][designation][]" type="text" class="form-control" placeholder="Enter designation">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".board-members-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[board_members][itration][]" type="hidden">
                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[board_members][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <textarea name="meta[board_members][description][]" class="form-control" rows="3" placeholder="Enter content"></textarea>
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <input value="" name="meta[board_members][name][]" type="text" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <input value="" name="meta[board_members][designation][]" type="text" class="form-control" placeholder="Enter designation">
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
        <span class="ml-2">Add Board Member</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Our Patron Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $patron_title }}" name="meta[patron_title]" type="text" placeholder="Enter title">
    </div>

    <div class="patron-members-target w-100">
        @if(isset($patron_members['itration']) && is_array($patron_members['itration']))
            @foreach($patron_members['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[patron_members][itration][]" type="hidden">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[patron_members][image][]" class="selected-files" value="{{ $patron_members['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $patron_members['name'][$index] ?? '' }}" name="meta[patron_members][name][]" type="text" class="form-control" placeholder="Enter name">
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $patron_members['designation'][$index] ?? '' }}" name="meta[patron_members][designation][]" type="text" class="form-control" placeholder="Enter designation">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".patron-members-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[patron_members][itration][]" type="hidden">
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[patron_members][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[patron_members][name][]" type="text" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[patron_members][designation][]" type="text" class="form-control" placeholder="Enter designation">
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
        <span class="ml-2">Add Patron</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Charter Council Members Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $charter_council_title }}" name="meta[charter_council_title]" type="text" placeholder="Enter title">
    </div>

    <div class="charter-council-members-target w-100">
        @if(isset($charter_council_members['itration']) && is_array($charter_council_members['itration']))
            @foreach($charter_council_members['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[charter_council_members][itration][]" type="hidden">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[charter_council_members][image][]" class="selected-files" value="{{ $charter_council_members['image'][$index] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <input value="{{ $charter_council_members['name'][$index] ?? '' }}" name="meta[charter_council_members][name][]" type="text" class="form-control" placeholder="Enter name">
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

    <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100" data-toggle="add-more" data-target=".charter-council-members-target" data-content='
        <div class="row remove-parent">
            <div class="col-md-11">
                <div class="row">
                    <input value="data" name="meta[charter_council_members][itration][]" type="hidden">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" name="meta[charter_council_members][image][]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <input value="" name="meta[charter_council_members][name][]" type="text" class="form-control" placeholder="Enter name">
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
        <span class="ml-2">Add Charter Council Member</span>
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Our Team Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title</label>
        <input class="form-control" value="{{ $team_section_title }}" name="meta[team_section_title]" type="text" placeholder="Enter title">
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description</label>
        <textarea name="meta[team_description]" class="form-control" rows="4" placeholder="Enter description">{{ $team_description }}</textarea>
    </div>

    <div class="team-members-target w-100">
        @if(isset($team_members['itration']) && is_array($team_members['itration']))
            @foreach($team_members['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[team_members][itration][]" type="hidden">
                            <div class="col-md-4">
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
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $team_members['name'][$index] ?? ($team_members['title'][$index] ?? '') }}" name="meta[team_members][name][]" type="text" class="form-control" placeholder="Enter name">
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <input value="{{ $team_members['designation'][$index] ?? '' }}" name="meta[team_members][designation][]" type="text" class="form-control" placeholder="Enter designation">
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
                    <div class="col-md-4">
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
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[team_members][name][]" type="text" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <input value="" name="meta[team_members][designation][]" type="text" class="form-control" placeholder="Enter designation">
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
