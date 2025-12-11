<div class="modal fade" id="modalFlow" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalFlowLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFlowLabel">Document Flow Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Flow Level Name <strong class="text-danger">*</strong></label>
                    <input type="text" name="flow_name" id="flow_name" class="form-control rounded-0" placeholder="Flow Level Name" maxlength="150" required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-0" id="btnCloseFlow" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i>&ensp;Close</button>
                <button type="button" class="btn btn-primary rounded-0" id="btnSaveFlow"><i class="bi bi-floppy"></i>&ensp;Save</button>
            </div>
        </div>
    </div>
</div>