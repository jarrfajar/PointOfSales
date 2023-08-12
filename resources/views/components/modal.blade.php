<div id="modal-{{ $name }}" data-focus="false" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-{{ $name }}">
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-form-{{ $name }}">Save</button>
                    <button type="button" class="btn btn-warning" onclick="formReset()">Reset</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="dismis-modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>