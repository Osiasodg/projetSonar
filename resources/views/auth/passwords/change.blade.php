@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Changement de mot de passe obligatoire') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="current_password" class="col-md-4 col-form-label text-md-right">
                                {{ __('Mot de passe actuel') }}
                            </label>
                            <div class="col-md-6">
                                <input id="current_password" type="password" 
                                    class="form-control @error('current_password') is-invalid @enderror" 
                                    name="current_password" required autofocus>
                                
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">
                                {{ __('Nouveau mot de passe') }}
                            </label>
                            <div class="col-md-6">
                                <input id="new_password" type="password" 
                                    class="form-control @error('new_password') is-invalid @enderror" 
                                    name="new_password" required>
                                
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">
                                {{ __('Confirmer le nouveau mot de passe') }}
                            </label>
                            <div class="col-md-6">
                                <input id="new_password_confirmation" type="password" 
                                    class="form-control" 
                                    name="new_password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Mettre à jour le mot de passe') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection