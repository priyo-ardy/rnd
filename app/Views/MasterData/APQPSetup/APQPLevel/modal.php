<div class="modal fade" id="modalApprover" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalApproverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <form id="formApprover">
                <div class="modal-header">
                    <h5 class="modal-title">APQP Approver List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearModel()"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="form-group col-12">
                            <input type="hidden" name="apqp_token" id="apqp_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-primary" id="tableApprover">
                            <thead>
                                <th class="text-center align-middle bg-secondary-subtle col-8">Name</th>
                                <th class="text-center align-middle bg-secondary-subtle col-4">Action</th>
                            </thead>
                            <tbody id="listApprover"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal" title="Cancel" onclick="clearModel()"><i class="bi bi-x-circle"></i>&ensp;Close</button>
                    <button type="button" hidden class="btn btn-primary rounded-0" id="btnSaveApprover" title="Save Approver"><i class="bi bi-floppy"></i>&ensp;Save</button>
                </div>
            </form>
        </div>
    </div>
</div>