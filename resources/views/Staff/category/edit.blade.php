@extends('layout.default')

@section('title')
    <title>Uredi {{ __('staff.torrent-categories') }} - {{ __('staff.staff-dashboard') }}</title>
@endsection


@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.categories.index') }}" class="breadcrumb__link">
            {{ __('staff.torrent-categories') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        {{ $category->name }}
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('page', 'page__category--edit')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.edit') }} {{ __('torrent.category') }}: {{ $category->name }}
        </h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('staff.categories.update', ['id' => $category->id]) }}"
                enctype="multipart/form-data"
            >
                @method('PATCH')
                @csrf
                <p class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        type="text"
                        name="name"
                        value="{{ $category->name }}"
                    >
                    <label class="form__label form__label--floating" for="name">{{ __('common.name') }}<label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        type="text"
                        name="position"
                        value="{{ $category->position }}"
                    >
                    <label class="form__label form__label--floating" for="positon">{{ __('common.position') }}</label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        type="text"
                        name="icon"
                        value="{{ $category->icon }}"
                    >
                    <label class="form__label form__label--floating" for="icon">{{ __('common.icon') }} (FontAwesome)</label>
                </p>
                <p class="form__group">
                    <label for="image">
                        {{ __('common.select') }}
                        {{ trans_choice('common.a-an-art',false) }}
                        {{ __('common.image') }}
                        (Če ne uporabljate ikone FontAwesome)
                    </label>
                    <input
                        id="file"
                        class="form__file"
                        type="file"
                        name="image"
                    >
                </p>
                <p class="form__group">
                    <select
                        name="meta"
                        id="meta"
                        class="form__select"
                        required
                    >
                        <option class="form__option" value="movie" @selected($category->movie_meta)>
                            {{ __('staff.movie-meta-data') }}
                        </option>
                        <option class="form__option" value="cartoon" @selected($category->cartoon_meta)>
                            {{ __('staff.cartoons-meta-data') }}
                        </option>
                        <option class="form__option" value="tv" @selected($category->tv_meta)>
                            {{ __('staff.tv-meta-data') }}
                        </option>
                        <option class="form__option" value="cartoontv" @selected($category->cartoontv_meta)>
                            {{ __('staff.cartoontvs-meta-data') }}
                        </option>
                        <option class="form__option" value="game" @selected($category->game_meta)>
                            {{ __('staff.game-meta-data') }}
                        </option>
                        <option class="form__option" value="music" @selected($category->music_meta)>
                            {{ __('staff.music-meta-data') }}
                        </option>
                        <option class="form__option" value="no" @selected($category->no_meta)>
                            {{ __('staff.no-meta-data') }}
                        </option>
                    </select>
                    <label class="form__label form__label--floating" for="meta">
                        Meta
                    </label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.submit') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
