@extends('contensio::admin.layout')
@section('title', 'Content Password')
@section('breadcrumb')
<a href="{{ route('contensio.settings') }}" class="text-gray-500 hover:text-gray-700">Settings</a>
<span class="mx-2 text-gray-300">/</span>
<span class="font-medium text-gray-700">Content Password</span>
@endsection
@section('content')

@if(session('success'))
<div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
    <svg class="w-4 h-4 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
    <svg class="w-4 h-4 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>{{ $errors->first() }}</div>
</div>
@endif

<div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">

    <div class="px-6 py-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900">Password-Protected Content</h2>
            <p class="text-sm text-gray-500 mt-0.5">Set or remove passwords on any published post or page.</p>
        </div>
        <span class="text-sm text-gray-400">{{ $items->count() }} item{{ $items->count() === 1 ? '' : 's' }}</span>
    </div>

    @forelse($items as $item)
    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4">

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-900 truncate">{{ $item['title'] }}</span>
                @if($item['has_password'])
                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Protected
                </span>
                @endif
            </div>
            <div class="text-xs text-gray-400 mt-0.5">{{ $item['type'] }} &middot; Published {{ $item['published']?->diffForHumans() }}</div>
        </div>

        {{-- Set / update password --}}
        <form method="POST" action="{{ route('content-password.set', $item['id']) }}" class="flex items-center gap-2 shrink-0">
            @csrf
            <input type="password"
                   name="password"
                   placeholder="{{ $item['has_password'] ? 'Change password' : 'Set password' }}"
                   minlength="4"
                   class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ember-500 focus:border-transparent w-40">
            <button type="submit" class="bg-ember-500 hover:bg-ember-600 text-white text-sm font-semibold px-3 py-1.5 rounded-lg transition-colors">
                {{ $item['has_password'] ? 'Update' : 'Set' }}
            </button>
        </form>

        {{-- Remove password --}}
        @if($item['has_password'])
        <form method="POST" action="{{ route('content-password.remove', $item['id']) }}" class="shrink-0">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-red-600 transition-colors px-2 py-1.5 rounded-lg">
                Remove
            </button>
        </form>
        @endif

    </div>
    @empty
    <div class="px-6 py-10 text-center text-sm text-gray-400">
        No published content found.
    </div>
    @endforelse

</div>

@endsection
