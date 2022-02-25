<?php

namespace MicrosoftStoreLib\Submissions;

class ApplicationData
{
    public string $id;
    public string $resourceLocation;
}

class ApplicationsInfo
{
    public $value = [];
    public int $totalCount = 0;

    public function addValue(ApplicationData $item)
    {
        $this->value[] = $item;
        $this->totalCount = count($this->value);
    }

    public function removeValue(ApplicationData $item)
    {
        foreach ($this->value as $i => $app)
        {
            if($item->id === $app->id)
            {
                unset($this->value[$i]);
                break;
            }
        }
        $this->totalCount = count($this->value);
    }

    public function hasValue()
    {
        return 0 !== count($this->value);
    }
}

class SubmissionInfo
{
    public string $id = '';
    public string $resourceLocation = '';
}

class AddOnResource
{
    public ApplicationsInfo $applications;
    public string $id;
    public string $productId;
    public string $productType;
    public ?SubmissionInfo $pendingInAppProductSubmission = null;
    public ?SubmissionInfo $lastPublishedInAppProductSubmission = null;
}
