@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Auth::check())
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @php
                            $settings = Auth::user()->settings;
                            $fields = ['name', 'surname', 'city'];
                        @endphp

                        <form method="POST" enctype="multipart/form-data" action="{{ route('settings.change') }}" id="settings-form">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="method-select">Выберите способ подтверждения:</label>
                                <select name="method" id="method-select">
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="telegram">Telegram</option>
                                </select>
                                <div id="input-email">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" id="email" value="{{Auth::user()->email}}">
                                </div>
                                <div id="input-phone" style="display: none">
                                    <label for="phone">Телефон:</label>
                                    <input type="tel" name="methodData[phone]" id="phone" value="{{Auth::user()->phone}}">
                                </div>
                                <div id="input-telegram" style="display: none">
                                    <label for="telegram">Telegram chat_id:</label>
                                    <input type="text" name="methodData[telegram]" id="telegram" value="{{Auth::user()->telegram}}">
                                </div>
                            </div>

                            @foreach ($fields as $field)
                                <div class="form-group mb-3">
                                    <input id="{{$field}}" type="text" name="settings[{{$field}}]" placeholder="{{ucfirst($field)}}" value="{{ $settings->{$field} }}">
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script type="text/javascript" src="{{ asset('js/home.js') }}"></script>
@endpush
