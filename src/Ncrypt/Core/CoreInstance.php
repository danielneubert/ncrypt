<?php

namespace Neubert\Ncrypt\Core;

use Neubert\Ncrypt\NcryptService;

class CoreInstance
{
    /**
     * The parent Ncrypt service.
     *
     * @var \Neubert\Ncrypt\NcryptService
     */
    private $ncryptService;

    /**
     * Create an core instance.
     *
     * @param NcryptService $ncrytp
     */
    public function __construct(NcryptService $ncrytp)
    {
        $this->ncryptService = $ncrytp;
    }

    /**
     * Return the Ncrypt service.
     *
     * @param NcryptService $ncrytp
     */
    protected function ncrypt(): NcryptService
    {
        return $this->ncryptService;
    }
}
