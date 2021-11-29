<!--App Sidebar-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user clearfix">
        <div class="user-pro-body">
            <div class="sideuser-img">
                <img src="{ asset('storage/'.$pegawai->foto) }" alt="user-img" class="">
                <span class="sidebar-icon"></span>
            </div>
            <div class="user-info">
                <h2 class="app-sidebar__user-name">{ Nama }</h2>
                <span class="app-sidebar__title">{ nama }</span>
            </div>
        </div>
    </div>
    <ul class="side-menu">
        <li>
            <h3>Main</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('dashboard') }}"><i class="side-menu__icon" data-eva="monitor-outline"></i><span class="side-menu__label">Dashboard</span></a>
        </li>
        @role('Utama')
        <li>
            <h3>Daftar Masyarakat</h3>
        </li>
        <li>
            <a class="side-menu__item" href="#"><i class="side-menu__icon" data-eva="layout-outline"></i><span class="side-menu__label">Daftar Masyarakat</span></a>
        </li>
        @endrole
        @role('Kabupaten')
        <li>
            <h3>Daftar Masyarakat</h3>
        </li>
        <li>
            <a class="side-menu__item" href="#"><i class="side-menu__icon" data-eva="layout-outline"></i><span class="side-menu__label">Daftar Masyarakat</span></a>
        </li>
        @endrole
        @role('Desa')
        <li>
            <h3>Daftar Masyarakat</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.warga.index') }}"><i class="side-menu__icon" data-eva="layout-outline"></i><span class="side-menu__label">Daftar Masyarakat</span></a>
        </li>
        <li>
            <h3>Pengguna</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.pengguna.index') }}"><i class="side-menu__icon" data-eva="stop-circle-outline"></i><span class="side-menu__label">Pengguna</span></a>
        </li>
        <li>
            <h3>Surat</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{  route('desa.antrian.index') }}"><i class="side-menu__icon" data-eva="list-outline"></i><span class="side-menu__label">Antrian</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.permohonan.index') }}"><i class="side-menu__icon" data-eva="email-outline"></i><span class="side-menu__label">Permohonan Surat</span></a>
        </li>
        <li>
            <h3>Gallery</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.gallery.index') }}"><i class="side-menu__icon" data-eva="grid-outline"></i><span class="side-menu__label">Gallery</span></a>
        </li>
        <li>
            <h3>Produk Warga</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.produk.index') }}"><i class="side-menu__icon" data-eva="shopping-cart-outline"></i><span class="side-menu__label">Produk Warga</span></a>
        </li>
        <li>
            <h3>Aduan</h3>
        </li>
        <li>
            <a class="side-menu__item" href="#"><i class="side-menu__icon" data-eva="file-text-outline"></i><span class="side-menu__label">Aduan</span></a>
        </li>

        <li>
            <h3>Informasi</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.informasi.index') }}"><i class="side-menu__icon" data-eva="cube-outline"></i><span class="side-menu__label">Informasi</span></a>
        </li>
        @endrole
        @role('Warga')
        <li>
            <h3>Daftar Masyarakat</h3>
        </li>
        <li>
            <a class="side-menu__item" href="#"><i class="side-menu__icon" data-eva="layout-outline"></i><span class="side-menu__label">Daftar Masyarakat</span></a>
        </li>
        @endrole
    </ul>
</aside>
<!--/App Sidebar-->