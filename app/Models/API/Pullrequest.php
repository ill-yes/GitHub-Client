<?php

namespace App\Models\API;

class Pullrequest
{
    /** @var string $title  */
    public $title;

    /** @var string $prLink */
    public $prLink;

    /** @var string $branchName */
    public $branchName;

    /** @var string $branchCommitSha */
    public $branchCommitSha;

    /** @var string $baseLabel */
    public $baseLabel;

    /** @var \DateTime $createdAt */
    public $createdAt;  // todo

    /** @var \DateTime $updatedAt */
    public $updatedAt;

    /** @var \DateTime $mergedAt */
    public $mergedAt;

    /** @var string $mergeCommitSha */
    public $mergeCommitSha;

    /** @var string $creator */
    public $creator;

    /** @var string $creatorUrl */
    public $creatorUrl;
}
