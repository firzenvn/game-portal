<tr id="serverRow{{ $item->id }}">
    <td>{{ $item->id }}</td>
    <td>{{ $item->name }}</td>
    <td>{{ $item->order_number }}</td>
    <td>{{ $item->ip }}</td>
    <td>{{ $item->url }}</td>
    <td>{{ $item->secret_key }}</td>
    <td>{{ $item->sid }}</td>
    <td>{{ $item->apply_for }}</td>
    <td>{{ $item->active }}</td>
    <td>
        <div class="pull-right">
            <a href="javascript:editServer({{ $item->id }})" class="btn btn-sm btn-primary">Sửa</a> <a href="javascript:deleteServer({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
        </div>
    </td>
</tr>