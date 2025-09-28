<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface FlashClosePositionRequestContract
{
    /**
     * Flash close position by position ID
     *
     * @param string $positionId Position ID
     * @return ResponseInterface
     */
    public function flashClosePosition(string $positionId): ResponseInterface;
}
