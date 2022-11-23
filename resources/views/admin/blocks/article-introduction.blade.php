@twillBlockTitle('Article Introduction')
@twillBlockIcon('text')

@formField('wysiwyg', [
    'name' => 'paragraph',
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
