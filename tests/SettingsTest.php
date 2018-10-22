<?php

namespace Tests;

use CreateSettingsTable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Factory as CacheContract;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\Schema;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Tantana5\Setting\EloquentStorage;
use Tantana5\Setting\Setting;

class SettingsTest extends TestCase
{
    public function setUp()
    {
        $this->setMock();
        $this->migrationUp();
    }

    public function tearDown()
    {
        $this->migrationDown();

        m::close();
    }

    public function testSimpleGetSetWithoutCache()
    {
        $cache = m::mock(CacheContract::class);
        $cache->shouldReceive('has')->andReturn(false);
        $cache->shouldReceive('add')->andReturn(true);

        $setting = new Setting(new EloquentStorage(), $cache);

        $setting->set('key', 'value');

        $this->assertSame('value', $setting->get('key'));
    }

    public function testSimpleGetSetWithCache()
    {
        $cache = m::mock(CacheContract::class);
        $cache->shouldReceive('has')->andReturn(true);
        $cache->shouldReceive('get')->with('key@')->andReturn('value');
        $cache->shouldReceive('forget')->andReturn(true);

        $setting = new Setting(new EloquentStorage(), $cache);

        $setting->set('key', 'value');

        $this->assertSame('value', $setting->get('key'));
    }

    public function testSimpleGetSetDotValueWithoutCache()
    {
        $cache = m::mock(CacheContract::class);
        $cache->shouldReceive('has')->andReturn(false);
        $cache->shouldReceive('add')->andReturn(true);

        $setting = new Setting(new EloquentStorage(), $cache);

        $setting->set('key', $arr = ['a' => 'va', 'b' => 'vb']);

        $this->assertSame($arr, $setting->get('key'));
        $this->assertSame($arr['a'], $setting->get('key.a'));

        $setting->set('key2.c', 'val2c');

        $this->assertSame('val2c', $setting->get('key2.c'));
        $this->assertSame(['c' => 'val2c'], $setting->get('key2'));
    }

    public function testLang()
    {
        $cache = m::mock(CacheContract::class);
        $cache->shouldReceive('has')->andReturn(false);
        $cache->shouldReceive('add')->andReturn(true);

        $setting = new Setting(new EloquentStorage(), $cache);

        $setting->lang('lang1')->set('key', 'val1');

        $this->assertSame('val1', $setting->lang('lang1')->get('key'));
        $this->assertNull($setting->get('key'));
    }

    public function testForget()
    {
        $cache = m::mock(CacheContract::class);
        $cache->shouldReceive('has')->andReturn(false);
        $cache->shouldReceive('add')->andReturn(true);
        $cache->shouldReceive('forget')->andReturn(true);

        $setting = new Setting(new EloquentStorage(), $cache);

        $setting->set('key', 'value');

        $this->assertSame('value', $setting->get('key'));

        $setting->forget('key');

        $this->assertNull($setting->get('key'));
    }

    public function testDefault()
    {
        $cache = m::mock(CacheContract::class);
        $cache->shouldReceive('has')->andReturn(false);
        $cache->shouldReceive('add')->andReturn(true);

        $setting = new Setting(new EloquentStorage(), $cache);

        $setting->set('key', 'value');

        $this->assertSame('value', $setting->get('key', 'new-value'));
        $this->assertSame('new-value', $setting->get('not-exists', 'new-value'));

        $setting->set('key2.a', 'value-a');

        $this->assertSame(['a' => 'value-a'], $setting->get('key2', 'new-value'));
        $this->assertSame('value-a', $setting->get('key2.a', 'new-value'));
        $this->assertSame('new-value', $setting->get('key2.b', 'new-value'));
    }

    protected function setMock()
    {
        $app = m::mock(Container::class);
        $app->shouldReceive('instance');
        $app->shouldReceive('offsetGet')->with('db')->andReturn(
            m::mock('db')->shouldReceive('connection')->andReturn(
                m::mock('connection')->shouldReceive('getSchemaBuilder')->andReturn('schema')->getMock()
            )->getMock()
        );
        $app->shouldReceive('offsetGet');

        Schema::setFacadeApplication($app);
        Schema::swap(Manager::Schema());
    }

    public function testNullValue()
    {
        $cache = m::mock(CacheContract::class);
        $cache->shouldReceive('has')->andReturn(false);
        $cache->shouldReceive('add')->andReturn(true);

        $setting = new Setting(new EloquentStorage(), $cache);
        $setting->set('a', null);
        $this->assertTrue($setting->get('a') === null);
        $this->assertTrue($setting->get('b') === null);

        $setting->set('foo.bar', null);
        $this->assertTrue($setting->get('foo.bar') === null);

        $this->assertTrue($setting->get('foo.xxx') === null);

        $setting->set('foo.zzz', 0);
        $this->assertTrue($setting->get('foo.zzz') === 0);

        $setting->set('foo.yyy', []);
        $this->assertTrue($setting->get('foo.yyy') === []);
    }

    protected function migrationUp()
    {
        (new CreateSettingsTable())->up();
    }

    protected function migrationDown()
    {
        (new CreateSettingsTable())->down();
    }
}
