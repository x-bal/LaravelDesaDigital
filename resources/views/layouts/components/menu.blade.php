<!--App Sidebar-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user clearfix">
        <div class="user-pro-body">
            <div class="sideuser-img">
                <img src="{{ asset('assets/img/photos/1.jpg') }}" alt="user-img" class="">
                <span class="sidebar-icon"></span>
            </div>
            <div class="user-info">
                <h2 class="app-sidebar__user-name">{{ auth()->user()->name }}</h2>
                <span class="app-sidebar__title">{{ auth()->user()->roles()->first()->name }}</span>
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
            <a class="side-menu__item" href="{{  route('desa.loket.index') }}"><i class="side-menu__icon" data-eva="home-outline"></i><span class="side-menu__label">Loket</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{  route('desa.antrian.index') }}"><i class="side-menu__icon" data-eva="list-outline"></i><span class="side-menu__label">Antrian</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.permohonan.index') }}"><i class="side-menu__icon" data-eva="email-outline"></i><span class="side-menu__label">Permohonan Surat</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('desa.cetak_surat.index') }}"><i class="side-menu__icon" data-eva="email-outline"></i><span class="side-menu__label">Cetak Surat</span></a>
        </li>
        <li>
            <h3>Gallery</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.gallery.index') }}"><i class="side-menu__icon" data-eva="grid-outline"></i><span class="side-menu__label">Gallery</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.playlist.index') }}"><i class="side-menu__icon" data-eva="video-outline"></i><span class="side-menu__label">Playlist</span></a>
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
            <a class="side-menu__item" href="{{ route('desa.aduan.index') }}"><i class="side-menu__icon" data-eva="file-text-outline"></i><span class="side-menu__label">Aduan</span></a>
        </li>

        <li>
            <h3>Informasi</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.kategori_informasi.index') }}"><i class="side-menu__icon" data-eva="cube-outline"></i><span class="side-menu__label">Kategori Informasi</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.informasi.index') }}"><i class="side-menu__icon" data-eva="cube-outline"></i><span class="side-menu__label">Informasi</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.marque.index') }}"><i class="side-menu__icon" data-eva="cube-outline"></i><span class="side-menu__label">Marque</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.rates.index') }}"><i class="side-menu__icon" data-eva="star-outline"></i><span class="side-menu__label">Rates</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('desa.rating.index') }}"><i class="side-menu__icon" data-eva="star-outline"></i><span class="side-menu__label">Rating</span></a>
        </li>
        <li>
            <h3>Setting</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('setting') }}"><i class="side-menu__icon" data-eva="settings-outline"></i><span class="side-menu__label">Setting Profile</span></a>
        </li>
        @endrole
        @role('Warga')
        <li>
            <h3>Daftar Masyarakat</h3>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('warga.masyarakat.index') }}"><i class="side-menu__icon" data-eva="layout-outline"></i><span class="side-menu__label">Daftar Masyarakat</span></a>
        </li>
        @endrole
    </ul>
</aside>
<!--/App Sidebar-->