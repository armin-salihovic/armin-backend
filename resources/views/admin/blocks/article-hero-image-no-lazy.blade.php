@twillBlockTitle('Hero image no lazy')
@twillBlockIcon('text')

@formField('select', [
    'name' => 'position',
    'label' => 'Position',
    'placeholder' => 'Select the position',
    'default' => 2,
    'options' => [
        [
            'value' => 1,
            'label' => 'Top'
        ],
        [
            'value' => 2,
            'label' => 'Center'
        ],
        [
            'value' => 3,
            'label' => 'Bottom'
        ]
    ]
])

@formField('medias', [
    'name' => 'image',
    'label' => 'Image',
])

