<?php namespace Arcanedev\LaravelSitemap\Tests;

use Arcanedev\LaravelSitemap\Contracts\Entities\ChangeFrequency;
use Arcanedev\LaravelSitemap\Entities\Sitemap;
use Arcanedev\LaravelSitemap\Entities\Url;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelSitemap\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelSitemap\LaravelSitemapServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            //
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sitemap.urls-max-size', 100);
    }

    /* -----------------------------------------------------------------
     |  Custom assertions
     | -----------------------------------------------------------------
     */

    /**
     * Asserts that an array has these specified keys.
     *
     * @param  array   $keys
     * @param  array   $array
     * @param  string  $message
     */
    public static function assertArrayHasKeys(array $keys, $array, $message = '')
    {
        foreach ($keys as $key) {
            static::assertArrayHasKey($key, $array, $message);
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * @return \Arcanedev\LaravelSitemap\Entities\Sitemap
     */
    protected function createPagesSitemap()
    {
        return tap(new Sitemap, function (Sitemap $sitemap) {
            $baseUrl = 'http://example.com';
            $lastMod = '2017-01-01 00:00:00';

            $sitemap->setPath("{$baseUrl}/sitemap-pages.xml");
            $sitemap->add(Url::make("{$baseUrl}/")->setLastMod($lastMod));
            $sitemap->add(Url::make("{$baseUrl}/about-us")->setLastMod($lastMod));
            $sitemap->add(Url::make("{$baseUrl}/contact")->setLastMod($lastMod));
        });
    }

    /**
     * @param  int  $times
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Sitemap
     */
    protected function createBlogSitemap($times = 10)
    {
        return tap(new Sitemap, function (Sitemap $sitemap) use ($times) {
            $baseUrl = 'http://example.com';
            $lastMod = '2017-01-02 00:00:00';

            $sitemap->setPath("{$baseUrl}/sitemap-blog.xml");
            $sitemap->create("{$baseUrl}/blog", function (Url $url) use ($lastMod) {
                $url->setTitle('Blog page')
                    ->setChangeFreq(ChangeFrequency::WEEKLY)
                    ->setPriority(.7)
                    ->setLastMod($lastMod);
            });

            foreach (range(1, $times) as $i) {
                $sitemap->add(
                    Url::make("{$baseUrl}/blog/posts/post-{$i}")
                       ->setTitle("Blog / Post {$i}")
                       ->setChangeFreq(ChangeFrequency::MONTHLY)
                       ->setPriority(.5)
                       ->setLastMod($lastMod)
                );
            }
        });
    }
}
