@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
        'name' => 'description',
        'label' => 'Description',
        'maxlength' => 100
    ])

    @formField('medias', [
        'name' => 'image',
        'label' => 'Image',
        'required' => true,
    ])

    @formField('block_editor', [
        'blocks' => ['paragraph', 'article-title', 'article-image']
    ])
@stop