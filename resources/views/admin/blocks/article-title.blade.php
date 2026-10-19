@twillBlockTitle('Article Title')
@twillBlockIcon('text')

@formField('input', [
    'name' => 'year',
    'label' => 'Year',
    'maxlength' => 100
])

@formField('input', [
    'name' => 'title',
    'label' => 'Title',
    'translated' => true,
    'maxlength' => 200
])

@formField('input', [
    'name' => 'subtitle',
    'label' => 'Subtitle',
    'translated' => true,
    'maxlength' => 100
])
