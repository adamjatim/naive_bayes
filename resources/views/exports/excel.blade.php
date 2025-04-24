<table>
    <thead>
        <tr>
            @foreach (array_keys($predictions[0]['data']->getAttributes()) as $key)
                @if (!in_array($key, ['id', 'user_id', 'created_at', 'updated_at', 'file_name', 'file_size']))
                    <th>{{ strtoupper(str_replace('_', ' ', $key)) }}</th>
                @endif
            @endforeach
            <th>HASIL TESTING</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($predictions as $row)
            <tr>
                @foreach ($row['data']->getAttributes() as $key => $val)
                    @if (!in_array($key, ['id', 'user_id', 'created_at', 'updated_at', 'file_name', 'file_size']))
                        <td>{{ $val }}</td>
                    @endif
                @endforeach
                <td>{{ $row['predicted'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
