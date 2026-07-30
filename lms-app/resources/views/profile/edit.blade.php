@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('breadcrumb', 'Hồ sơ cá nhân')

@section('content')
    <main class="flex-1 max-w-[1200px] mx-auto w-full px-4 sm:px-6 py-6 sm:py-10 md:py-16 space-y-6 sm:space-y-8">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 p-5 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 p-5 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 p-5 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </main>
@endsection
