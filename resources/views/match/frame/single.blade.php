<tr>
    <td class="text-center">{{ $frame->frame_number }}</td>
    <td>
        {{ $frame->homePlayer->name }}

        @if ($frame->isWinner($frame->homePlayer))
            [W]
        @endif
    </td>
    <td>
        {{ $frame->awayPlayer->name }}

        @if ($frame->isWinner($frame->awayPlayer))
            [W]
        @endif
    </td>
    <td>
        <a href="{{ $frame->endpoint() }}/edit" class="btn btn-primary btn-xs">Edit</a>
    </td>
</tr>