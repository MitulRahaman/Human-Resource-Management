@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <div class="row row-deck">
            <div class="col-sm-6 col-xl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$total['users']}}</dt>
                            <dd class="text-muted mb-0">Users</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="si si-users font-size-h3 text-primary"></i>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        <a class="font-w500 d-flex align-items-center" href="{{url('user/manage')}}">
                            View all users
                            <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            <div class="col-sm-6 col-xl-3">
                <!-- New Customers -->
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$total['pending_requisition']}}</dt>
                            <dd class="text-muted mb-0">Pending requisition requests</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="far  fa-plus-square font-size-h3 text-primary"></i>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        <a class="font-w500 d-flex align-items-center" href="{{url('requisition')}}">
                            View all requisition requests
                            <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END New Customers -->
            </div>
            <div class="col-sm-6 col-xl-3">
                <!-- Messages -->
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$total['pending_leave']}}</dt>
                            <dd class="text-muted mb-0">Pending leave application</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="si si-note font-size-h3 text-primary"></i>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        <a class="font-w500 d-flex align-items-center" href="{{url('leaveApply/manage')}}">
                            View all leave application
                            <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Messages -->
            </div>
            <div class="col-sm-6 col-xl-3">
                <!-- Conversion Rate -->
                <div class="block block-rounded d-flex flex-column">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{$total['on_leave']}}</dt>
                            <dd class="text-muted mb-0">Absent Today</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            <i class="si si-users font-size-h3 text-primary"></i>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        <a class="font-w500 d-flex align-items-center" href="{{url('leaveApply/manage')}}">
                            View leave table
                            <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Conversion Rate-->
            </div>
        </div>
    @if($hasManageLeavePermission)
        <!-- Recent Orders -->

            <div class="block block-rounded">

                <div class="block-content">
                    <div class="row">
                        <div class="col-lg-6">
                                <div class="block block-rounded">
                                    <div class="block-header block-header-default">
                                        <h3 class="block-title">On Leave(Today)</h3>
                                    </div>

                                    <div class="block-content">
                                        <!-- Recent Orders Table -->
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-striped table-vcenter" id="on_leave">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Employee ID</th>
                                                    <th class="text-center">Employee Name</th>
                                                    <th class="text-center">Designation</th>
                                                </tr>
                                                </thead>
                                                <tbody id="on_leave_table_body">

                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- END Recent Orders Table -->

                                    </div>
                                </div>
                        </div>
                        <div class="col-lg-6">
                                <div class="block block-rounded">
                                    <div class="block-header block-header-default">
                                        <h3 class="block-title">Pending Leave Application</h3>
                                    </div>

                                    <div class="block-content">
                                        <!-- Recent Orders Table -->
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-striped table-vcenter" id="pending_leave">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Employee ID</th>
                                                    <th class="text-center">Employee Name</th>
                                                    <th class="text-center">Leave Type</th>
                                                    <th class="text-center">Start date</th>
                                                    <th class="text-center">End date</th>
                                                    <th class="text-center">Applied</th>
                                                </tr>
                                                </thead>
                                                <tbody id="pending_table_body">

                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- END Recent Orders Table -->

                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($hasManageRequisitionPermission)
            <!-- Recent Orders -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Requisition Requests</h3>
                        <div class="block-options">
                            <div class="dropdown d-inline-block">
                                <button type="button" class="btn btn-sm btn-alt-primary" id="dropdown-recent-orders-filters" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-fw fa-flask"></i>
                                    Filters
                                    <i class="fa fa-angle-down ml-1"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right font-size-sm" aria-labelledby="dropdown-recent-orders-filters">
                                    <a class="dropdown-item filter-link font-w500 d-flex align-items-center justify-content-between" data-status="pending" href="javascript:void(0)">
                                        Pending..
                                    </a>
                                    <a class="dropdown-item filter-link font-w500 d-flex align-items-center justify-content-between" data-status="approved" href="javascript:void(0)">
                                        Approved
                                    </a>
                                    <a class="dropdown-item filter-link font-w500 d-flex align-items-center justify-content-between" data-status="rejected" href="javascript:void(0)">
                                        Rejected
                                    </a>
                                    <a class="dropdown-item filter-link font-w500 d-flex align-items-center justify-content-between" data-status="canceled" href="javascript:void(0)">
                                        Canceled
                                    </a>
                                    <a class="dropdown-item filter-link font-w500 d-flex align-items-center justify-content-between" data-status="given" href="javascript:void(0)">
                                        Given
                                    </a>
                                    <a class="dropdown-item filter-link font-w500 d-flex align-items-center justify-content-between" data-status="all" href="javascript:void(0)">
                                        All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block-content">
                        <!-- Recent Orders Table -->
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped table-vcenter" id="requisition_table">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Employee ID</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Asset Type</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Specification</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Remarks</th>
                                    <th class="text-center">Requested</th>
                                </tr>
                                </thead>
                                <tbody id="requisition_table_body">

                                </tbody>
                            </table>
                        </div>
                        <!-- END Recent Orders Table -->
                        <!-- Pagination -->
                        <nav aria-label="Photos Search Navigation">
                            <ul class="pagination pagination-sm justify-content-end mt-2" id="pagination">

                            </ul>
                        </nav>
                        <!-- END Pagination -->

                    </div>
                </div>
                <!-- END Recent Orders -->
            @endif



    </div>
@endsection
@section('js_after')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var originalData = [];
            var currentPage = 1;
            function populateTable(data) {
                var tableBody = $('#requisition_table_body');
                tableBody.empty();
                originalData = data.data;
                if (originalData.length == 0) {
                    var noDataRow = '<tr><td colspan=9 class="text-center">No Data Found</td></tr>';
                    tableBody.append(noDataRow);
                    return;
                }
                for (var i = 0; i < originalData.length; i++) {
                    var record = originalData[i];
                    var row = '<tr>';
                    for (var j = 0; j < record.length; j++) {
                        row += '<td class="text-center">' + record[j] + '</td>';
                    }
                    row += '</tr>';
                    tableBody.append(row);
                }
            }
            function fetchData(page) {
                $.ajax({
                    url: '{{ url('/dashboard/get_requisition_data') }}',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        page: page
                    },
                    success: function (data) {
                        populateTable(JSON.parse(data.data));
                        updatePaginationLinks(data.total_pages, currentPage);
                    },
                    error: function (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }
            fetchData(currentPage);
            $('.pagination').on('click', '.page-link', function (e) {
                e.preventDefault();
                var clickedPage = $(this).data('page');
                if (clickedPage !== currentPage) {
                    currentPage = clickedPage;
                    fetchData(currentPage);
                }
            });
            function updatePaginationLinks(totalPages, currentPage) {
                var paginationContainer = $('#pagination');
                paginationContainer.empty();
                if(totalPages>1)
                {
                    var prevButton = '<li class="page-item">';
                    prevButton += '<a class="page-link" href="javascript:void(0)" data-page="' + ((currentPage == 0) ? (currentPage - 1) : 0) + '" aria-label="Previous">';
                    prevButton += 'Prev';
                    prevButton += '</a>';
                    prevButton += '</li>';
                    paginationContainer.append(prevButton);
                }
                for (var i = 1; i <= totalPages; i++) {
                    var pageLink = '<li class="page-item ' + (i === currentPage ? 'active' : '') + '">';
                    pageLink += '<a class="page-link" href="javascript:void(0)" data-page="' + i + '">' + i + '</a>';
                    pageLink += '</li>';
                    paginationContainer.append(pageLink);
                }
                if(totalPages>1)
                {
                    var nextButton = '<li class="page-item">';
                    nextButton += '<a class="page-link" href="javascript:void(0)" data-page="' +( (currentPage<=totalPages)? (currentPage + 1):totalPages)+ '" aria-label="Next">';
                    nextButton += 'Next';
                    nextButton += '</a>';
                    nextButton += '</li>';
                    paginationContainer.append(nextButton);
                }
            }
            $('.filter-link').on('click', function () {
                var status = $(this).data('status');
                if (status === 'all') {
                    $.ajax({
                        url: '{{ url('/dashboard/get_requisition_data') }}',
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            page: currentPage
                        },
                        success: function (data) {
                            populateTable(JSON.parse(data.data));
                            updatePaginationLinks(data.total_pages, currentPage);
                        },
                        error: function (error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                } else {
                    $.ajax({
                        url: '{{ url('/dashboard/get_requisition_data') }}',
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            page: currentPage,
                            status: status
                        },
                        success: function (data) {
                            var filteredData = JSON.parse(data.data).data.filter(function (record) {
                                var statusText = $(record[6]).text().trim();
                                return statusText === status;
                            });
                            populateTable({data: filteredData});
                        },
                        error: function (error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }
            });
        });
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            function populateTable(data) {
                var tableBody = $('#on_leave_table_body');
                tableBody.empty();
                if (data.data.length==0) {
                    var noDataRow = '<tr><td colspan=4 class="text-center">No Data Found</td></tr>';
                    tableBody.append(noDataRow);
                    return;
                }
                for (var i = 0; i < data.data.length; i++) {
                    var record = data.data[i];
                    var row = '<tr>' ;
                    for (var j = 0; j < record.length; j++) {
                        row = row + '<td class="text-center">' + record[j] + '</td>';
                    }
                    row+= '</tr>';
                    tableBody.append(row);
                }
            }
            $.ajax({
                url: '{{ url('/dashboard/get_on_leave_data') }}',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    populateTable(data);
                },
                error: function (error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            function populateTable(data) {
                var tableBody = $('#pending_table_body');
                tableBody.empty();
                if (data.data.length == 0) {
                    var noDataRow = '<tr><td colspan=7 class="text-center">No Data Found</td></tr>';
                    tableBody.append(noDataRow);
                    return;
                }
                for (var i = 0; i < data.data.length; i++) {
                    var record = data.data[i];
                    var row = '<tr>' ;
                    for (var j = 0; j < record.length; j++) {
                        row = row + '<td class="text-center">' + record[j] + '</td>';
                    }
                       row+= '</tr>';
                    tableBody.append(row);
                }
            }
            $.ajax({
                url: '{{ url('/dashboard/get_pending_leave_data') }}',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    populateTable(data);
                },
                error: function (error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    </script>

@endsection

