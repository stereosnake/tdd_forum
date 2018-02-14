@component('profiles.activities.activity')
    @slot('heading')
        <a href="/profiles/{{ $profileUser->name }}">{{ $profileUser->name }}</a> published
        <a href="{{ $activity->subject->path() }}">"{{ $activity->subject->title }}"</a>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent