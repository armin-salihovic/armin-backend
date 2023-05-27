@extends('twill::layouts.form')

@section('contentFields')
    @formField('files', [
        'name' => 'cv',
        'label' => 'CV',
        'note' => 'Add CV'
    ])
@stop
