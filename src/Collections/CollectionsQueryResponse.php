<?php

namespace MicrosoftStoreLib\Collections;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CollectionsQueryResponse
{
    /// <summary>
    /// List of CollectionsItems returned from the request
    /// </summary>
    /**
     * @SerializedName("items")
     */
    /**
     * @var CollectionsItem[]
     */
    public $Items = [];

    public function addItem(CollectionsItem $item)
    {
        $this->Items[$item->TransactionId] = $item;
    }

    public function removeItem(CollectionsItem $item)
    {
        $this->Items[$item->TransactionId] = $item;
    }

    public function hasItems()
    {
        return 0 !== count($this->Items);
    }
}
