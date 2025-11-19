<div class="modal fade" id="modalSetDate" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalApproverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mass Set Due Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearModal()"></button>
            </div>
            <div class="modal-body">
                <input type="date" name="mass_due_date" id="mass_due_date" class="form-control rounded-0">
                <div class="invalid-feedback"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal" title="Cancel" onclick="clearModel()"><i class="bi bi-x-circle"></i>&ensp;Close</button>
                <button type="button" class="btn btn-primary rounded-0" id="btnSetDate" title="Set Due Date"><i class="bi bi-floppy"></i>&ensp;Set Due Date</button>
            </div>
        </div>
    </div>
</div>