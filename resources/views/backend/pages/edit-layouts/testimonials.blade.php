@php
    $breadcrumb_title = $pageData->meta->where('meta_key', 'breadcrumb_title')->first()->meta_value ?? '';
    $testimonials_section_image = $pageData->meta->where('meta_key', 'testimonials_section_image')->first()->meta_value ?? '';

    $testimonials = json_decode($pageData->meta->where('meta_key', 'testimonials')->first()->meta_value ?? '[]', true);
    $testimonials = is_array($testimonials) ? $testimonials : [];
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
        <h4 class="text-primary">Testimonials Section</h4>
    </div>

    <div class="col-md-12">
        <label class="form-label">Section Image</label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $testimonials_section_image }}" type="hidden" name="meta[testimonials_section_image]" class="selected-files">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="testimonials-target w-100">
        @if(isset($testimonials['itration']) && is_array($testimonials['itration']))
            @foreach($testimonials['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <input value="{{ $index }}" name="meta[testimonials][itration][]" type="hidden">

                            <div class="col-md-6">
                                <label class="form-label">Image</label>
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

                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title</label>
                                <input value="{{ $testimonials['title'][$index] ?? '' }}" name="meta[testimonials][title][]" type="text" class="form-control" placeholder="Enter title">
                            </div>

                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description</label>
                                <textarea name="meta[testimonials][description][]" class="form-control" rows="3" placeholder="Enter description">{{ $testimonials['description'][$index] ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Subtitle</label>
                                <input value="{{ $testimonials['subtitle'][$index] ?? '' }}" name="meta[testimonials][subtitle][]" type="text" class="form-control" placeholder="Enter subtitle">
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

    <button
        type="button"
        class="mt-1 btn btn-soft-success btn-icon w-100"
        data-toggle="add-more"
        data-target=".testimonials-target"
        data-content='
            <div class="row remove-parent">
                <div class="col-md-11">
                    <div class="row">
                        <input value="data" name="meta[testimonials][itration][]" type="hidden">

                        <div class="col-md-6">
                            <label class="form-label">Image</label>
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

                        <div class="col-md-6 form-group mb-2">
                            <label class="form-label">Title</label>
                            <input value="" name="meta[testimonials][title][]" type="text" class="form-control" placeholder="Enter title">
                        </div>

                        <div class="col-md-12 form-group mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="meta[testimonials][description][]" class="form-control" rows="3" placeholder="Enter description"></textarea>
                        </div>

                        <div class="col-md-12 form-group mb-2">
                            <label class="form-label">Subtitle</label>
                            <input value="" name="meta[testimonials][subtitle][]" type="text" class="form-control" placeholder="Enter subtitle">
                        </div>
                    </div>
                </div>
                <div class="col-md-1 btn-dynamic-fields">
                    <button type="button" class="btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
        '>
        <i class="ti ti-plus"></i>
        <span class="ml-2">Add More</span>
    </button>
</div>
