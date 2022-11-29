@extends('twill::layouts.settings', [
    'additionalFieldsets' => [
        ['fieldset' => 'Social', 'label' => 'Social'],

        ['fieldset' => 'homepage', 'label' => 'Homepage'],
        ['fieldset' => 'projects', 'label' => 'Projects'],
        ['fieldset' => 'about', 'label' => 'About'],
        ['fieldset' => 'contact', 'label' => 'Contact'],
]])

@section('contentFields')
    @formField('medias', [
        'name' => 'favicon',
        'label' => 'Favicon',
    ])

    @formField('input', [
        'label' => 'Global Title',
        'name' => 'global_title',
    ])

    @formField('input', [
        'label' => 'Global Description',
        'name' => 'global_description',
    ])
@stop

@section('fieldsets')
    @formFieldset(['id' => 'social', 'title' => 'Social'])

    @formField('input', [
        'label' => 'Github',
        'name' => 'social_github',
    ])

    @formField('input', [
        'label' => 'LinkedIn',
        'name' => 'social_linkedin',
    ])

    @formField('input', [
        'label' => 'Discord',
        'name' => 'social_discord',
    ])

    @formField('input', [
        'label' => 'Email',
        'name' => 'social_email',
    ])

    @endformFieldset


    @formFieldset(['id' => 'homepage', 'title' => 'Homepage'])

        @formField('input', [
            'label' => 'Home title',
            'name' => 'home_title',
        ])

        @formField('input', [
            'label' => 'Home meta title',
            'name' => 'home_meta_title',
        ])

        @formField('input', [
            'label' => 'Home meta description',
            'name' => 'home_meta_description',
        ])

        @formField('medias', [
            'name' => 'home_og',
            'label' => 'Og Image',
        ])

    @endformFieldset

    @formFieldset(['id' => 'projects', 'title' => 'Projects'])

    @formField('input', [
        'label' => 'Projects title',
        'name' => 'projects_title',
    ])

    @formField('input', [
        'label' => 'Projects meta title',
        'name' => 'projects_meta_title',
    ])

    @formField('input', [
        'label' => 'Projects meta description',
        'name' => 'projects_meta_description',
    ])

    @formField('medias', [
        'name' => 'projects_og',
        'label' => 'Og Image',
    ])

    @endformFieldset

    @formFieldset(['id' => 'about', 'title' => 'About'])

    @formField('input', [
        'label' => 'About title',
        'name' => 'about_title',
    ])

    @formField('input', [
        'label' => 'About meta title',
        'name' => 'about_meta_title',
    ])

    @formField('input', [
        'label' => 'About meta description',
        'name' => 'about_meta_description',
    ])

    @formField('medias', [
        'name' => 'about_og',
        'label' => 'Og Image',
    ])

    @endformFieldset

    @formFieldset(['id' => 'contact', 'title' => 'Contact'])

    @formField('input', [
        'label' => 'Contact title',
        'name' => 'contact_title',
    ])

    @formField('input', [
        'label' => 'Contact meta title',
        'name' => 'contact_meta_title',
    ])

    @formField('input', [
        'label' => 'Contact meta description',
        'name' => 'contact_meta_description',
    ])

    @formField('medias', [
        'name' => 'contact_og',
        'label' => 'Og Image',
    ])

    @endformFieldset
@stop
