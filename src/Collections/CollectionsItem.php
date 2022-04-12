<?php

namespace MicrosoftStoreLib\Collections;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CollectionsItem
{
    /// <summary>
    /// The date on which the user acquired the item.
    /// </summary>
    /**
     * @SerializedName("acquiredDate")
     */
    public \DateTime $AcquiredDate;

    
    /// <summary>
    /// Indicates how the user has this entitlement.
    /// </summary>
    /**
     * @SerializedName("acquisitionType")
     */
    public string $AcquisitionType;

    /// <summary>
    /// The UTC date that the item will expire.
    /// </summary>
    /**
     * @SerializedName("endDate")
     */
    public \DateTime $EndDate;

    /// <summary>
    /// The developer-specified product ID string that is assigned to the item in Partner Center. A example product ID is product123.
    /// </summary>
    /**
     * @SerializedName("inAppOfferToken")
     */
    public string $InAppOfferToken;

    /// <summary>
    /// An ID that identifies this collection item from other items that the user owns. This ID is unique per product.
    /// </summary>
    /**
     * @SerializedName("id")
     */
    public string $Id;

    /// <summary>
    /// The offerInstanceId value that would have been provided if calling the Xbox Inventory Service. This shouldn’t be needed
    /// in most cases.
    /// </summary>
    /**
     * @SerializedName("legacyOfferInstanceId")
     */
    public string $LegacyOfferInstanceId;

    /// <summary>
    /// The older ProductID format from the Xbox Developer Portal and used by the Xbox Inventory Service. New products created
    /// in Partner Center don’t have these by default but can be enrolled to have this value if needed.
    /// </summary>
    /**
     * @SerializedName("legacyProductId")
     */
    public string $LegacyProductId;

    /// <summary>
    /// The ID of the previously supplied localTicketReference in the request body.
    /// </summary>
    /**
     * @SerializedName("localTicketReference")
     */
    public string $LocalTicketReference;

    /// <summary>
    /// The UTC date that this item was last modified. With consumable products, this value changes when the user’s quantity
    /// balance changes through an additional purchase of the consumable product or when a consume request is issued.
    /// </summary>
    /**
     * @SerializedName("modifiedDate")
     */
    public \DateTime $ModifiedDate;

    /// <summary>
    /// The two-character ISO 3166 country code indicating the region store the product was acquired from.
    /// </summary>
    /**
     * @SerializedName("purchasedCountry")
     */
    public string $PurchasedCountry;

    /// <summary>
    /// Indicates what type of product that this relates to. Usually, this is “Games” but can also be blank for
    /// game-related content.
    /// </summary>
    /**
     * @SerializedName("productFamily")
     */
    public string $ProductFamily;

    /// <summary>
    /// Also refereed to as the Store ID for the product within the Microsoft Store catalog. An example Store ID for
    /// a product is 9NBLGGH42CFD.
    /// </summary>
    /**
     * @SerializedName("productId")
     */
    public string $ProductId;

    /// <summary>
    /// Indicates the product type. For more information, see ProductKindTypes.
    /// </summary>
    /**
     * @SerializedName("productKind")
     */
    public string $ProductKind;

    /// <summary>
    /// Provides specific information to manage this product through the Recurrence services if this is a subscription.
    /// </summary>
    /**
     * @SerializedName("recurrenceData")
     */
    public RecurrenceData $RecurrenceData;

    /// <summary>
    /// If this product is entitled because of a bundle or subscription, the ProductIds of those parent products are provided here.
    /// </summary>
    /**
     * @SerializedName("satisfiedByProductIds")
     */
    public $SatisfiedByProductIds;

    /// <summary>
    /// Indicates if the item is entitled because of a sharing scenario. When calling Microsoft Store Services from your own service this should always return as None.
    /// </summary>
    /**
     * @SerializedName("sharingSource")
     */
    public string $SharingSource;

    /// <summary>
    /// The specific SKU identifier if there are multiple offerings of the product in the Microsoft Store catalog. An example Store ID for a SKU is 0010.
    /// </summary>
    /**
     * @SerializedName("skuId")
     */
    public string $SkuId;

    /// <summary>
    /// The UTC date that the item became or will become valid.
    /// </summary>
    /**
     * @SerializedName("startDate")
     */
    public \DateTime $StartDate;

    /// <summary>
    /// The status of the item.
    /// </summary>
    /**
     * @SerializedName("status")
     */
    public string $Status;

    /// <summary>
    /// Tags related to the product.
    /// </summary>
    /**
     * @SerializedName("tags")
     */
    public $Tags;

    /// <summary>
    /// Information about this product—if it’s a trial and the time remaining.
    /// </summary>
    /**
     * @SerializedName("trialData")
     */
    public TrialData $TrialData;

    /// <summary>
    /// The offer ID from an in-app purchase.
    /// </summary>
    /**
     * @SerializedName("devOfferId")
     */
    public string $DevOfferId;

    /// <summary>
    /// The quantity of the item. For non-consumable products, this is always 1. For consumable products, this represents the remaining balance that can be consumed or fulfilled for the user.
    /// </summary>
    /**
     * @SerializedName("quantity")
     */
    public int $Quantity;

    /// <summary>
    /// The transaction ID as a result of the purchase of this item. Can be used for reporting an item as fulfilled.
    /// </summary>
    /**
     * @SerializedName("transactionId")
     */
    public string $TransactionId;
}

/// <summary>
/// Subscription related information for the product.
/// </summary>
class RecurrenceData
{
    /// <summary>
    /// Unique Id to be used with the Recurrence services to manage this subscription.
    /// </summary>
    /**
     * @SerializedName("recurrenceId")
     */
    public string $RecurrenceId;
}

/// <summary>
/// Trial information related to the users entitlement to the product.
/// </summary>
class TrialData
{
    /// <summary>
    /// Indicates if this product is licensed through a trial.
    /// </summary>
    /**
     * @SerializedName("isInTrialPeriod")
     */
    public bool $IsInTrialPeriod;

    /// <summary>
    /// Indicates if the product is in a trial period, such as a subscription.
    /// </summary>
    /**
     * @SerializedName("isTrial")
     */
    public bool $IsTrial;

    /// <summary>
    /// Information about how long the trial remains valid.
    /// </summary>
    /**
     * @SerializedName("trialTimeRemaining")
     */
    public float $TrialTimeRemaining;
}

/// <summary>
/// The AcquisitionType values that are returned in calls to the Microsoft Store
/// Service and indicate how the product is entitled to the user.
/// </summary>
class AcquisitionTypes
{
    /// <summary>
    /// Owned or entitled through a subscription.
    /// </summary>
    const Recurring = "Recurring";

    /// <summary>
    /// Direct digital purchase or code redemption.
    /// </summary>
    const Single = "Single";

    /// <summary>
    /// Owned but requires other products to continue use.
    /// Ex: Games With Gold obtained games which expire if Gold subscription ends
    /// </summary>
    const Conditional = "Conditional";
}

/// <summary>
/// Values returned with a Collections item as the Status value
/// </summary>
class ProductStatusTypes
{
    /// <summary>
    /// The product is actively entitled. The user should have access to it.
    /// </summary>
    const Active = "Active";

    /// <summary>
    /// Most commonly indicates that the user requested a refund.
    /// </summary>
    const Revoked = "Revoked";

    /// <summary>
    /// The product was part of an entitlement (usually a subscription) that has since expired.
    /// </summary>
    const Expired = "Expired";

    /// <summary>
    /// No information on this type
    /// </summary>
    const Banned = "Banned";

    /// <summary>
    /// No information on this type
    /// </summary>
    const Suspended = "Suspended";
}

/// <summary>
/// String values that can be returned as the ProductKind in the Collections result.
/// </summary>
class ProductKindTypes
{
    /// <summary>
    /// Game application or a bundle that includes a game application
    /// </summary>
    const Game = "Game";

    /// <summary>
    /// Apps that are not games.
    /// </summary>
    const Application = "Application";

    /// <summary>
    /// Game content such as DLC and most single-time purchase items.
    /// </summary>
    const Durable = "Durable";

    /// <summary>
    /// Store-managed consumable product (recommended consumable type for most scenarios).
    /// </summary>
    const Consumable = "Consumable";

    /// <summary>
    /// Developer-managed consumable that must be fulfilled before being able to be purchased by the user again.
    /// </summary>
    const UnmanagedConsumable = "UnmanagedConsumable";
}
