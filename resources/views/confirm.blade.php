@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('confirm.code') }}">
                            @method('PATCH')
                            @csrf
                            <input type="hidden" name="method" value="{{$method}}">
                            <label for="code">Введите код подтверждения c {{$method}}:</label>
                            <input type="text" name="code" id="code">
                            <button type="submit">Подтвердить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection