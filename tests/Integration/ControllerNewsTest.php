<?php

use PHPUnit\Framework\TestCase;

/**
 * These tests exercise Controller -> Model -> real view/*.php files together,
 * exactly as index.php + routing.php do in production. They run against the
 * project's ACTUAL view templates (not stubs), so they double as a
 * regression check that views don't fatal when given real model data.
 *
 * Controller paths that call exit() (InsertComment/profile/logout when not
 * authenticated) are intentionally NOT exercised here — PHPUnit runs in one
 * PHP process, and exit() would kill the whole test run. Those flows are
 * covered by the Playwright E2E suite instead (see e2e/tests/).
 */
final class ControllerNewsTest extends TestCase
{
    protected function setUp(): void
    {
        useFreshTestDatabase();
    }

    protected function tearDown(): void
    {
        Database::$testConnection = null;
    }

    private function captureOutput(callable $fn): string
    {
        ob_start();
        $fn();
        return ob_get_clean();
    }

    public function testStartSiteRendersLatestNews(): void
    {
        $output = $this->captureOutput(fn() => Controller::StartSite());

        $this->assertStringContainsString('Quantum Computing Leap', $output);
    }

    public function testAllNewsRendersEveryArticle(): void
    {
        $output = $this->captureOutput(fn() => Controller::AllNews());

        $this->assertStringContainsString('Quantum Computing Leap', $output);
        $this->assertStringContainsString('New Firewall Released', $output);
    }

    public function testNewsByCatIdRendersOnlyMatchingArticles(): void
    {
        $output = $this->captureOutput(fn() => Controller::NewsByCatID(3));

        $this->assertStringContainsString('New Firewall Released', $output);
        $this->assertStringNotContainsString('Quantum Computing Leap', $output);
    }

    public function testNewsByIdRendersSingleArticleWithAuthor(): void
    {
        $output = $this->captureOutput(fn() => Controller::NewsByID(1));

        $this->assertStringContainsString('Quantum Computing Leap', $output);
        $this->assertStringContainsString('CyberAdmin', $output);
    }

    public function testSearchNewsRendersMatchingResults(): void
    {
        $output = $this->captureOutput(fn() => Controller::SearchNews('firewall'));

        $this->assertStringContainsString('New Firewall Released', $output);
        $this->assertStringNotContainsString('Quantum Computing Leap', $output);
    }

    public function testSearchNewsRendersNothingForUnmatchedKeyword(): void
    {
        $output = $this->captureOutput(fn() => Controller::SearchNews('zzz-nonexistent-zzz'));

        $this->assertStringNotContainsString('Quantum Computing Leap', $output);
        $this->assertStringNotContainsString('New Firewall Released', $output);
    }

    public function testAboutSiteRendersWithoutErrors(): void
    {
        $output = $this->captureOutput(fn() => Controller::AboutSite());

        $this->assertNotSame('', trim($output));
    }

    public function testError404RendersWithoutErrors(): void
    {
        $output = $this->captureOutput(fn() => Controller::error404());

        $this->assertNotSame('', trim($output));
    }
}
