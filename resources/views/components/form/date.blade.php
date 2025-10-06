@props(['name', 'label' => '', 'col' => '12'])

<div class="col-md-{{ $col }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ __($label) }}</label>
        <input type="text"
               name="{{ $name }}"
               id="{{ $name }}"
               value="{{ old($name) }}"
               class="form-control date-input-{{ $name }}" />
    </div>
</div>

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof flatpickr !== "undefined") {
                flatpickr(".date-input-{{ $name }}", {
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    locale: "fr" // optional French
                });
            } else {
                console.error("Flatpickr is not loaded");
            }
        });
    </script>
@endpush
