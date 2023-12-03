<php>
   
    Please approve my Asset Requisition Request <br>
    name: {{ $data['name'] }}  <br>
    specification: {{ $data['specification'] }}  <br>
    @if($assetType)
        asset type: {{$assetType}}<br>
    @endif
    reason: {{ $data['remarks'] }}

    
</php>