@extends('backend.layouts.master')
@section('css_after')

@endsection
@section('content')
    <div class="content">
        <!-- Quick Actions -->

        <!-- END Quick Actions -->

        <!-- User Info -->
        <div class="block block-rounded">
            <div class="block-content text-center">
                <div class="py-4">
                    <div class="mb-3">

                        <?php
                        $url =asset('backend/media/avatars/avatar13.jpg');
                            if($user->image)
                                {
                                    $url = asset('storage/userImg/'. $user->image);
                                }
                        ?>
                        <img class="img-avatar" src="{{$url}}" alt="">
                    </div>
                    <h1 class="font-size-lg mb-0">
                        {{ $user->full_name }} <i class="fa fa-star text-warning" data-toggle="tooltip" title="Premium Member"></i>
                    </h1>
                    <p class="font-size-sm text-muted"></p>
                </div>
            </div>
        </div>
        <!-- END User Info -->

        <!-- User Information -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">User Information</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-lg-12 ">
                        <!-- Billing Address -->
                        <div class="block block-rounded block-bordered">
                            <div class="block-header border-bottom">
                                <h3 class="block-title">Official Information</h3>
                            </div>
                            <div class="block-content">
                                <div class="font-size-h4 mb-1">{{$user->full_name}}</div>
                                <div class="font-size-sm">
                                    Employee ID: {{$user->employee_id}}<br>
                                    @if($user_official_info)
                                    Designation: {{$user_official_info->designation_name}}<br>
                                    Role: {{$user_official_info->role_name}}<br>
                                    Department: {{$user_official_info->department_name}}<br>
                                    Branch:{{$user_official_info->branch_name}}<br>
                                    @endif
                                    <br>
                                    <i class="fa fa-phone"></i> {{$user->phone_number}}<br>
                                    <i class="fa fa-envelope"></i> Official: <a href="javascript:void(0)">{{$user->email}}</a><br>
                                    @if($user->basicInfo)
                                    <i class="fa fa-envelope"></i> Personal: <a href="javascript:void(0)">{{$user->basicInfo->personal_email}}</a><br>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- END Billing Address -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END User Information -->
        <!-- Emergency Contact -->
        @if(count($user->emergencyContacts)>1)
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Emergency Contact</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="block block-rounded block-bordered">
                            <div class="block-header border-bottom">
                                <h3 class="block-title">Emergency Contact 1</h3>
                            </div>
                            <div class="block-content">
                                <div class="font-size-h4 mb-1">{{$user->emergencyContacts[0]->name}}</div>
                                <div class="font-size-sm">
                                    Relation: {{$user->emergencyContacts[0]->relation}}<br>
                                    <br>
                                    <i class="fa fa-phone"></i> {{$user->emergencyContacts[0]->phone_number}}<br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div class="block block-rounded block-bordered">
                            <div class="block-header border-bottom">
                                <h3 class="block-title">Emergency Contact 1</h3>
                            </div>
                            <div class="block-content">
                                <div class="font-size-h4 mb-1">{{$user->emergencyContacts[1]->name}}</div>
                                <div class="font-size-sm">
                                    Relation: {{$user->emergencyContacts[1]->relation}}<br>
                                    <br>
                                    <i class="fa fa-phone"></i> {{$user->emergencyContacts[1]->phone_number}}<br>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- END Emergency Contact -->

        <!-- Addresses -->
        @if(count($user_address)>1)
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Addresses</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Billing Address -->
                        <div class="block block-rounded block-bordered">
                            <div class="block-header border-bottom">
                                <h3 class="block-title">Present Address</h3>
                            </div>
                            <div class="block-content">
                                <div class="font-size-h4 mb-1">{{$user_address[0]->address}}</div>
                            </div>
                        </div>
                        <!-- END Billing Address -->
                    </div>
                    <div class="col-lg-6">
                        <!-- Shipping Address -->
                        <div class="block block-rounded block-bordered">
                            <div class="block-header border-bottom">
                                <h3 class="block-title">Permanent Address</h3>
                            </div>
                            <div class="block-content">
                                <div class="font-size-h4 mb-1">{{$user_address[1]->address}}</div>
                            </div>
                        </div>
                        <!-- END Shipping Address -->
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- END Addresses -->
        <!-- Academic Info -->
        @if(count($user->academicInfo)>0)
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Academic Information</h3>
            </div>

            <div class="block-content">
                <?php $index=0 ?>
                @foreach($user->academicInfo  as $academy)
                        <div class="row">
                            @for($i=0; $i<2 && $index<count($user->academicInfo); $i=$i+1)
                            <div class="col-lg-6">
                                <!-- Billing Address -->
                                <div class="block block-rounded block-bordered">
                                    <div class="block-header border-bottom">
                                        <h3 class="block-title">{{$userInstituteDegree[$index]['degree_name']}}</h3>
                                    </div>
                                    <div class="block-content">
                                        <div class="font-size-h4 mb-1">{{$academy->major}}</div>
                                        <div class="font-size-sm">
                                            GPA: {{$academy->gpa}}<br>
                                            Institute: {{$userInstituteDegree[$index]['institute_name']}}<br>
                                            Passing Year: {{$academy->passing_year}}<br><br>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Billing Address -->
                            </div>
                                <?php $index=$index+1 ?>
                                @endfor
                        </div>
                @endforeach
            </div>


        </div>
        @endif

        <!-- END Academic Info -->

        <!-- Banking -->
        <!-- END Banking -->
    </div>

@endsection

@section('js_after')
@endsection
