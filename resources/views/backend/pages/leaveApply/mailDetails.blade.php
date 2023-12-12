<php>
    Assalamu Alaikum, <br> <br>
    I hope this email finds you well.
    I am applying for a leave from {{ $user['startDate'] }} to {{ $user['endDate'] }}, total: {{ $user['totalLeave'] }} days.<br>

    Please approve my Leave Application, reason is given below. <br><br>
    Details: <br><br>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Start Date</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">End Date</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Total Days</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Reason</th>
        </tr>
        <tr>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $user['startDate'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $user['endDate'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $user['totalLeave'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $user['reason'] }}</td>
        </tr>
    </table>
   <br>
    Regards,<br>
    {{ $user_name }}
</php>
