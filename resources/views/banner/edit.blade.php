@extends('layouts.app')

@section('title', 'Edit Banner')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Update image banner',
            'title' => $banner->title ?: ('Banner #' . $banner->id),
        ])

        <form action="{{ route('banner.update', $banner) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')
            @include('banner.partials.form', [
                'banner' => $banner,
                'submitLabel' => 'Update Banner',
                'cancelUrl' => route('banner.index'),
            ])
        </form>
    </section>
@endsection
