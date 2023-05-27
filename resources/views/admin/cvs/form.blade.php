@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
        'label' => 'Trigger',
        'name' => 'trigger',
        'note' => 'Trigger for updating cache id',
        'disabled' => true,
    ])

    @formField('files', [
        'name' => 'cv',
        'label' => 'CV',
        'note' => 'Add CV'
    ])
@stop
