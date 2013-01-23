# Multi Array Library

This class turns multidimensional arrays into one which can have their embedded
indexes accessible and settible using the dot notation.

# Example Usage

    // Instantiate object and pass an array to the constructor
    $arr = new Multi\Arr(array(
        'level1'    => array(
            'level2'    => array(
                'level3'    => array(
                        'name'  => 'Tester',
                        'demo'  => 'This is a demo',
                    ),
                ),
            ),
        'anotherIndex'  => array(
            'test'  => 'Yet another test',
            ),
        ),
    );

    // Access specific level of the array
    var_dump($arr['level1.level2.level3']);

    // Set a embedded value
    $arr['level1.testset'] = 'A test of setting.';

    // Unset an embedded value
    unset($arr['anotherIndex.test']);

    // Change the delimiter to something else
    $arr->setDelimiter('-');

    // Now access elements using the new delimiter
    var_dump($arr['level1-testset']);