<?php

namespace MicrosoftStoreLib\Submissions;

use Symfony\Component\Serializer\Annotation\SerializedName;

class AddOnItem
{
    public string $inAppProductId = '';
}

class AppAddOnsResponse
{
    /**
     * @SerializedName("@nextLink")
     */
    public ?string $nextLink = null;
    /**
     * @var AddOnItem[]
     */
    public array $value = [];
    public int $totalCount = 0;

    public function addValue(AddOnItem $item)
    {
        $this->value[] = $item;
    }

    public function removeValue(AddOnItem $item)
    {
        foreach ($this->value as $i => $app)
        {
            if($item->inAppProductId === $app->inAppProductId)
            {
                unset($this->value[$i]);
                break;
            }
        }
    }
}
