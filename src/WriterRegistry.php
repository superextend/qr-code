<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace superextend\QrCode;

use superextend\QrCode\Exception\InvalidWriterException;
use superextend\QrCode\Writer\BinaryWriter;
use superextend\QrCode\Writer\DebugWriter;
use superextend\QrCode\Writer\EpsWriter;
use superextend\QrCode\Writer\PngWriter;
use superextend\QrCode\Writer\SvgWriter;
use superextend\QrCode\Writer\WriterInterface;

class WriterRegistry implements WriterRegistryInterface
{
    /** @var WriterInterface[] */
    private $writers = [];

    /** @var WriterInterface|null */
    private $defaultWriter;

    public function loadDefaultWriters(): void
    {
        if (count($this->writers) > 0) {
            return;
        }

        $this->addWriters([
            new BinaryWriter(),
            new DebugWriter(),
            new EpsWriter(),
            new PngWriter(),
            new SvgWriter(),
        ]);

        $this->setDefaultWriter('png');
    }

    public function addWriters(iterable $writers): void
    {
        foreach ($writers as $writer) {
            $this->addWriter($writer);
        }
    }

    public function addWriter(WriterInterface $writer): void
    {
        $this->writers[$writer->getName()] = $writer;
    }

    public function getWriter(string $name): WriterInterface
    {
        $this->assertValidWriter($name);

        return $this->writers[$name];
    }

    public function getDefaultWriter(): WriterInterface
    {
        if ($this->defaultWriter instanceof WriterInterface) {
            return $this->defaultWriter;
        }

        throw new InvalidWriterException('Please set the default writer via the second argument of addWriter');
    }

    public function setDefaultWriter(string $name): void
    {
        $this->defaultWriter = $this->writers[$name];
    }

    public function getWriters(): array
    {
        return $this->writers;
    }

    private function assertValidWriter(string $name): void
    {
        if (!isset($this->writers[$name])) {
            throw new InvalidWriterException('Invalid writer "'.$name.'"');
        }
    }
}
