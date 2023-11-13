@extends('backend.layouts.master')
@section('css_after')

@endsection
@section('content')
    <div class="content">
        <!-- Quick Actions -->
        <div class="row">
            <div class="col-6">
                <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-h2 text-dark">
                            <i class="fa fa-pencil-alt"></i>
                        </div>
                    </div>
                    <div class="block-content py-2 bg-body-light">
                        <p class="font-w600 font-size-sm text-muted mb-0">
                            Edit Profile
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-h2 text-danger">
                            <i class="fa fa-lock-open"></i>
                        </div>
                    </div>
                    <div class="block-content py-2 bg-body-light">
                        <p class="font-w600 font-size-sm text-danger mb-0">
                            Change Password
                        </p>
                    </div>
                </a>
            </div>
        </div>
        <!-- END Quick Actions -->

        <!-- User Info -->
        <div class="block block-rounded">
            <div class="block-content text-center">
                <div class="py-4">
                    <div class="mb-3">
                        <img class="img-avatar" src="{{ asset('backend/media/avatars/avatar13.jpg') }}" alt="">
                    </div>
                    <h1 class="font-size-lg mb-0">
                        {{ $user->full_name }} <i class="fa fa-star text-warning" data-toggle="tooltip" title="Premium Member"></i>
                    </h1>
                    <p class="font-size-sm text-muted">{{ $user->basicInfo->designation->name }}</p>
                </div>
            </div>
            <div class="block-content bg-body-light text-center">
                <div class="row items-push text-uppercase">
                    <div class="col-6 col-md-3">
                        <div class="font-w600 text-dark mb-1">Orders</div>
                        <a class="link-fx font-size-h3 text-primary" href="javascript:void(0)">5</a>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="font-w600 text-dark mb-1">Orders Value</div>
                        <a class="link-fx font-size-h3 text-primary" href="javascript:void(0)">$3.580,00</a>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="font-w600 text-dark mb-1">Cart</div>
                        <a class="link-fx font-size-h3 text-primary" href="javascript:void(0)">4</a>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="font-w600 text-dark mb-1">Referred</div>
                        <a class="link-fx font-size-h3 text-primary" href="javascript:void(0)">3</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END User Info -->

        <!-- Addresses -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Addresses (2)</h3>
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
                                <div class="font-size-h4 mb-1">John Parker</div>
                                <address class="font-size-sm">
                                    Sunrise Str 620<br>
                                    Melbourne<br>
                                    Australia, 11-587<br><br>
                                    <i class="fa fa-phone"></i> (999) 888-55555<br>
                                    <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)">company@example.com</a>
                                </address>
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
                                <div class="font-size-h4 mb-1">John Parker</div>
                                <address class="font-size-sm">
                                    Sunrise Str 620<br>
                                    Melbourne<br>
                                    Australia, 11-587<br><br>
                                    <i class="fa fa-phone"></i> (999) 888-55555<br>
                                    <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)">company@example.com</a>
                                </address>
                            </div>
                        </div>
                        <!-- END Shipping Address -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Addresses -->

        <!-- Shopping Cart -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Shopping Cart (4)</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <th class="d-none d-md-table-cell">Product</th>
                            <th class="d-none d-sm-table-cell text-center">Added</th>
                            <th>Status</th>
                            <th class="d-none d-sm-table-cell text-right">Value</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_product_edit.html">
                                    <strong>PID.0154</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell font-size-sm">
                                <a href="be_pages_ecom_product_edit.html">Product #4</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">19/12/2019</td>
                            <td>
                                <span class="badge badge-danger">Out of Stock</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$35,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_product_edit.html">
                                    <strong>PID.0153</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell font-size-sm">
                                <a href="be_pages_ecom_product_edit.html">Product #3</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">16/10/2019</td>
                            <td>
                                <span class="badge badge-danger">Out of Stock</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$87,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_product_edit.html">
                                    <strong>PID.0152</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell font-size-sm">
                                <a href="be_pages_ecom_product_edit.html">Product #2</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">15/01/2019</td>
                            <td>
                                <span class="badge badge-success">Available</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$87,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_product_edit.html">
                                    <strong>PID.0151</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell font-size-sm">
                                <a href="be_pages_ecom_product_edit.html">Product #1</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">15/11/2019</td>
                            <td>
                                <span class="badge badge-success">Available</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$94,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Shopping Cart -->

        <!-- Past Orders -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Past Orders (5)</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <th class="d-none d-md-table-cell text-center">Products</th>
                            <th class="d-none d-sm-table-cell text-center">Submitted</th>
                            <th>Status</th>
                            <th class="d-none d-sm-table-cell text-right">Value</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    <strong>ORD.0625</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a href="javascript:void(0)">5</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">10/12/2019</td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$230,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    <strong>ORD.0624</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a href="javascript:void(0)">3</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">17/10/2019</td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$235,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    <strong>ORD.0623</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a href="javascript:void(0)">4</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">26/03/2019</td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$38,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    <strong>ORD.0622</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a href="javascript:void(0)">6</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">02/12/2019</td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$238,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    <strong>ORD.0621</strong>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a href="javascript:void(0)">8</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">06/05/2019</td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$233,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html" data-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Past Orders -->

        <!-- Referred Members -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Referred Members (3)</h3>
            </div>
            <div class="block-content">
                <div class="row row-deck">
                    <div class="col-md-4">
                        <!-- Referred User -->
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="font-w600 mb-1">Susan Day</div>
                                    <div class="font-size-sm text-muted">4 Orders</div>
                                </div>
                                <div class="ml-3">
                                    <img class="img-avatar" src="assets/media/avatars/avatar3.jpg" alt="">

                                </div>
                            </div>
                        </a>
                        <!-- END Referred User -->
                    </div>
                    <div class="col-md-4">
                        <!-- Referred User -->
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="font-w600 mb-1">Jesse Fisher</div>
                                    <div class="font-size-sm text-muted">5 Orders</div>
                                </div>
                                <div class="ml-3">
                                    <img class="img-avatar" src="assets/media/avatars/avatar11.jpg" alt="">

                                </div>
                            </div>
                        </a>
                        <!-- END Referred User -->
                    </div>
                    <div class="col-md-4">
                        <!-- Referred User -->
                        <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="font-w600 mb-1">Helen Jacobs</div>
                                    <div class="font-size-sm text-muted">3 Orders</div>
                                </div>
                                <div class="ml-3">
                                    <img class="img-avatar" src="assets/media/avatars/avatar3.jpg" alt="">

                                </div>
                            </div>
                        </a>
                        <!-- END Referred User -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Referred Members -->

        <!-- Private Notes -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Private Notes</h3>
            </div>
            <div class="block-content">
                <p class="alert alert-info font-size-sm">
                    <i class="fa fa-fw fa-info mr-1"></i> This note will not be displayed to the customer.
                </p>
                <form action="be_pages_ecom_customer.html" onsubmit="return false;">
                    <div class="form-group">
                        <label for="one-ecom-customer-note">Note</label>
                        <textarea class="form-control" id="one-ecom-customer-note" name="one-ecom-customer-note" rows="4" placeholder="Maybe a special request?"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-alt-success">Add Note</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Private Notes -->
    </div>

@endsection

@section('js_after')

@endsection
