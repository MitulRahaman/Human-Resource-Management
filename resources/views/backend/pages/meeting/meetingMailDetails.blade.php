<php>
   
    Assalamu Alaikum, <br> <br>
    I hope this email finds you well.
    I am writing to let you know that there is a meeting on {{$info['date']}}. <br><br>
    Details of meeting:<br><br>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Title</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Agenda</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Date</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Place</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Start Time(24 H:i)</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">End Time(24 H:i)</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Description</th>
        </tr>
        <tr>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $info['title'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $info['agenda'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $info['date']}}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $place_name }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $info['start_time'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $info['end_time'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $info['description'] }}</td>
        </tr>
    </table>
   <br>
    @if($info['url']) URL: <a href="{{$info['url']}}">{{$info['url']}}</a>  @endif<br><br>
    Regards,<br>
    {{ $user_name }}

    
</php>