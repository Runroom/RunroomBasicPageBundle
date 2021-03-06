<?php

declare(strict_types=1);

/*
 * This file is part of the Runroom package.
 *
 * (c) Runroom <runroom@runroom.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Runroom\BasicPageBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Runroom\BasicPageBundle\Factory\BasicPageFactory;
use Runroom\BasicPageBundle\Service\BasicPageAlternateLinksProvider;
use Runroom\BasicPageBundle\ViewModel\BasicPageViewModel;
use Zenstruck\Foundry\Test\Factories;

class BasicPageAlternateLinksProviderTest extends TestCase
{
    use Factories;

    private BasicPageAlternateLinksProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new BasicPageAlternateLinksProvider();
    }

    /** @test */
    public function itCanGenerateAlternateLinks(): void
    {
        $basicPage = BasicPageFactory::new()->withTranslations(['en', 'es'])->create()->object();
        $model = new BasicPageViewModel();
        $model->setBasicPage($basicPage);

        self::assertTrue($this->provider->canGenerateAlternateLink($model, 'es'));
        self::assertTrue($this->provider->canGenerateAlternateLink($model, 'en'));
    }

    /** @test */
    public function itCantGenerateAlternateLinksIfNoBasicPageIsProvided(): void
    {
        $model = new BasicPageViewModel();

        self::assertFalse($this->provider->canGenerateAlternateLink($model, 'es'));
    }

    /** @test */
    public function itReturnsRouteParameters(): void
    {
        $basicPage = BasicPageFactory::new()->withTranslations(['en', 'es'])->create()->object();
        $model = new BasicPageViewModel();
        $model->setBasicPage($basicPage);

        foreach (['en', 'es'] as $locale) {
            self::assertSame(
                ['slug' => $basicPage->getSlug($locale)],
                $this->provider->getParameters($model, $locale)
            );
        }
    }

    /** @test */
    public function itReturnsNoRouteParametersIfNoBasicPageIsProvided(): void
    {
        self::assertNull($this->provider->getParameters(new BasicPageViewModel(), 'en'));
    }

    /** @test */
    public function itProvidesAlternateLinks(): void
    {
        self::assertTrue($this->provider->providesAlternateLinks('runroom.basic_page.route.show'));
    }
}
