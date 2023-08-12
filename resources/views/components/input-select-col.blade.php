<div class="col">
    <div class="form-group row">
        <label for="{{ $name }}" class="col-sm-3 col-form-label">{{ $label }}</label>
        <div class="col-sm-9">
            <select class="form-control select" id="{{ $name }}" name="{{ $name }}" style="width: 100%;" {{ $attributes }}></select>
            <span id="{{ $name }}_err" class="invalid-feedback"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
        </div>
    </div>
</div>