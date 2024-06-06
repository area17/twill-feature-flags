@extends('twill::layouts.form')

@php
    $allowUsersInTwill = filled(config('session_domain'));
@endphp

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

    @if($allowUsersInTwill)
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
