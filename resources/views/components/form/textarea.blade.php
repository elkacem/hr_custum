@props(['name', 'label' => '', 'col' => '12'])

<div class="col-md-{{ $col }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <textarea
            class="form-control"
            id="{{ $name }}"
            name="{{ $name }}"
        >{{ old($name) }}</textarea>
    </div>
</div>
