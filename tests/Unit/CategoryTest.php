<?php

use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    protected function setUp(): void
    {
        useFreshTestDatabase();
    }

    protected function tearDown(): void
    {
        Database::$testConnection = null;
    }

    public function testGetAllCategoryReturnsAllRowsOrderedById(): void
    {
        $categories = Category::getAllCategory();

        $this->assertCount(3, $categories);
        $this->assertSame('AI & Neural Networks', $categories[0]['name']);
        $this->assertSame('Hardware & Gadgets', $categories[1]['name']);
        $this->assertSame('Cybersecurity', $categories[2]['name']);
    }

    public function testGetCategoryByIdReturnsMatchingRow(): void
    {
        $category = Category::getCategoryByID(2);

        $this->assertNotFalse($category);
        $this->assertSame('Hardware & Gadgets', $category['name']);
    }

    public function testGetCategoryByIdReturnsFalseForUnknownId(): void
    {
        $category = Category::getCategoryByID(999);

        $this->assertFalse($category);
    }

    public function testGetCategoryByIdCastsNonNumericInputToZero(): void
    {
        // getCategoryByID casts $id to (int); a purely non-numeric string
        // becomes 0, matching no row.
        $category = Category::getCategoryByID("abc");

        $this->assertFalse($category);
    }

    public function testGetCategoryByIdIsNotVulnerableToSqlInjection(): void
    {
        // PHP's (int) cast reads only the leading digits, so this resolves
        // to id=1 and the " OR 1=1" suffix is never executed as SQL -
        // proving the value can't be used to inject arbitrary conditions.
        $category = Category::getCategoryByID("1 OR 1=1");

        $this->assertSame('AI & Neural Networks', $category['name']);
    }
}
