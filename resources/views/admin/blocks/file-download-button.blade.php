@twillBlockTitle('File Download Button')
@twillBlockIcon('text')

@formField('input', [
    'name' => 'title',
    'label' => 'Title',
    'translated' => true,
    'maxlength' => 100
])

@formField('files', [
    'name' => 'single_file',
    'label' => 'Single file',
    'note' => 'Add one file'
])
