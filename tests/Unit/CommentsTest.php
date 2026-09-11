<?php

use PHPUnit\Framework\TestCase;

final class CommentsTest extends TestCase
{
    protected function setUp(): void
    {
        useFreshTestDatabase();
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        Database::$testConnection = null;
        $_SESSION = [];
    }

    public function testInsertCommentFailsWithoutLoggedInUser(): void
    {
        $result = Comments::insertComment('hello', 1);

        $this->assertFalse($result);
        $count = Comments::getCommentsCountByNewsID(1);
        $this->assertSame(1, (int)$count['count']); // unchanged (only the seeded comment)
    }

    public function testInsertCommentSucceedsForLoggedInUser(): void
    {
        $_SESSION['user_id'] = 2;

        $result = Comments::insertComment('Great article!', 1);

        $this->assertTrue($result);
        $count = Comments::getCommentsCountByNewsID(1);
        $this->assertSame(2, (int)$count['count']);
    }

    public function testGetCommentByNewsIdReturnsCommentsWithUsernameJoinedNewestFirst(): void
    {
        $_SESSION['user_id'] = 1;
        Comments::insertComment('Second comment', 1);

        $comments = Comments::getCommentByNewsID(1);

        $this->assertCount(2, $comments);
        $this->assertSame('Second comment', $comments[0]['text']);
        $this->assertSame('CyberAdmin', $comments[0]['username']);
        $this->assertSame('Amazing progress!', $comments[1]['text']);
        $this->assertSame('Alice', $comments[1]['username']);
    }

    public function testGetCommentByNewsIdReturnsEmptyArrayWhenNoComments(): void
    {
        $comments = Comments::getCommentByNewsID(2);

        $this->assertSame([], $comments);
    }

    public function testGetCommentsCountByNewsIdCountsOnlyMatchingNews(): void
    {
        $countNews1 = Comments::getCommentsCountByNewsID(1);
        $countNews2 = Comments::getCommentsCountByNewsID(2);

        $this->assertSame(1, (int)$countNews1['count']);
        $this->assertSame(0, (int)$countNews2['count']);
    }
}
