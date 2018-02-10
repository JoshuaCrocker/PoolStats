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
</tr>