@extends('layout.default')

@section('title')
    <title>{{ $cartoontv->name }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $cartoontv->name }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.cartoontvs.index') }}" class="breadcrumb__link">
            TV Serija
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $cartoontv->name }}
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div style="width: 100% !important; display: table !important;">
                <div class="header mediahub" style="width: 100% !important; display: table-cell !important;">
                    <h1 class="text-center"
                        style="font-family: Shrikhand, cursive; font-size: 4em; font-weight: 400; margin: 0;">
                        {{ $cartoontv->name }}
                    </h1>
                    <div class="row">
                        <div class="col-md-8">
                            @foreach($cartoontv->seasons as $season)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card is-torrent"
                                             style=" height: auto; margin-top: 0; margin-bottom: 20px;">
                                            <div class="card_head">
                                <span class="badge-user text-bold" style="float:right;">
                                    {{ $season->episodes->count() }} Episodes
                                </span>
                                                <span class="badge-user text-bold" style="float:right;">
                                    Season {{ $season->season_number }}
                                </span>
                                            </div>
                                            <div class="card_body" style="height: 190px;">
                                                <div class="body_poster">
                                                    <img src="{{ isset($season->poster) ? tmdb_image('poster_mid', $season->poster) : '/img/SLOshare/movie_no_image_search.jpg' }}"
                                                         class="show-poster" style="height: 190px;">
                                                </div>
                                                <div class="body_description" style=" height: 190px;">
                                                    <h3 class="description_title">
                                                        <a href="{{ route('mediahub.season.show', ['id' => $season->id]) }}">{{ $season->name }}
                                                            @if($season->air_date)
                                                                <span class="text-bold text-pink"> ({{ substr($season->air_date, 0, 4) }})</span>
                                                            @endif
                                                        </a>
                                                    </h3>
                                                    <p class="description_plot">
                                                        {{ $season->overview }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="card_footer text-center">
                                                <a data-toggle="collapse" data-target="#{{ $season->season_number }}">
                                                    <i class="fas fa-chevron-double-down"></i> <span
                                                            class="badge-user text-bold"> {{ $season->torrents->where('season_number', '=', $season->season_number)->count() }} Torrents Matched</span>
                                                    <i class="fas fa-chevron-double-down"></i>
                                                </a>
                                            </div>
                                            <div id="{{ $season->season_number }}" class="collapse">
                                                <div class="card_footer" style="height: auto;">
                                                    <div class="table-responsive">
                                                        <table class="table table-condensed table-bordered table-striped table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ __('common.name') }}</th>
                                                                <th>{{ __('torrent.size') }}</th>
                                                                <th>S</th>
                                                                <th>L</th>
                                                                <th>C</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($season->torrents->where('season_number', '=', $season->season_number)->sortByDesc('created_at') as $torrent)
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{ route('torrent', ['id' => $torrent->id]) }}"
                                                                           style="color: #8fa8e0;">{{ $torrent->name }}</a>
                                                                    </td>
                                                                    <td>{{ $torrent->getSize() }}</td>
                                                                    <td>{{ $torrent->seeders }}</td>
                                                                    <td>{{ $torrent->leechers }}</td>
                                                                    <td>{{ $torrent->times_completed }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="info_column col-md-3"
                             style=" background: rgba(0, 0, 0, 0.28); border-radius: 5px; margin-left: 3%;">
                            <div>
                                <h2 class="text-center"><em>Facts</em></h2>
                                <hr>
                                <section class="split_column season">
                                    <div>
                                        <div class="column no_bottom_pad">
                                            <section class="facts left_column">
                                                <h4>
                                                    <bdi>General</bdi>
                                                </h4>
                                                <div class="seasons">
                                                    <div>
                                                        <span class="badge badge-default">Seasons:</span>
                                                        <span class="text-bold">{{ $cartoontv->number_of_seasons }} </span>
                                                    </div>
                                                </div>

                                                <div class="status">
                                                    <span class="badge badge-default">Status:</span>
                                                    <span class="text-bold">{{ $cartoontv->status }} </span>
                                                </div>

                                                <div class="networks">
                                                    <span class="badge badge-default">Networks:</span>
                                                    @if ($cartoontv->networks)
                                                        @foreach ($cartoontv->networks as $network)
                                                            <span class="text-bold">{{ $network->name }}</span>
                                                        @endforeach
                                                    @endif
                                                </div>

                                                <div class="companies">
                                                    <span class="badge badge-default">Companies:</span>
                                                    @if ($cartoontv->companies)
                                                        @foreach ($cartoontv->companies as $company)
                                                            <span class="text-bold">{{ $company->name }}</span>
                                                        @endforeach
                                                    @endif
                                                </div>

                                                <div class="runtime">
                                                    <span class="badge badge-default">Runtime:</span>
                                                    <span class="text-bold">{{ $cartoontv->episode_run_time }} </span>
                                                </div>

                                                <div class="torrents">
                                                    <span class="badge badge-default">Torrents:</span>
                                                    <span class="text-bold">{{ $cartoontv->torrents_count }} </span>
                                                </div>
                                            </section>

                                            <hr>

                                            <section class="genres right_column">
                                                <h4>
                                                    <bdi>Genres</bdi>
                                                </h4>
                                                <ul>
                                                    @if ($cartoontv->genres)
                                                        @foreach ($cartoontv->genres as $genre)
                                                            <li><a class="rounded"
                                                                   href="{{ route('mediahub.genres.show', ['id' => $genre->id]) }}">{{ $genre->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </section>
                                        </div>
                                    </div>

                                    <div>
                                        {{--EDIT BUTTON --}}
                                    </div>

                                </section>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection