<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface GetPendingPositionsRequestContract
{
    /**
     * Get pending positions
     *
     * @param  string|null  $symbol  Trading pair (optional)
     * @param  string|null  $positionId  Position ID (optional)
     */
    public function getPendingPositions(?string $symbol = null, ?string $positionId = null): ResponseInterface;
}
