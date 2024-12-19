@extends('admin.master')

@section('title', 'Account Settings')

@section('content')
    <!-- Row start -->
    <div class="row">
        <div class="col-xxl-8 col-xl-7 col-lg-7 col-md-6 col-sm-12 col-12">
            <div class="row">
                <div class="col-sm-6 col-12">
                    <div class="d-flex flex-row">
                        <img src="{{ asset('../adminn/assets/images/user2.png') }}" class="img-fluid change-img-avatar" alt="Image">
                        <div id="dropzone-sm" class="mb-4 dropzone-dark">
                            <form action="/upload" class="dropzone needsclick dz-clickable" id="demo-upload">
                                <div class="dz-message needsclick">
                                    <button type="button" class="dz-button">Change Image.</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach (['fullName' => 'Full Name', 'emailID' => 'Email ID', 'phoneNo' => 'Phone'] as $id => $label)
                    <div class="col-xxl-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="{{ $id }}" class="form-label">{{ $label }}</label>
                            <input type="text" class="form-control" id="{{ $id }}" placeholder="{{ $label }}">
                        </div>
                    </div>
                @endforeach

                <div class="col-xxl-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="Address">
                    </div>
                </div>

                <div class="col-xxl-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" placeholder="City">
                    </div>
                </div>

                <div class="col-xxl-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="state" placeholder="State">
                    </div>
                </div>

                <div class="col-xxl-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label for="zipCode" class="form-label">Zip Code</label>
                        <input type="text" class="form-control" id="zipCode" placeholder="Zip Code">
                    </div>
                </div>

                <div class="col-xxl-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-control" id="country">
                            @foreach(['United States', 'Australia', 'Canada', 'Germany', 'India', 'Japan', 'England', 'Brazil'] as $country)
                                <option>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xxl-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label for="enterPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="enterPassword" placeholder="Enter Password">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-lg-5 col-md-6 col-sm-12 col-12">
            <div class="account-settings-block">
                <div class="settings-block">
                    <div class="settings-block-title">Change Plan</div>
                    <div class="settings-block-body">
                        <div class="settings-block-body">
                            <div class="pricing-change-plan">
                                <a href="#" class="shade-blue active-plan">
                                    <h5>$29</h5>
                                    <h6>Basic</h6>
                                </a>
                                <a href="#" class="shade-green">
                                    <h5>$59</h5>
                                    <h6>Business</h6>
                                </a>
                                <a href="#" class="shade-red">
                                    <h5>$99</h5>
                                    <h6>Enterprise</h6>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="settings-block">
                    <div class="settings-block-title">Other Settings</div>
                    <div class="settings-block-body">
                        <div class="list-group">
                            @foreach (['Show desktop notifications' => 'showNotifications', 'Show email notifications' => 'showEmailNotifications', 'Show chat notifications' => 'showChatNotifications', 'Show purchase history' => 'showPurchaseNotifications', 'Show orders' => 'showOrders', 'Show alerts' => 'showAlerts'] as $label => $id)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>{{ $label }}</div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="{{ $id }}" @if($id == 'showEmailNotifications') checked @endif>
                                        <label class="form-check-label" for="{{ $id }}"></label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-12">
            <hr>
            <button class="btn btn-info">Save Settings</button>
        </div>
    </div>
    <!-- Row end -->
@endsection
