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
                                <th>@lang('Nom')</th>
                                <th>@lang('Code')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($directions as $direction)
                                <tr>
                                    <td>{{ $loop->index + $directions->firstItem() }}</td>
                                    <td>{{ $direction->name }}</td>
                                    <td>{{ $direction->code }}</td>
                                    <td>
                                        <div class="button--group">
                                            <button class="btn btn-sm btn-outline--primary editBtn cuModalBtn"
                                                    data-resource="{{ $direction }}"
                                                    data-modal_title="@lang('Modifier Direction')"
                                                    type="button">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>

                                            <button class="btn btn-sm btn-outline--danger confirmationBtn ms-1"
                                                    data-action="{{ route('admin.direction.destroy', $direction->id) }}"
                                                    data-question="@lang('Êtes-vous sûr de vouloir supprimer cette direction ?')"
                                                    type="button">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted text-center">
                                        {{ __($emptyMessage ?? 'Aucune direction trouvée') }}
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($directions->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($directions) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cuModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.direction.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Nom')</label>
                            <input class="form-control" name="name" type="text" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Code')</label>
                            <input class="form-control" name="code" type="text">
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
            data-modal_title="@lang('Ajouter Direction')" type="button">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush
