<tr id="leagueUserRow{{ $item->id }}">
    <td>{{ $item->id }}</td>
    <td>{{ $item->username }}</td>
    <td>{{ $item->level_range }}</td>
    <td>{{ $item->point }}</td>
    <td>
        <div class="pull-right">
            <a href="javascript:editUser({{ $item->id }})" class="btn btn-sm btn-primary">Sửa</a> <a href="javascript:deleteUser({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
        </div>
    </td>
</tr>