<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\MyClasses\Dollar;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $five = new dollar(5);
        $x =  $five->multiply(2);
        echo $x->amount;
        $y = $five->divide(5);
        echo $y->amount;
        $this->assertTrue($y->amount==1);
        $this->assertTrue($x->amount==10);
    }
}
