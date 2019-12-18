<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace superextend\QrCode\Response;

use superextend\QrCode\QrCodeInterface;
use Symfony\Component\HttpFoundation\Response;

class QrCodeResponse extends Response
{
    public function __construct(QrCodeInterface $qrCode)
    {
        parent::__construct($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()]);
    }
}
