<?php

namespace Illuminate\Tests\Filesystem;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Manager;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class FileSystemManagerTest extends TestCase
{

    private $tempDir;
    private $driver;

    public function setUp()
    {
        $this->tempDir = __DIR__ . '/tmp';
        $this->driver  = new FilesystemManager($this->CreateAppInstance());
        mkdir($this->tempDir);
    }

    public function tearDown()
    {
        m::close();
        $files = new Filesystem();
        $files->deleteDirectory($this->tempDir);
    }

    private function CreateAppInstance()
    {
        $configArr = [
            'filesystems.default'      => 'local',
            'filesystems.cloud'        => 's3',
            'filesystems.disks.local'  => [
                'driver' => 'local',
                'root'   => './app/storage',
            ],
            'filesystems.disks.public' => [
                'driver'     => 'local',
                'root'       => '',
                'url'        => '',
                'visibility' => 'public',
            ],
            'filesystems.disks.s3'     => [
                'driver' => 's3',
                'key'    => '',
                'secret' => '',
                'region' => '',
                'bucket' => '',
                'url'    => '',
            ],
        ];
        $app       = new Application;
        $config    = m::mock(Repository::class);
        $config->shouldReceive('set')->once()->andReturn($configArr);
        $app['config'] = $config->set($configArr);

        return $app;
    }

    /**
     * Test that drive method return Manger object
     *
     * @author Mohammed Elkady <m.elkady365@gmail.com>
     *
     */
    public function testDriveMethod()
    {
        $this->assertInstanceOf(Manager::class, $this->driver);
    }

    /**
     * DESC
     *
     * @author Mohammed Elkady <m.elkady365@gmail.com>
     *
     */
    public function testGetDefaultCloudDriver()
    {
        $this->assertEquals('s3', $this->driver->getDefaultCloudDriver());
    }
}