<php>
   
    Assalamu Alaikum, <br> <br>
    I hope this email finds you well.
    I am writing to request the following asset for our project.
    This requisition is essential for improving productivity and ensuring the smooth functioning of our team. <br><br>
    Details of asset:<br><br>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Name</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Specification</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Asset type</th>
            <th style="border: 1px solid; text-align: center; padding: 2px;">Reason</th>
        </tr>
        <tr>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $data['name'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $data['specification'] }}</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">@if($assetType) {{$assetType}} @endif</td>
            <td style="border: 1px solid; text-align: center; padding: 2px;">{{ $data['remarks'] }}</td>
        </tr>
    </table>
   <br>
    Regards,<br>
    {{ $user_name }}

    
</php>