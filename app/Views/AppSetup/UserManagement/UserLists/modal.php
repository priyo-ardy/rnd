<div class="modal fade" id="modalUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <form id="formUser">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetForm()"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="form-group col-12 clearfix">
                            <input type="hidden" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                            <label class="form-label">Username <strong class="text-danger fw-bolder">*</strong></label>
                            <input type="text" name="data_username" id="data_username" class="form-control rounded-0" required autofocus autocomplete="off" placeholder="Username" maxlength="25">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                            <label class="form-label">Full Name <strong class="text-danger fw-bolder">*</strong></label>
                            <input type="text" name="data_fullname" id="data_fullname" class="form-control rounded-0" required autocomplete="off" placeholder="Full Name" maxlength="150">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                            <label class="form-label">Email <strong class="text-danger fw-bolder">*</strong></label>
                            <input type="email" name="data_email" id="data_email" class="form-control rounded-0" required autocomplete="off" placeholder="Email" maxlength="150">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                            <label class="form-label">Phone Number <strong class="text-danger fw-bolder">*</strong></label>
                            <input type="number" name="data_phone" id="data_phone" class="form-control rounded-0" required autocomplete="off" placeholder="Phone Number" maxlength="20">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                            <label class="form-label">User Level <strong class="text-danger fw-bolder">*</strong></label>
                            <select name="data_level" id="data_level" class="form-select select2 select2bs5 rounded-0" required>
                                <option value="">Select User Level</option>
                                <option value="0">Administrator</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                            <label class="form-label">Password <strong class="text-danger fw-bolder">*</strong></label>
                            <input type="password" name="data_password" id="data_password" class="form-control rounded-0" required autocomplete="off" placeholder="Password" maxlength="50">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnCancel" type="button" class="btn btn-secondary rounded-0" title="Cancel" data-bs-dismiss="modal" aria-label="Close" onclick="resetForm()"><i class="fa-solid fa-xmark"></i>&ensp;Cancel</button>
                    <button id="btnSave" type="button" class="btn btn-primary rounded-0" title="Save"><i class="fa-solid fa-floppy-disk"></i>&ensp;Save</button>
                    <button id="btnUpdate" hidden type="button" class="btn btn-primary rounded-0" title="Update"><i class="fa-solid fa-floppy-disk"></i>&ensp;Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formPassword">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="text" name="user_token" id="user_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password <strong class="text-danger fw-bolder">*</strong></label>
                        <input type="password" name="new_password" id="new_password" class="form-control rounded-0" placeholder="New Password" maxlength="50" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelPassword" class="btn btn-secondary rounded-0" title="Cancel" onclick="clearModal()" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>&ensp;Cancel</button>
                    <button type="button" id="changePassword" class="btn btn-primary rounded-0" title="Change"><i class="fa-solid fa-floppy-disk"></i>&ensp;Change</button>
                </div>
            </form>
        </div>
    </div>
</div>