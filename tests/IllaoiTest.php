<?php namespace Tests;

use Laravelista\Illaoi\Illaoi;
use PHPUnit_Framework_TestCase;

class IllaoTest extends PHPUnit_Framework_TestCase
{
    protected $illaoi;

    public function setUp()
    {
        $this->illaoi = new Illaoi();
    }

    /** @test */
    public function it_generates_a_slug()
    {
        $this->assertEquals(
            'this-is-a-post-title',
            $this->illaoi->generate('This is a post title')
        );

        $this->assertEquals(
            'something-with-number-47',
            $this->illaoi->generate('Something with number 47')
        );

        $this->assertEquals(
            'abecedza',
            $this->illaoi->generate('Abećeđža')
        );

        $this->assertEquals(
            'cista-velika',
            $this->illaoi->generate('Čista velika')
        );

    }

}