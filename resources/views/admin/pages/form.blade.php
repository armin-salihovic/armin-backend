@extends('twill::layouts.form')

@section('contentFields')
    @formField('block_editor', [
        'blocks' => [
            'paragraph',
            'article-paragraph-h3',
            'file-download-button',
            'double-paragraph',
            'article-title',
            'article-image',
            'article-introduction',
            'article-hero-image',
            'article-hero-image-no-lazy',
            'latest-cv-download-button',
        ]
    ])
@stop
