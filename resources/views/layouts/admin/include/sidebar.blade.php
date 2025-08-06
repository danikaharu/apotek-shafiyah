<div class="sidebar ">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">

    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item ">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->is('admin/dashboard') ? ' active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            <li
                class="nav-item {{ request()->is('admin/supplier', 'admin/purchase', 'admin/receipt', 'admin/refund') ? ' menu-open' : '' }}">
                <a href="#"
                    class="nav-link {{ request()->is('admin/supplier', 'admin/purchase', 'admin/receipt', 'admin/refund') ? ' active' : '' }}">
                    <i class="nav-icon fa fa-cubes"></i>
                    <p>
                        Detail Supplier
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.supplier.index') }}"
                            class="nav-link {{ request()->is('admin/supplier') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Supplier</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.purchase.index') }}"
                            class="nav-link {{ request()->is('admin/purchase') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Pembelian</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.receipt.index') }}"
                            class="nav-link {{ request()->is('admin/receipt') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Penerimaan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.refund.index') }}"
                            class="nav-link {{ request()->is('admin/refund') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Retur Barang</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="nav-item {{ request()->is('admin/discount', 'admin/product', 'admin/product/*', 'admin/expire-product', 'admin/dwindling-product') ? ' menu-open' : '' }}">
                <a href="#"
                    class="nav-link {{ request()->is('admin/discount', 'admin/product', 'admin/expire-product', 'admin/dwindling-product') ? ' active' : '' }}">
                    <i class="nav-icon fa fa-medkit"></i>
                    <p>
                        Data Produk
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.product.index') }}"
                            class="nav-link {{ request()->is('admin/product', 'admin/product/*') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>List Produk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.discount.index') }}"
                            class="nav-link {{ request()->is('admin/discount') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Diskon Produk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.expire.product') }}"
                            class="nav-link {{ request()->is('admin/expire-product') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Produk Kadaluarsa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dwindling.product') }}"
                            class="nav-link {{ request()->is('admin/dwindling-product') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Produk Menipis</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.member-level.index') }}"
                    class="nav-link {{ request()->is('admin/member-level', 'admin/member-level/*') ? ' active' : '' }}">
                    <i class="nav-icon fa fa-medal"></i>
                    <p>
                        Level Member
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.order.index') }}"
                    class="nav-link {{ request()->is('admin/order', 'admin/order/*') ? ' active' : '' }}">
                    <i class="nav-icon fa fa-calculator"></i>
                    <p>
                        Order
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.user.index') }}"
                    class="nav-link {{ request()->is('admin/user') ? ' active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Customer / User
                    </p>
                </a>
            </li>
            <li
                class="nav-item {{ request()->is('admin/report/order', 'admin/report/purchase', 'admin/report/receipt') ? ' menu-open' : '' }}">
                <a href="#"
                    class="nav-link {{ request()->is('admin/report/order', 'admin/report/purchase', 'admin/report/receipt') ? ' active' : '' }}">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>
                        Laporan
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.report.order') }}"
                            class="nav-link {{ request()->is('admin/report/order') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Laporan Penjualan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.report.purchase') }}"
                            class="nav-link {{ request()->is('admin/report/purchase') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Lap. Pembelian-Sup</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.report.receipt') }}"
                            class="nav-link {{ request()->is('admin/report/receipt') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Lap. Obat Yang Diterima</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="nav-item {{ request()->is('admin/recipe', 'admin/recipe/*', 'admin/banner', 'admin/profile') ? ' menu-open' : '' }}">
                <a href="#"
                    class="nav-link {{ request()->is('admin/recipe', 'admin/recipe/*', 'admin/banner', 'admin/profile') ? ' menu-open' : '' }}">
                    <i class="nav-icon fas fa-info-circle"></i>
                    <p>
                        Informasi
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.recipe.index') }}"
                            class="nav-link {{ request()->is('admin/recipe', 'admin/recipe/*') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Resep Dokter</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.banner.index') }}"
                            class="nav-link {{ request()->is('admin/banner') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Banner</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile.index') }}"
                            class="nav-link {{ request()->is('admin/profile') ? ' active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Deskripsi Toko</p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
