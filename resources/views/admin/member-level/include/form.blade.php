<div class="row">
    <div class="col-md-12">
        <!-- /.form-group -->
        <div class="form-group">
            <label>Nama Level</label>
            <input type="text" class="form-control" name="name"
                value="{{ isset($memberLevel->name) ? $memberLevel->name : old('name') }}">
            <span class="text-danger" id="name_error"></span>
        </div>
        <!-- /.form-group -->
        <div class="form-group">
            <label>Min. Transaksi</label>
            <input type="number" class="form-control" name="min_transactions"
                value="{{ isset($memberLevel->min_transactions) ? $memberLevel->min_transactions : old('min_transactions') }}">
            <span class="text-danger" id="min_transactions_error"></span>
        </div>
        <div class="form-group">
            <label>Diskon (%)</label>
            <input type="number" class="form-control" name="discount_percent"
                value="{{ isset($memberLevel->discount_percent) ? $memberLevel->discount_percent : old('discount_percent') }}">
            <span class="text-danger" id="discount_percent_error"></span>
        </div>
    </div>
</div>
