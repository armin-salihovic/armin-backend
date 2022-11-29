@extends('twill::layouts.form')

@section('contentFields')
    @formField('block_editor', [
        'blocks' => ['paragraph', 'double-paragraph', 'article-title', 'article-image', 'article-introduction', 'article-hero-image']
    ])
@stop
