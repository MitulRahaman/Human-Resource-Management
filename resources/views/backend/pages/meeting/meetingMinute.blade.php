
<!DOCTYPE html>
<html>
<head>
    <title>Appnap HRM</title>
</head>
<body>
<h1>Meeting Minutes</h1>

<div class="content">

    <h2>Title : <span style="color:darkolivegreen">{{$meeting_info->title}}</span></h2>
    <p>Date : {{$meeting_info->date}}</p>
    <h3>Agenda : <span style="color: #2a3f6f">{{$meeting_info->agenda}}</span></h3>
    <h4>Notes : </h4>
    <table>
        @foreach($meeting_minute as $mm)
            <tr>
                <td>{{$mm['name']}} ( {{$mm['employee_id']}} )</td>
                <td>{{'   : '}}</td>
                <td>{{$mm['notes']}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

