@twillBlockTitle('Double Paragraph')
@twillBlockIcon('text')

@formField('input', [
    'name' => 'title',
    'label' => 'Title',
    'maxlength' => 100
])

@formField('wysiwyg', [
    'name' => 'paragraph1',
    'label' => 'Text',
    'toolbarOptions' => [
        ['header' => [2, 3, 4, 5, 6, false]],
        [ 'color' => ['','#0062cc','#dc3545']],
        'bold',
        'italic',
        'underline',
        'strike',
        ["script" => "super"],
        ["script" => "sub"],
        "blockquote",
        ['list' => 'ordered'],
        ['list' => 'bullet'],
        ['indent' => '-1'],
        ['indent' => '+1'],
        ["align" => []],
        ["direction" => "rtl"],
        'link',
        'video',
        "clean",
    ],
    'placeholder' => 'Enter the text',
    'editSource' => true,
])

@formField('wysiwyg', [
    'name' => 'paragraph2',
    'label' => 'Text',
    'toolbarOptions' => [
        [
            'header' => [2, 3, 4, 5, 6, false]
        ],
        [
            'color' => ['','#0062cc','#dc3545']
        ],
        'bold',
        'italic',
        'underline',
        'strike',
        ["script" => "super"],
        ["script" => "sub"],
        "blockquote",
        ['list' => 'ordered'],
        ['list' => 'bullet'],
        ['indent' => '-1'],
        ['indent' => '+1'],
        ["align" => []],
        ["direction" => "rtl"],
        'link',
        'video',
        "clean",
    ],
    'placeholder' => 'Enter the text',
    'editSource' => true,
])
