<tr>
    <td class="text-center">{{ $frame->frame_number }}</td>
    <td>
        {{ join(' & ', $frame->homePlayers->pluck('name')->all()) }}

        @if ($frame->isWinner($frame->homePlayer))
            [W]
        @endif
    </td>
    <td>
        {{ join(' & ', $frame->awayPlayers->pluck('name')->all()) }}

        @if ($frame->isWinner($frame->awayPlayer))
            [W]
        @endif
    </td>
    <td>
        <a href="{{ $frame->endpoint() }}/edit" class="btn btn-primary btn-xs">Edit</a>

        <form action="{{ $frame->endpoint() }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}

            <button type="submit" class="btn btn-danger btn-xs">Delete</button>
        </form>
    </td>
</tr>
