<?php

namespace MicrosoftStoreLib\Collections;

use Symfony\Component\Serializer\Annotation\SerializedName;

/// <summary>
/// JSON response from a successful consume request
/// </summary>
class CollectionsConsumeResponse
{
    /// <summary>
    /// ID that identifies this collection item from other items that the user owns. This ID is unique per product.
    /// </summary>
    /**
     * @SerializedName("itemId")
     */
    public string $ItemId;

    /// <summary>
    /// The remaining balance of this consumable that the user owns.
    /// </summary>
    /**
     * @SerializedName("newQuantity")
     */
    public int $NewQuantity;

    /// <summary>
    /// The unique tracking ID that was provided in the request to track and validate that the fulfillment succeeded.
    /// </summary>
    /**
     * @SerializedName("trackingId")
     */
    public string $TrackingId;

    /// <summary>
    /// The ProductId / StoreId of the consumable that was fulfilled.
    /// </summary>
    /**
     * @SerializedName("productId")
     */
    public string $ProductId;
}
