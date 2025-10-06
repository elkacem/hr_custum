@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Role')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($admins as $admin)
                                <tr>
                                    <td>{{ $loop->index + $admins->firstItem() }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->username }}</td>
                                    <td>
                                        @switch($admin->role)
                                            @case('admin') <span class="badge badge--primary">Admin</span> @break
                                            @case('agent') <span class="badge badge--info">Agent</span> @break
                                            @case('payment_service') <span class="badge badge--warning">Chef Service Paiement</span> @break
                                            @case('payment_department') <span class="badge badge--dark">Chef Département Paiement</span> @break
                                            @case('other_structure') <span class="badge badge--success">Responsable Structure</span> @break
                                            @default {{ ucfirst($admin->role) }}
                                        @endswitch
                                    </td>
                                    <td>
                                        {!! $admin->status
                                            ? '<span class="badge badge--success">Active</span>'
                                            : '<span class="badge badge--danger">Disabled</span>' !!}
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <button class="btn btn-sm btn-outline--primary editBtn cuModalBtn"
                                                    data-resource="{{ $admin }}"
                                                    data-modal_title="@lang('Edit User')"
                                                    data-has_status="1" type="button">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>
                                            @if ($admin->status == 0)
                                                <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-action="{{ route('admin.moderator.status', $admin->id) }}"
                                                        data-question="@lang('Are you sure to enable this user')?" type="button">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.moderator.status', $admin->id) }}"
                                                        data-question="@lang('Are you sure to disable this user')?" type="button">
                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($admins->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($admins) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.moderator.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input class="form-control" name="email" type="email" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Username')</label>
                            <input class="form-control" name="username" type="text" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Role')</label>
                            <select class="form-control" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="agent">Agent</option>
                                <option value="payment_service">Chef Service Paiement</option>
                                <option value="payment_department">Chef Département Paiement</option>
                                <option value="other_structure">Autre Structure</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Password')</label>
                            <input class="form-control" name="password" type="password">
                            <small class="text-muted">@lang('Leave blank if not changing')</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
    <button class="btn btn-sm btn-outline--primary cuModalBtn"
            data-modal_title="@lang('Add New User')" type="button">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush
