<div id="reply_{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="{{ route('profile', $reply->owner->name) }}">
                    {{ $reply->owner->name }}
                </a> said {{ $reply->created_at->diffForHumans() }}
            </h5>
            <div>
                <form method="POST" action="/replies/{{$reply->id}}/favourites">
                    {{ csrf_field() }}
                    <button class="btn btn-default" type="submit" {{ $reply->isFavourited() ? 'disabled' : '' }}>
                        {{ $reply->favourites_count }} {{ str_plural('Favourite', $reply->favourites_count) }}
                    </button>
                </form>
            </div>
        </div>

    </div>

    <div class="panel-body">
        {{ $reply->body }}
    </div>

    @can('update', $reply)
        <div class="panel-footer">
            <form method="POST" action="/replies/{{ $reply->id }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button class="btn btn-danger btn-xs" type="submit">Delete</button>
            </form>
        </div>
    @endcan
</div>