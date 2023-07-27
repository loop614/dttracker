<?php

declare(strict_types=1);

namespace App\Validator\Tests;

use App\Validator\CategoryValidator;
use PHPUnit\Framework\TestCase;

class CategoryValidatorTest extends TestCase
{
    /**
     * @var \App\Validator\CategoryValidator
     */
    private CategoryValidator $sut;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->sut = new CategoryValidator();
    }

    /**
     * @return void
     */
    public function testBadCategoryInput(): void
    {
        $inputArray = [];
        $validationResponse = $this->sut->validate($inputArray);
        $this->assertSame($validationResponse->hasErrors(), true);
    }

    /**
     * @return void
     */
    public function testGoodCategoryInput(): void
    {
        $inputArray = [];
        $inputArray["name"] = "food";
        $validationResponse = $this->sut->validate($inputArray);
        $this->assertSame($validationResponse->hasErrors(), false);
    }
}
