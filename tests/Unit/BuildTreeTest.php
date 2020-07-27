<?php

namespace Tests\Unit;

use App\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class BuildTreeTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testFromTheSource()
    {
        $rows = array(
            array(
                'id' => 1,
                'parent_id' => 0,
            ),
            array(
                'id' => 2,
                'parent_id' => 1,
            ),
            array(
                'id' => 3,
                'parent_id' => 1,
            ),
            array(
                'id' => 4,
                'parent_id' => 2,
            ),
            array(
                'id' => 5,
                'parent_id' => 4,
            ),
            array(
                'id' => 6,
                'parent_id' => 3,
            ),
            array(
                'id' => 7,
                'parent_id' => 2,
            ),
            array(
                'id' => 8,
                'parent_id' => 3,
            )
        );

        $array = buildTree($rows, 0);
//        dd($array);

        $this->assertEquals(1, count($array));
        $this->assertEquals(2, count($array[0]['children']));
        $this->assertEquals(2, count($array[0]['children'][0]['children']));
        $this->assertEquals(2, count($array[0]['children'][1]['children']));
        $this->assertEquals(1, count($array[0]['children'][0]['children'][0]['children']));
        $this->assertEquals(5, $array[0]['children'][0]['children'][0]['children'][0]['id']);

        $array = buildTree($rows, 3);
        $this->assertEquals(2, count($array));
    }

    public function testGetPlainArrayOfIds()
    {
        $rows = array(
            array(
                'id' => 1,
                'parent_id' => 0,
            ),
            array(
                'id' => 2,
                'parent_id' => 1,
            ),
            array(
                'id' => 3,
                'parent_id' => 1,
            ),
            array(
                'id' => 4,
                'parent_id' => 2,
            ),
            array(
                'id' => 5,
                'parent_id' => 4,
            ),
            array(
                'id' => 6,
                'parent_id' => 3,
            ),
            array(
                'id' => 7,
                'parent_id' => 2,
            ),
            array(
                'id' => 8,
                'parent_id' => 3,
            )
        );

        $array = buildTree($rows, 2);
        $ids=collectValues($array, 'id', []);
//        dd($ids);

        $this->assertEquals(3, count($ids));
        $this->assertEquals([4, 5, 7], $ids);
    }
}
