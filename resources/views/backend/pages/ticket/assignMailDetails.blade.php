<php>
   
    Assalamu Alaikum, <br> <br>
    I hope this email finds you well.
    I am writing to let you know that a new ticket has been assigned to you. <br><br>
    Details of ticket:<br><br>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Subject</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Priority</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Deadline</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Description</th>
        </tr>
        <tr>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $ticket_info['subject'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">
                            @if($ticket_info['priority']==\Illuminate\Support\Facades\Config::get('variable_constants.ticket_priority.low'))low
                            @elseif($ticket_info['priority']==\Illuminate\Support\Facades\Config::get('variable_constants.ticket_priority.medium'))medium
                            @elseif($ticket_info['priority']==\Illuminate\Support\Facades\Config::get('variable_constants.ticket_priority.high'))high
                            @elseif($ticket_info['priority']==\Illuminate\Support\Facades\Config::get('variable_constants.ticket_priority.critical'))critical
                            @endif</td>

            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $ticket_info['deadline']}}</td>

            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $ticket_info['description'] }}</td>
        </tr>
    </table>
   <br>
    Regards,<br>
    {{ $user_name }}

    
</php>