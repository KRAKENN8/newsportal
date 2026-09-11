<?php

use PHPUnit\Framework\TestCase;

final class NewsTest extends TestCase
{
    protected function setUp(): void
    {
        useFreshTestDatabase();
    }

    protected function tearDown(): void
    {
        Database::$testConnection = null;
    }

    public function testGetAllNewsReturnsAllRowsWithCategoryName(): void
    {
        $news = News::getAllNews();

        $this->assertCount(2, $news);
        // ordered DESC by id
        $this->assertSame('New Firewall Released', $news[0]['title']);
        $this->assertSame('Cybersecurity', $news[0]['category_name']);
        $this->assertSame('Quantum Computing Leap', $news[1]['title']);
    }

    public function testGetLast10NewsLimitsResults(): void
    {
        $pdo = Database::$testConnection;
        for ($i = 3; $i <= 10; $i++) {
            $pdo->exec("INSERT INTO news (id, title, text, picture, category_id, user_id)
                        VALUES ($i, 'Title $i', 'Text $i', '', 1, 1)");
        }

        $news = News::getLast10News();

        $this->assertCount(6, $news);
        $this->assertSame('Title 10', $news[0]['title']);
    }

    public function testGetNewsByCategoryIdFiltersCorrectly(): void
    {
        $news = News::getNewsByCategoryID(3);

        $this->assertCount(1, $news);
        $this->assertSame('New Firewall Released', $news[0]['title']);
    }

    public function testGetNewsByCategoryIdReturnsEmptyArrayForUnknownCategory(): void
    {
        $news = News::getNewsByCategoryID(999);

        $this->assertSame([], $news);
    }

    public function testGetNewsByIdReturnsSingleRowWithAuthorName(): void
    {
        $item = News::getNewsByID(1);

        $this->assertSame('Quantum Computing Leap', $item['title']);
        $this->assertSame('CyberAdmin', $item['author_name']);
        $this->assertSame('AI & Neural Networks', $item['category_name']);
    }

    public function testSearchNewsMatchesTitleCaseInsensitively(): void
    {
        $results = News::searchNews('quantum');

        $this->assertCount(1, $results);
        $this->assertSame('Quantum Computing Leap', $results[0]['title']);
    }

    public function testSearchNewsMatchesBodyText(): void
    {
        $results = News::searchNews('firewall');

        $this->assertCount(1, $results);
        $this->assertSame('New Firewall Released', $results[0]['title']);
    }

    public function testSearchNewsReturnsEmptyArrayWhenNothingMatches(): void
    {
        $results = News::searchNews('nonexistent-keyword-xyz');

        $this->assertSame([], $results);
    }

    public function testSearchNewsTrimsWhitespaceFromKeyword(): void
    {
        $results = News::searchNews('   quantum   ');

        $this->assertCount(1, $results);
    }
}
