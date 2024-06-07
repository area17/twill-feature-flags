@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
    'label' => 'Code',
    'name' => 'code',
    'type' => 'text'
    ])

    @formField('checkbox', [
    'name' => 'publicly_available',
    'label' => 'Publicly available',
    ])

    @if(features_can_be_public_on_twill())
        @formField('checkbox', [
        'name' => 'publicly_available_twill_users',
        'label' => 'Publicly available for users logged in Twill',
        ])
    @endif

    @formField('input', [
    'label' => 'IP Addresses',
    'name' => 'ip_addresses',
    'type' => 'text',
    'note' => 'Use comma as a delimiter',
    'label' => 'Publicly available to those IP addresses',
    'rows' => 2,
    'translated' => false,
    ])

    @formField('input', [
    'label' => 'Description',
    'name' => 'description',
    'rows' => 4,
    'type' => 'textarea'
    ])
@stop
