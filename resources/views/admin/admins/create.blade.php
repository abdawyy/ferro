@extends('admin.layouts.app')

@section('title', 'New administrator')
@section('page_title', 'New administrator')
@section('breadcrumb', 'Admin / Staff / Create')

@section('content')

<div class="page-header">
    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary btn-sm">← Back</a>
</div>

<div class="admin-card" style="max-width: 520px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Create administrator</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="name">Full name</label>
                <input id="name" name="name" type="text" class="form-input" value="{{ old('name') }}" required autocomplete="name">
            </div>
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="email">Email</label>
                <input id="email" name="email" type="email" class="form-input" value="{{ old('email') }}" required autocomplete="off">
            </div>
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="password">Password</label>
                <input id="password" name="password" type="password" class="form-input" required autocomplete="new-password" minlength="10">
                <p class="text-muted text-sm" style="margin-top: 6px;">At least 10 characters.</p>
            </div>
            <div style="margin-bottom: 20px;">
                <label class="form-label" for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary">Create administrator</button>
        </form>
    </div>
</div>

@endsection
