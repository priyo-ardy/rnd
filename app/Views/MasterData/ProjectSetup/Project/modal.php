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

<div class="modal fade" id="modalGenerate" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalGenerateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate APQP Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="modal_token" id="modal_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                <p>
                    Are you sure you want to generate APQP data for all part numbers in this project?
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn rounded-0 btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i>&ensp;Close</button>
                <button type="button" id="btnModalGenerate" class="btn rounded-0 btn-primary"><i class="bi bi-gear-wide-connected"></i>&ensp;Generate</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalListApprover" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalListApprover" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approver List <span id="apqp_name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalApprover()"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-primary" id="tableApprover">
                        <thead>
                            <tr>
                                <th class="text-center align-middle bg-secondary-subtle col-9">Approver</th>
                                <th class="text-center align-middle bg-secondary-subtle col-3">Action</th>
                            </tr>
                        </thead>
                        <tbody id="listApprover"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn rounded-0 btn-secondary" data-bs-dismiss="modal" onclick="closeModalApprover()"><i class="bi bi-x-circle"></i>&ensp;Close</button>
            </div>
        </div>
    </div>
</div>