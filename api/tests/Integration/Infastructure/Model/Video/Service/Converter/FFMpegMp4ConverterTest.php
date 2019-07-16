<?php

declare(strict_types=1);

namespace Api\Test\Integration\Infastructure\Model\Video\Service\Converter;

use PHPUnit\Framework\TestCase;
use Api\Model\Video\Service\Processor\Video;
use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Size;
use Api\Infrastructure\Model\Video\Service\Processor\Converter\FFMpegMp4Converter;

class FFMpegMp4ConverterTest extends TestCase
{
    private $path;

    /**
     * @var FFMpegMp4Converter
     */
    private $converter;

    protected function setUp(): void
    {
        parent::setUp();
        $path = realpath('var/test');
        $this->initDemoFiles($path);
        $this->path = $path;
        $this->converter = new FFMpegMp4Converter($this->path);
    }

    public function testCan(): void
    {
        self::assertTrue(
            $this->converter->canConvert(new Format('3gp'), new Format('mp4'))
        );

        self::assertFalse(
            $this->converter->canConvert(new Format('3gp'), new Format('webm'))
        );
    }

    public function testConvert(): void
    {
        $video = new Video(
            'video.3gp',
            new Format('3gp'),
            new Size(352, 288)
        );

        $newVideo = $this->converter->convert(
            $video,
            new Format('mp4'),
            new Size(320, 240)
        );

        self::assertEquals(
            'video_320x240.mp4',
            $newVideo->getPath()
        );

        self::assertEquals(320, $newVideo->getSize()->getWidth());
        self::assertEquals(240, $newVideo->getSize()->getHeight());
        self::assertFileExists($this->path . '/video_320x240.mp4');
    }

    protected function initDemoFiles(string $path): void
    {
        if (file_exists($path . '/video.3gp')) {
            unlink($path . '/video.3gp');
        }

        copy(\dirname(__DIR__) . '/data/video.3gp', $path . '/video.3gp');

        if (file_exists($path . '/video_320x240.mp4')) {
            unlink($path . '/video_320x240.mp4');
        }
    }
}
