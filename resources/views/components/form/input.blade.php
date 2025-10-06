@props(['name', 'label' => '', 'type' => 'text', 'col' => '12'])

<div class="col-md-{{ $col }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}" value="{{ old($name) }}">
    </div>
</div>
