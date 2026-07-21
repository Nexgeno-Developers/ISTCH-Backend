@extends('backend.layouts.app')

@section('content')
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0">{{ $moduleName }} / Create</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0 fs-13">
                <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Back to {{ $moduleName }} list</a></li>
            </ol>
        </div>
    </div>

    <form class="form" action="{{ route('companies.store') }}" method="POST">
        @include('backend.includes.alert-message')
        @csrf
        <div class="row">
            <!-- Company Details -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">Primary Information</h5>
                        <div class="mb-3 form-group">
                            <label for="company-name" class="form-label">Company Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="company-name" name="name" value="{{ old('name') }}"
                                class="form-control" placeholder="e.g : Sample Company" required>
                        </div>
                        <div class="mb-2 form-group clearfix">
                            <label for="company-breadcrumb" class="form-label">{{ __('Breadcrumb') }} <span
                                    class="text-danger">*</span></label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" id="company-breadcrumb" name="meta[breadcrumb]"
                                    value="{{ old('meta.breadcrumb') }}" class="selected-files" required>
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="mb-2 form-group clearfix">
                            <label for="company-logo" class="form-label">{{ __('Logo') }} <span
                                    class="text-danger">*</span></label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" id="company-logo" name="logo" value="{{ old('logo') }}"
                                    class="selected-files" required>
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="mb-2 form-group clearfix">
                            <label for="company-footer-logo" class="form-label">{{ __('Footer Logo Image') }}</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                <input type="hidden" id="company-footer-logo" name="footer_logo_image"
                                    value="{{ old('footer_logo_image') }}" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="mb-3 form-group">
                            <label for="company-short-description" class="form-label">{{ __('Short Description') }}</label>
                            <textarea id="company-short-description" name="short_description" class="form-control" rows="3">{{ old('short_description') }}</textarea>
                        </div>

                        <div class="mb-3 mt-1 form-group">
                            <label for="company-website" class="form-label">Website <span
                                    class="text-danger">*</span></label>
                            <input type="url" id="company-website" name="website" value="{{ old('website') }}"
                                class="form-control" placeholder="" required>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="company-address" class="form-label">Address <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="company-address" name="address" value="{{ old('address') }}"
                                class="form-control" placeholder="e.g : 123 Main St, City, Country" required>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="company-phone" class="form-label">Phone <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="company-phone" name="phone" value="{{ old('phone') }}"
                                class="form-control" placeholder="" required>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="company-email" class="form-label">Enquiry Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" id="company-email" name="email" value="{{ old('email') }}"
                                class="form-control" placeholder="" required>
                        </div>

                    </div>
                </div>



            </div>
        </div>

        <!-- Secondary Meta Data -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Social Links</h5>
                    <div class="mb-3 form-group">
                        <label for="meta-instagram" class="form-label">Instagram URL</label>
                        <input type="url" class="form-control" id="meta-instagram" name="meta[instagram_url]"
                            value="{{ old('meta.instagram_url') }}" placeholder="Enter Instagram URL">
                    </div>
                    <div class="mb-3 form-group">
                        <label for="meta-x" class="form-label">X URL</label>
                        <input type="url" class="form-control" id="meta-x" name="meta[x_url]"
                            value="{{ old('meta.x_url') }}" placeholder="Enter X URL">
                    </div>
                    <div class="mb-3 form-group">
                        <label for="meta-linkedin" class="form-label">LinkedIn URL</label>
                        <input type="url" class="form-control" id="meta-linkedin" name="meta[linkedin_url]"
                            value="{{ old('meta.linkedin_url') }}" placeholder="Enter LinkedIn URL">
                    </div>
                    <div class="mb-3 form-group">
                        <label for="meta-facebook" class="form-label">Facebook URL</label>
                        <input type="url" class="form-control" id="meta-facebook" name="meta[facebook_url]"
                            value="{{ old('meta.facebook_url') }}" placeholder="Enter Facebook URL">
                    </div>
                    <div class="mb-3 form-group">
                        <label for="meta-youtube" class="form-label">YouTube URL</label>
                        <input type="url" class="form-control" id="meta-youtube" name="meta[youtube_url]"
                            value="{{ old('meta.youtube_url') }}" placeholder="Enter YouTube URL">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary w-100">Create</button>
            </div>
        </div>
        </div>
    </form>

    <script defer>
        initValidate('.form');
    </script>
@endsection
