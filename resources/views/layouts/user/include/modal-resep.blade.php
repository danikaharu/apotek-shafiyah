<!-- Modal Upload Resep -->
<div class="modal fade" id="modal-resep" tabindex="-1" aria-labelledby="modalResepLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalResepLabel"><i class="fas fa-upload"></i> Upload Resep Dokter</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('store.recipe') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="resep">Pilih File Resep</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                            required>
                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">Format file: JPG, JPEG, PNG. Maksimal 2MB.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-upload"></i> Kirim Resep
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
