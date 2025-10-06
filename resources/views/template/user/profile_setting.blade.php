@extends('template.layouts.master')
@section('content')
    <div class="pt-60 pb-60">
        <div class="profile-wrapper bg--body">
            <div class="profile-user mb-lg-0">
                <div class="thumb">
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="">
                </div>
                <div class="content">
                    <h6 class="title">{{ $user->fullname }}</h6>
                    <span class="subtitle">@lang('Username'): {{ $user->username }}</span>
                </div>
            </div>

            <div class="profile-form-area">
                <form class="profile-edit-form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('First Name')</label>
                            <input type="text" class="form-control form--control" name="firstname" value="{{ $user->firstname }}" required>
                        </div>
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('Last Name')</label>
                            <input type="text" class="form-control form--control" name="lastname" value="{{ $user->lastname }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('E-mail Address')</label>
                            <input class="form-control form--control" value="{{ $user->email }}" readonly>
                        </div>
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('Mobile Number')</label>
                            <input class="form-control form--control" value="{{ $user->mobile }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('Address')</label>
                            <input type="text" class="form-control form--control" name="address" value="{{ @$user->address }}">
                        </div>
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('State')</label>
                            <input type="text" class="form-control form--control" name="state" value="{{ @$user->state }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('Zip Code')</label>
                            <input type="text" class="form-control form--control" name="zip" value="{{ @$user->zip }}">
                        </div>
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('City')</label>
                            <input type="text" class="form-control form--control" name="city" value="{{ @$user->city }}">
                        </div>
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('Country')</label>
                            <input class="form-control form--control" value="{{ @$user->country_name }}" disabled>
                        </div>
                        <div class="form-group form--group col-sm-6">
                            <label class="form-label">@lang('Profile Picture')</label>
                            <input type="file" name="image" class="form-control form--control" accept="image/*">
                            <code>@lang('Image size') {{ getFileSize('userProfile') }}</code>
                        </div>
                    </div>
                    <div class="form--group w-100 col-md-6 mb-0 text-end mt-3">
                        <button type="submit" class="cmn--btn">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
