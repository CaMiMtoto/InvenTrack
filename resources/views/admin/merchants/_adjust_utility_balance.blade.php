<form action="{{ route('admin.merchants.balance-top-up',encodeId($merchant->id)) }}" id="balance-top-up-form" method="post">
    @csrf
    <div class="modal-body">
        <div class="mb-3 ">
            <label for="staticEmail" class="col-form-label">Name:</label>
            <input type="text" readonly class="form-control-plaintext"
                   value="{{ $merchant->name }}"/>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 ">
                    <label for="staticEmail" class="col-form-label">Email:</label>
                    <input type="text" readonly class="form-control-plaintext"
                           value="{{ $merchant->email }}"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 ">
                    <label for="staticEmail" class="col-form-label">Phone number:</label>
                    <input type="text" readonly class="form-control-plaintext"
                           value="{{ $merchant->phone }}"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 ">
                    <label for="staticEmail" class="col-form-label">Utility balance:</label>
                    <input type="text" readonly class="form-control-plaintext"
                           value="{{ number_format($merchant->utilityWallet->balance??0) }} RWF"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 ">
                    <label for="staticEmail" class="col-form-label">Payment balance:</label>
                    <input type="text" readonly class="form-control-plaintext"
                           value="{{ number_format($merchant->paymentWallet->balance??0) }} RWF"/>
                </div>
            </div>
        </div>
        <div class="mb-3 ">
            <label for="amount" class="col-form-label">New Utility balance:</label>
            <div class="input-group">
                <input type="number" class="form-control" id="amount" name="amount"
                       value="{{ number_format($merchant->utilityWallet->balance??0) }}"/>
                <span class="input-group-text" id="basic-addon2">RWF</span>
            </div>
        </div>
        <div class="mb-3 ">
            <label for="reason" class="col-form-label">Reason:</label>
            <textarea class="form-control" id="reason" name="reason"></textarea>
        </div>
    </div>

    <div class="modal-footer bg-light justify-content-between">
        <button type="submit" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn bg-secondary text-light-emphasis" data-bs-dismiss="modal">
            Close
        </button>
    </div>
</form>
