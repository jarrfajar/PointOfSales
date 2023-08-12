<div class="col">
    <div class="form-group row">
        <label for="{{ $name }}" class="col-sm-3 control-label text-left is-required">{{ $label }}</label>
        <div class="col-sm-9">
            <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}" {{ $attributes }}>
            <span id="{{ $name }}_err" class="invalid-feedback"></span>
        </div>
    </div>
</div>