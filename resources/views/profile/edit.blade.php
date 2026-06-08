@php
    $role = auth()->user()->role;
@endphp

@if ($role === 'admin')
<x-app-admin-layout>
    @include('profile.partials.edit-content')
</x-app-admin-layout>
@elseif ($role === 'supervisor')
<x-app-supervisor-layout>
    @include('profile.partials.edit-content')
</x-app-supervisor-layout>
@else
<x-app-layout>
    @include('profile.partials.edit-content')
</x-app-layout>
@endif
