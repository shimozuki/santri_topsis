<aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-0 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-r-2xl border-0 bg-white p-0 antialiased shadow-lg transition-transform duration-200 xl:left-0 xl:translate-x-0">
    <div class="h-19.5">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer ri-close-line text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-dark" href="#" target="_blank">
            <img src="{{ asset('img/logo.jpg') }}" class="inline h-full max-w-full max-h-12 rounded-full transition-all duration-200 ease-nav-brand" alt="main_logo" />
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">SPK TOPSIS</span>
        </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

    <ul class="flex flex-col pl-0 mb-5">
        <li class="mt-0.5 w-full">
            <a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-home-smile-fill text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
            </a>
        </li>

        <li class="w-full mt-6">
            <h6 class="pl-6 ml-2 font-bold leading-tight uppercase text-xs opacity-60">Master</h6>
        </li>
        @if(Auth::user()->roles->pluck('name')->contains('admin') || Auth::user()->roles->pluck('name')->contains('kepala_sekolah'))
        <li class="mt-0.5 w-full">
            <a href="{{ route('kriteria') }}" class="{{ Request::is('dashboard/kriteria') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard/kriteria') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-table-fill text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Kriteria</span>
            </a>
        </li>
        @if(Auth::user()->roles->pluck('name')->contains('admin'))
        <li class="mt-0.5 w-full">
            <a href="{{ route('sub_kriteria') }}" class="{{ Request::is('dashboard/sub_kriteria') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard/sub_kriteria') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-collage-fill text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Sub Kriteria</span>
            </a>
        </li>
        @endif
        <li class="mt-0.5 w-full">
            <a href="{{ route('objek') }}" class="{{ Request::is('dashboard/objek') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard/objek') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-brackets-fill text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Santri</span>
            </a>
        </li>
        @endif
        {{-- Data TOPSIS --}}
        <li class="w-full mt-6">
            <h6 class="pl-6 ml-2 font-bold leading-tight uppercase text-xs opacity-60">TOPSIS</h6>
        </li>
        @if(Auth::user()->roles->pluck('name')->contains('penguji_1') || Auth::user()->roles->pluck('name')->contains('penguji_2') || Auth::user()->roles->pluck('name')->contains('penguji_3') || Auth::user()->roles->pluck('name')->contains('admin'))
        <li class="mt-0.5 w-full">
            <a href="{{ route('penilaian') }}" class="{{ Request::is('dashboard/penilaian*') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard/penilaian*') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-survey-fill text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Penilaian</span>
            </a>
        </li>
        @endif
        @if(Auth::user()->roles->pluck('name')->contains('admin') || Auth::user()->roles->pluck('name')->contains('kepala_sekolah'))
        <li class="mt-0.5 w-full">
            <a href="{{ route('perhitungan') }}" class="{{ Request::is('dashboard/perhitungan') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard/perhitungan') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-calculator-fill text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Perhitungan</span>
            </a>
        </li>
        <li class="mt-0.5 w-full">
            <a href="{{ route('hasil_akhir') }}" class="{{ Request::is('dashboard/hasil_akhir') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard/hasil_akhir') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-bar-chart-2-fill text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Hasil Akhir</span>
            </a>
        </li>
        @endif
        @if(Auth::user()->roles->pluck('name')->contains('admin'))
        <li class="w-full mt-6">
            <h6 class="pl-6 ml-2 font-bold leading-tight uppercase text-xs opacity-60">AKUN</h6>
        </li>
        <li class="mt-2 w-full">
            <a href="{{ route('users.index') }}" class="{{ Request::is('dashboard/user') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard/user') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-user-3-line text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Data User</span>
            </a>
        </li>

        @endif
        @if(Auth::user()->roles->pluck('name')->contains('santri'))
        <li class="mt-0.5 w-full">
            <a href="{{ route('hasil_akhir') }}" class="{{ Request::is('dashboard/hasil_akhir') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-dark' : '' }} py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors">
                <div class="{{ Request::is('dashboard/hasil_akhir') ? 'bg-gradient-to-tl from-backgroundSecondary to-greenSecondary text-white' : '' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="ri-bar-chart-2-fill text-greenPrimary"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Hasil Akhir</span>
            </a>
        </li>
        @endif

    </ul>
</aside>