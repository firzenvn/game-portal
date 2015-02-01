<tr id="matchRow{{ $item->id }}">
    <td>{{ $item->id }}</td>
    <td>{{ $item->range_level }}</td>
    <td>{{ Config::get('common.game_leagues_round.'.$game->subdomain.'.'.$item->round) }}</td>
    <td>{{ $item->firstUser->username }}</td>
    <td>{{ $item->secondUser->username }}</td>
    <td>{{ $item->getLiteralResult() }}</td>
    <td>{{ $item->video_key }}</td>
    <td>
        <div class="pull-right">
            <a href="javascript:deleteMatch({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
        </div>
    </td>
</tr>

