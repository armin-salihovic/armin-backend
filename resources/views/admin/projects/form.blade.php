@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
        'name' => 'description',
        'label' => 'Description',
        'type' => 'textarea',
        'rows' => 2,
        'maxlength' => 200,
    ])

    @formField('medias', [
        'name' => 'image',
        'label' => 'Image',
        'required' => true,
    ])

    @formField('repeater', ['type' => 'project-technology'])

    @formField('block_editor', [
        'blocks' => [
            'paragraph',
            'article-title',
            'article-image',
            'article-introduction',
            'article-hero-image',
            'article-hero-image-no-lazy',
        ]
    ])
@stop
