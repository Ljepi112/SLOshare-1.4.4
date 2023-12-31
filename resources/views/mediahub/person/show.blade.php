@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.persons.index') }}" class="breadcrumb__link">
            {{ __('mediahub.persons') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $details->name }}
    </li>
@endsection

@section('content')
    <div class="container">

        <div class="block">
            <div class="panel-body">
                <div class="col-sm-12 movie-list" style=" margin: 0;">
                    <div class="row">

                        <section class="col-sm-3">
                            <img src="{{ isset($details->still) ? tmdb_image('cast_big', $details->still) : '/img/SLOshare/no_image_holder.jpg' }}"
                                 alt="{{ $details->name }}" class="img-responsive thumb">
                        </section>

                        <section class="col-sm-9">

                            <p class="actor-bio shortened">{{ $details->biography }}</p>

                            <br>

                            <i class="fa fa-book text-green" aria-hidden="true"></i> {{ __('mediahub.wiki-read') }}
                            <a target="_blank"
                               href="https://en.wikipedia.org/wiki/{{ $details->name }}">{{ $details->name }} Wiki</a>

                            <hr>

                            <dl class="dl-horizontal">
                                <dt><i class="fa fa-heart text-green" aria-hidden="true"></i> {{ __('mediahub.born') }}
                                </dt>
                                <dd>
                                    {{ $details->birthday ?? '' }}
                                    In {{ $details->place_of_birth ?? '' }}
                                </dd>
                                <br>

                                <dt><i class="fa fa-film text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.movie-credits') }} </dt>
                                <dd>{{ $credits->movie->count() ?? '0' }}</dd>
                                <dt><i class="fa fa-step-backward text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.first-seen') }} </dt>
                                <dd>In
                                    <a href="{{ route('mediahub.movies.show', ['id' => $credits->movie->first()->id ?? '0']) }}">{{ $credits->movie->first()->title ?? 'N/A'}}</a>
                                </dd>
                                <dt><i class="fa fa-step-forward text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.latest-project') }} </dt>
                                <dd>Last in <a
                                            href="{{ route('mediahub.movies.show', ['id' => $credits->movie->last()->id ?? '0']) }}">{{ $credits->movie->last()->title ?? 'N/A' }} </a>
                                </dd>
                                <br>

                                <dt><i class="fa fa-film text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.cartoon-credits') }} </dt>
                                <dd>{{ $credits->cartoon->count() ?? '0' }}</dd>
                                <dt><i class="fa fa-step-backward text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.first-seen') }} </dt>
                                <dd>In
                                    <a href="{{ route('mediahub.cartoons.show', ['id' => $credits->cartoon->first()->id ?? '0']) }}">{{ $credits->cartoon->first()->title ?? 'N/A'}}</a>
                                </dd>
                                <dt><i class="fa fa-step-forward text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.latest-project') }} </dt>
                                <dd>Last in <a
                                            href="{{ route('mediahub.cartoons.show', ['id' => $credits->cartoon->last()->id ?? '0']) }}">{{ $credits->cartoon->last()->title ?? 'N/A' }} </a>
                                </dd>
                                <br>

                                <dt><i class="fa fa-tv-retro text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.tv-credits') }} </dt>
                                <dd>{{ $credits->tv->count() ?? '0' }}</dd>
                                <dt><i class="fa fa-step-backward text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.first-seen') }} </dt>
                                <dd>In
                                    <a href="{{ route('mediahub.shows.show', ['id' => $credits->tv->first()->id ?? '0']) }}">{{ $credits->tv->first()->name ?? 'N/A'}}</a>
                                </dd>
                                <dt><i class="fa fa-step-forward text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.latest-project') }} </dt>
                                <dd>Last in <a
                                            href="{{ route('mediahub.shows.show', ['id' => $credits->tv->last()->id ?? '0']) }}">{{ $credits->tv->last()->name ?? 'N/A' }} </a>
                                </dd>
                                <br>

                                <dt><i class="fa fa-tv-retro text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.cartoontv-credits') }} </dt>
                                <dd>{{ $credits->cartoontv->count() ?? '0' }}</dd>
                                <dt><i class="fa fa-step-backward text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.first-seen') }} </dt>
                                <dd>In
                                    <a href="{{ route('mediahub.cartoontvs.show', ['id' => $credits->cartoontv->first()->id ?? '0']) }}">{{ $credits->cartoontv->first()->name ?? 'N/A'}}</a>
                                </dd>
                                <dt><i class="fa fa-step-forward text-green"
                                       aria-hidden="true"></i> {{ __('mediahub.latest-project') }} </dt>
                                <dd>Last in <a
                                            href="{{ route('mediahub.cartoontvs.show', ['id' => $credits->cartoontv->last()->id ?? '0']) }}">{{ $credits->cartoontv->last()->name ?? 'N/A' }} </a>
                                </dd>
                            </dl>

                        </section>
                    </div>
                </div>
            </div>
        </div>

<!-- Movie -->
        <div class="block">
            <table class="table table-striped clearfix">
                <tbody>
                @if(! empty($credits->movie))
                    @if (count($credits->movie) <= 0)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1 class="text-blue">
                                    <i class="{{ config('other.font-awesome') }} fa-frown text-blue"></i>{{ __('mediahub.no-data') }}
                                </h1>
                            </div>
                        </div>
                    @endif
                    @foreach($credits->movie as $movie)
                        <tr>
                            <td class="col-sm-1">
                                <img src="{{ isset($movie->poster) ? tmdb_image('poster_small', $movie->poster) : '/img/SLOshare/mediahub_no_image_90x135.jpg' }}"
                                     alt="{{ $movie->name }}" class="img-responsive">
                            </td>
                            <td class="col-sm-5">
                                <i class="fa fa-film text-purple" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.movie') }}</strong><br>
                                <i class="fa fa-eye text-green" aria-hidden="true"></i> <a
                                        href="{{ route('mediahub.movies.show', ['id' => $movie->id]) }}">{{ $movie->title }}</a><br>
                                <i class="fa fa-tags text-red" aria-hidden="true"></i>
                                <strong>
                                    @if ($movie->genres)
                                        @foreach ($movie->genres as $genre)
                                            {{ $genre->name }}
                                        @endforeach
                                    @endif
                                </strong>
                                <br>
                                <i class="fa fa-calendar text-blue" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.release-date') }} </strong>{{ $movie->release_date }}<br>
                            </td>
                            <td class="col-xs-pull-6"><i class="fa fa-book text-gold" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.plot') }} </strong>
                                {{ $movie->overview }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
<!-- Movie -->

<!-- Cartoon -->
        <div class="block">
            <table class="table table-striped clearfix">
                <tbody>
                @if(! empty($credits->cartoon))
                    @if (count($credits->cartoon) <= 0)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1 class="text-blue">
                                    <i class="{{ config('other.font-awesome') }} fa-frown text-blue"></i>{{ __('mediahub.no-data') }}
                                </h1>
                            </div>
                        </div>
                    @endif
                    @foreach($credits->cartoon as $cartoon)
                        <tr>
                            <td class="col-sm-1">
                                <img src="{{ isset($cartoon->poster) ? tmdb_image('poster_small', $cartoon->poster) : '/img/SLOshare/mediahub_no_image_90x135.jpg' }}"
                                     alt="{{ $cartoon->name }}" class="img-responsive">
                            </td>
                            <td class="col-sm-5">
                                <i class="fa fa-film text-purple" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.cartoon') }}</strong><br>
                                <i class="fa fa-eye text-green" aria-hidden="true"></i> <a
                                        href="{{ route('mediahub.cartoons.show', ['id' => $cartoon->id]) }}">{{ $cartoon->title }}</a><br>
                                <i class="fa fa-tags text-red" aria-hidden="true"></i>
                                <strong>
                                    @if ($cartoon->genres)
                                        @foreach ($cartoon->genres as $genre)
                                            {{ $genre->name }}
                                        @endforeach
                                    @endif
                                </strong>
                                <br>
                                <i class="fa fa-calendar text-blue" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.release-date') }} </strong>{{ $cartoon->release_date }}<br>
                            </td>
                            <td class="col-xs-pull-6"><i class="fa fa-book text-gold" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.plot') }} </strong>
                                {{ $cartoon->overview }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
<!-- Cartoon -->

<!-- Tv -->
        <div class="block">
            <table class="table table-striped clearfix">
                <tbody>
                @if(! empty($credits->tv))
                    @if (count($credits->tv) <= 0)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1 class="text-blue">
                                    <i class="{{ config('other.font-awesome') }} fa-frown text-blue"></i>{{ __('mediahub.no-data') }}
                                </h1>
                            </div>
                        </div>
                    @endif
                    @foreach($credits->tv as $show)
                        <tr>
                            <td class="col-sm-1">
                                <img src="{{ isset($show->poster) ? tmdb_image('poster_small', $show->poster) : '/img/SLOshare/mediahub_no_image_90x135.jpg' }}"
                                     alt="{{ $show->name }}" class="img-responsive">
                            </td>
                            <td class="col-sm-5">
                                <i class="fa fa-tv-retro text-purple" aria-hidden="true"></i>
                                <strong> {{ __('mediahub.show') }}</strong><br>
                                <i class="fa fa-eye text-green" aria-hidden="true"></i> <a
                                        href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}">{{ $show->name }}</a><br>
                                <i class="fa fa-tags text-red" aria-hidden="true"></i>
                                <strong>
                                    @if ($show->genres)
                                        @foreach ($show->genres as $genre)
                                            {{ $genre->name }}
                                        @endforeach
                                    @endif
                                </strong>
                                <br>
                                <i class="fa fa-calendar text-blue" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.release-date') }} </strong>{{ $show->first_air_date }}<br>
                            </td>
                            <td class="col-xs-pull-6"><i class="fa fa-book text-gold" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.plot') }} </strong>
                                {{ $show->overview }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
<!-- Tv -->

<!-- Cartoon Tv -->
        <div class="block">
            <table class="table table-striped clearfix">
                <tbody>
                @if(! empty($credits->cartoontv))
                    @if (count($credits->cartoontv) <= 0)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1 class="text-blue">
                                    <i class="{{ config('other.font-awesome') }} fa-frown text-blue"></i>{{ __('mediahub.no-data') }}
                                </h1>
                            </div>
                        </div>
                    @endif
                    @foreach($credits->cartoontv as $cartoontv)
                        <tr>
                            <td class="col-sm-1">
                                <img src="{{ isset($cartoontv->poster) ? tmdb_image('poster_small', $cartoontv->poster) : '/img/SLOshare/mediahub_no_image_90x135.jpg' }}"
                                     alt="{{ $cartoontv->name }}" class="img-responsive">
                            </td>
                            <td class="col-sm-5">
                                <i class="fa fa-tv-retro text-purple" aria-hidden="true"></i>
                                <strong> {{ __('mediahub.cartoontv') }}</strong><br>
                                <i class="fa fa-eye text-green" aria-hidden="true"></i> <a
                                        href="{{ route('mediahub.cartoontvs.show', ['id' => $cartoontv->id]) }}">{{ $cartoontv->name }}</a><br>
                                <i class="fa fa-tags text-red" aria-hidden="true"></i>
                                <strong>
                                    @if ($cartoontv->genres)
                                        @foreach ($cartoontv->genres as $genre)
                                            {{ $genre->name }}
                                        @endforeach
                                    @endif
                                </strong>
                                <br>
                                <i class="fa fa-calendar text-blue" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.release-date') }} </strong>{{ $cartoontv->first_air_date }}<br>
                            </td>
                            <td class="col-xs-pull-6"><i class="fa fa-book text-gold" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.plot') }} </strong>
                                {{ $cartoontv->overview }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
<!-- Cartoon Tv -->
    </div>
@endsection
