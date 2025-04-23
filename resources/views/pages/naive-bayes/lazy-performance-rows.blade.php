@foreach ($testData as $row)
    <tr>
        @foreach ($row->getAttributes() as $key => $val)
            @if (!in_array($key, ['id','user_id','file_name','file_size','created_at','updated_at']))
                <td class="px-4 py-2 text-sm text-gray-700">{{ $val }}</td>
            @endif
        @endforeach
    </tr>
@endforeach
