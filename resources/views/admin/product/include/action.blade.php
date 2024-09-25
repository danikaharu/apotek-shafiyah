<div class="dropdown show">
    <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button" id="menu" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        Action
    </a>

    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('admin.product.show', $id) }}">Detail</a>
        <a class="dropdown-item" href="{{ route('admin.product.edit', $id) }}">Edit</a>
        <form action={{ route('admin.product.destroy', $id) }} method="post" role="alert" alert-title="Hapus Produk"
            alert-text="Yakin ingin menghapusnya?">
            @csrf
            @method('delete')
            <button type="submit" class="dropdown-item">
                Hapus
            </button>
        </form>
    </div>
</div>
