@extends('layout.default')

@section('title')
    <title>{{ __('mediahub.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="MediaHub">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('mediahub.title') }}
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header mediahub text-center">
                <div class="mediahub_content">
                    <div class="content">

                        <h1 style=" height: 150px; font-family: Shrikhand, cursive; font-size: 7em; font-weight: 400; margin: 0;">{{ __('mediahub.title') }}</h1>
                        <h3>{{ __('mediahub.select-hub') }}</h3>

                        <div class="blocks text-center" style=" justify-content: center;">
                            {{--TV Shows--}}
                            <a href="{{ route('mediahub.shows.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.shows') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $tv }} {{ __('mediahub.shows') }}</h2>
                                </div>
                            </a>

                            {{--Movies--}}
                            <a href="{{ route('mediahub.movies.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.movies') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $movies }} {{ __('mediahub.movies') }}</h2>
                                </div>
                            </a>

                            {{--Cartoons--}}
                            <a href="{{ route('mediahub.cartoons.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.cartoons') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $cartoons }} {{ __('mediahub.cartoons') }}</h2>
                                </div>
                            </a>

                            {{--Cartoons TV--}}
                            <a href="{{ route('mediahub.cartoontvs.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.cartoontvs') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $cartoontvs }} {{ __('mediahub.cartoontvs') }}</h2>
                                </div>
                            </a>

                            {{--Collections--}}
                            <a href="{{ route('mediahub.collections.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.collections') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $collections }} {{ __('mediahub.collections') }}</h2>
                                </div>
                            </a>

                            {{--People--}}
                            <a href="{{ route('mediahub.persons.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.persons') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $persons }} {{ __('mediahub.persons') }}</h2>
                                </div>
                            </a>

                            {{--Genres--}}
                            <a href="{{ route('mediahub.genres.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.genres') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $genres }} {{ __('mediahub.genres') }}</h2>
                                </div>
                            </a>

                            {{--Networks--}}
                            <a href="{{ route('mediahub.networks.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.networks') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $networks }} {{ __('mediahub.networks') }}</h2>
                                </div>
                            </a>

                            {{--Companies--}}
                            <a href="{{ route('mediahub.companies.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>{{ __('mediahub.companies') }}</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $companies }} {{ __('mediahub.companies') }}</h2>
                                </div>
                            </a>
                        </div>
                        <br>
                        <h6 style="font-size: 10px;">
                            <i>{{ __('mediahub.disclaimer') }}</i>
                        </h6>
                        <img class="mb-20" src="/img/tmdb_long.svg" style="width: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
