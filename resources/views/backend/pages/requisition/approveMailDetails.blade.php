<php>
   
    Assalamu Alaikum, <br> <br>
    I hope this email finds you well.
    I am writing to let you know that request for the following asset has been approved for the user <br>
    Name: {{$requisition_info->full_name}}<br>
    Employee ID: {{$requisition_info->employee_id}}.<br><br>
    Details of asset:<br><br>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Name</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Specification</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Asset type</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Reason</th>
        </tr>
        <tr>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $requisition_info->name }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $requisition_info->specification }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">@if($requisition_info->asset_type_name) {{$requisition_info->asset_type_name}} @endif</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $requisition_info->remarks }}</td>
        </tr>
    </table>
   <br>
    Regards,<br>
    {{ $user_name }}

    
</php>