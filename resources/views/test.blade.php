<table>
    <tr>
        <td>dsadsddsdsadadsa</td>
    </tr>
</table>


<table>
    <thead>
    <tr>
        <th>UserName</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($schedules as $schedule)
        <tr>
            <td>{{ $schedule->user_id }}</td>
            <td>{{ $schedule->date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
