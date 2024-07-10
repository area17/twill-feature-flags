@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
    'label' => 'Code',
    'name' => 'code',
    'type' => 'text'
    ])

    @formField('input', [
    'label' => 'Description',
    'name' => 'description',
    'rows' => 4,
    'type' => 'textarea'
    ])

    @formField('checkbox', [
    'name' => 'publicly_available',
    'label' => 'Publicly available',
    ])

    @formField('input', [
    'label' => 'IP Addresses',
    'name' => 'ip_addresses',
    'type' => 'text',
    'note' => 'Use comma as a delimiter',
    'label' => 'Publicly available to those IP addresses',
    'rows' => 2,
    'translated' => false,
    ])

    @if (features_can_be_public_on_twill())
        @formField('checkbox', [
        'name' => 'publicly_available_twill_users',
        'label' => 'Publicly available for users logged in Twill',
        ])

        @formConnectedFields([
        'fieldName' => 'publicly_available_twill_users',
        'fieldValues' => true,
        ])
        @formField('browser', [
        'moduleName' => 'users',
        'name' => 'allowed_twill_users',
        'label' => 'Allowed users',
        'note' => 'If no users are selected, all users will be allowed',
        'max' => 999,
        ])
    @endformConnectedFields
    @endif
@stop
