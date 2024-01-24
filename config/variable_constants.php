<?php

return [
    'activation' => [
        'active' => 1,
        'inactive' => 0
    ],

    'check' => [
        'yes' => 1,
        'no' => 0
    ],

    'address' => [
        'present' => 1,
        'permanent' => 0
    ],

    'religion' => [
        'islam' => 1,
        'hindu' => 2,
        'christian' => 3,
        'buddhist' => 4,
        'others' => 5
    ],

    'blood_group' => [
        'O+' => 1,
        'O-' => 2,
        'A+' => 3,
        'A-' => 4,
        'B+' => 5,
        'B-' => 6,
        'AB+' => 7,
        'AB-' => 8
    ],

    'marital_status' => [
        'unmarried' => 0,
        'married' => 1,
        'divorced' => 2,
        'widowed' => 3
    ],

    'gender' => [
        'male' => 1,
        'female' => 2
    ],

    'requisition_status' => [
        'pending' => 0,
        'received' => 1,
        'approved' => 2,
        'canceled' => 3,
        'rejected' => 4,
        'processing' => 5,
        'delivered' => 6
    ],

    'status' => [
        'pending' => 0,
        'approved' => 1,
        'rejected' => 2,
        'canceled' => 3,
    ],

    'designation' =>[
        'hr'=>"HR",
        'super_user' => 1,
    ],

    'permission' => [
        'manageRequisition' => "manageRequisition",
        'manageLeaves' => "manageLeaves",
        'manageEmployee' => "manageEmployee",
    ],

    'leave_status' => [
        'pending' => 0,
        'line_manager_approval' => 1,
        'approved' => 2,
        'rejected' => 3,
        'canceled' => 4,
    ],

    'asset_condition' =>
    [
      'good' => 1,
      'need_repair' => 2,
      'damaged' => 3,
      'destroyed' => 4,
    ],

    'ticket_status' =>
    [
      'open' => 0,
      'hold' => 1,
      'completed' => 2,
      'closed' => 3,
    ],

    'ticket_priority' =>
    [
      'low' => 0,
      'medium' => 1,
      'high' => 2,
      'critical' => 3,
    ],

    'file_path' =>
    [
      'user' => 0,
      'leave' => 1,
      'event' => 2,
      'complaint' => 3,
    ],

    'warning_status' =>
    [
        'pending' => 0,
        'acknowledged' => 1,
    ],

    'meeting_status' =>
    [
        'pending' => 0,
        'completed' => 1,
    ],
    'note_status' =>
    [
        'pending' =>0,
        'approved' => 1,
    ]
];
