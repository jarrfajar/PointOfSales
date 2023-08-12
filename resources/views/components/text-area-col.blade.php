<div class="col">
    <div class="form-group row">
        <label for="{{ $name }}" class="col-sm-3 col-form-label">{{ $label }}</label>
        <div class="col-sm-9">
            <textarea class="form-control" id="{{ $name }}" name="{{ $name }}" rows="3"></textarea>
            <span id="{{ $name }}_err" class="invalid-feedback"></span>
        </div>
    </div>
</div>