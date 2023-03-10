@component('profiles.activities.activity')

    @slot('heading')

        {{ $profileUser->name }} published thread
        <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>

    @endslot

    @slot('body')

        {{ $activity->subject->body }}

    @endslot

@endcomponent
