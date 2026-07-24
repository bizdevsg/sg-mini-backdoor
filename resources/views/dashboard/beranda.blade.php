@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    @php($user = auth()->user())

    @if ($user?->isSuperadmin())
        @include('dashboard.partials.superadmin', [
            'user' => $user,
            'userCount' => $userCount,
            'superadminCount' => $superadminCount,
            'adminCount' => $adminCount,
            'adminHostCount' => $adminHostCount,
            'produkSPAcount' => $produkSPAcount,
            'produkJFXcount' => $produkJFXcount,
            'informasiCount' => $informasiCount,
            'penghargaanCount' => $penghargaanCount,
            'bannerCount' => $bannerCount,
            'signalCategoryCount' => $signalCategoryCount,
            'beritaCategoryCount' => $beritaCategoryCount,
            'signalCount' => $signalCount,
            'beritaCount' => $beritaCount,
            'recentProducts' => $recentProducts,
        ])
    @elseif ($user?->isAdminHost())
        @include('dashboard.partials.admin-host', [
            'user' => $user,
            'informasiCount' => $informasiCount,
            'bannerCount' => $bannerCount,
            'signalCategoryCount' => $signalCategoryCount,
            'beritaCategoryCount' => $beritaCategoryCount,
            'signalCount' => $signalCount,
            'beritaCount' => $beritaCount,
            'ebookCount' => $ebookCount,
            'ebookCategoryCount' => $ebookCategoryCount,
            'recentEbooks' => $recentEbooks,
        ])
    @else
        @include('dashboard.partials.admin', [
            'user' => $user,
            'userCount' => $userCount,
            'superadminCount' => $superadminCount,
            'adminCount' => $adminCount,
            'adminHostCount' => $adminHostCount,
            'produkSPAcount' => $produkSPAcount,
            'produkJFXcount' => $produkJFXcount,
            'informasiCount' => $informasiCount,
            'penghargaanCount' => $penghargaanCount,
            'bannerCount' => $bannerCount,
            'signalCategoryCount' => $signalCategoryCount,
            'beritaCategoryCount' => $beritaCategoryCount,
            'signalCount' => $signalCount,
            'beritaCount' => $beritaCount,
            'recentProducts' => $recentProducts,
        ])
    @endif
@endsection
