@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.distributors.index') }}" class="breadcrumb__link">
            Distributerji torrentov
        </a>
    </li>
    <li class="breadcrumbV2">
        {{ $distributor->name }}
    </li>
    <li class="breadcrumb--active">
        {{ __('common.delete') }}
    </li>
@endsection

@section('page', 'page__distributor--delete')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.delete') }} Distributer torrentov: {{ $distributor->name }}
        </h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('staff.distributors.destroy', ['id' => $distributor->id]) }}"
                x-data
            >
                @csrf
                @method('DELETE')
                <p class="form__group">
                    Obstoječi torrent na spletnem mestu morda že uporablja tega distributerja. Bi ga radi spremenili?
                </p>
                <p class="form__group">
                    <select
                        name="distributor_id"
                        id="autoreg"
                        class="form__select"
                        x-data="{ distributor: '' }"
                        x-model="distributor"
                        x-bind:class="distributor === '' ? 'form__select--default' : ''"
                    >
                        <option hidden disabled selected value=""></option>
                        @foreach ($distributors as $distributor)
                            <option value="{{ $distributor->id }}">
                                {{ $distributor->name }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="autoreg">
                        Replacement distributor
                    </label>
                </p>
                <p class="form__group">
                    <button 
                        x-on:click.prevent="Swal.fire({
                            title: 'Ali si prepričan?',
                            text: 'Ali ste prepričani, da želite izbrisati tega distributerja?: {{ $distributor->name }}?',
                            icon: 'warning',
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $root.submit();
                            }
                        })"
                        class="form__button form__button--filled"
                    >
                        {{ __('common.delete') }}
                    </button>
                </p>
            </form>
        </div>
    </div>
@endsection