<php>
   
    Assalamu Alaikum, <br> <br>
    I hope this email finds you well.
    I am writing to request the following asset for our project.
    This requisition is essential for improving productivity and ensuring the smooth functioning of our team. <br>
    Details of asset:<br>
    Name: {{ $data['name'] }}  <br>
    Specification: {{ $data['specification'] }}  <br>
    @if($assetType)
        Asset type: {{$assetType}}<br>
    @endif
    Reason: {{ $data['remarks'] }}<br><br>
    Regards,<br>
    {{ $user_name }}

    
</php>