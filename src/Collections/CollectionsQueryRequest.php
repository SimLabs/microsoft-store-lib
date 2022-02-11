<?php

namespace MicrosoftStoreLib\Collections;

use Symfony\Component\Serializer\Annotation\SerializedName;

/// <summary>
/// JSON request body for a query to the Collections service
/// </summary>
class CollectionsQueryRequest
{
    /// <summary>
    /// The maximum number of products to return in one response. The default and maximum value is 100.
    /// </summary>
    /**
     * @SerializedName("maxPageSize")
     */
    public int $MaxPageSize;

    /// <summary>
    /// Removes duplicate entitlements where the user might be entitled to a single product from multiple sources.
    /// </summary>
    /**
     * @SerializedName("excludeDuplicates")
     */
    public bool $ExcludeDuplicates;

    /// <summary>
    /// Specifies which product types to return in the query results. For a list of valid values, see EntitlementFilterTypes.]
    /// </summary>
    /**
     * @SerializedName("entitlementFilters")
     */
    public $EntitlementFilters;

    /// <summary>
    /// If specified, the service only returns products applicable to the provided product/SKU pairs. Recommended for all service-to-service queries for faster and more reliable results.
    /// </summary>
    /**
     * @SerializedName("productSkuIds")
     */
    public array $ProductSkuIds = [];

    /// <summary>
    /// The country/region/market that you want to check the entitlement for. Using “neutral” (recommended) searches all markets. Otherwise, use the two-character ISO 3166 country/region code, for example, US.
    /// </summary>
    /**
     * @SerializedName("market")
     */
    public string $Market;

    /// <summary>
    /// Include items that are entitled through bundles or subscriptions in the results. If set to false, the results only contain the items the user has purchased, such as the parent bundle’s product information. If you’re using this parameter, always specify which products you want results for to avoid long or timed-out requests.
    /// </summary>
    /**
     * @SerializedName("expandSatisfyingItems")
     */
    public bool $ExpandSatisfyingItems;

    /// <summary>
    /// The user for which this item is entitled. You will use the UserCollectionsId in this property to define the user you want the results for.
    /// </summary>
    /**
     * @SerializedName("beneficiaries")
     */
    public $Beneficiaries;

    /// <summary>
    /// Filter the results to this validity type which is based off the Status values of the items to be returned.
    /// </summary>
    /**
     * @SerializedName("validityType")
     */
    public string $ValidityType;

    /// <summary>
    /// Specifies which development sandbox the results should be scoped to.  If no sandbox is specified the results will always be to the sandbox RETAIL.
    /// </summary>
    /**
     * @SerializedName("sbx")
     */
    public string $SandboxId;

    public function __construct()
    {
        //  default values most commonly used
        $this->Market = "neutral";
        $this->ExpandSatisfyingItems = true; //  This expands the results to include any products that
        //  are included in a bundle the user owns.
        $this->ExcludeDuplicates = true;     //  Only include one result (entitlement) per item.

        $this->MaxPageSize = 100;            //  Default Max is 100

        //  Default Game related product types
        //  Filter our results to include these product types
        $this->EntitlementFilters = [
            EntitlementFilterTypes::Game,
            EntitlementFilterTypes::Consumable,
            EntitlementFilterTypes::UnmanagedConsumable,
            EntitlementFilterTypes::Durable,
            EntitlementFilterTypes::Subscription
        ];

        $this->Beneficiaries = [];

        $this->SandboxId = "";
    }
}

/// <summary>
/// JSON structure to denote the ProductId and the Sku
/// </summary>
class ProductSkuId
{
    /// <summary>
    /// ProductId (StoreId) of the item offered within the store.
    /// </summary>
    /**
     * @SerializedName("productId")
     */
    public string $ProductId;

    /// <summary>
    /// SkuId representing the specific sub-offering of the product that was purchased.  For query requests, this value can be blank.
    /// </summary>
    /**
     * @SerializedName("skuId")
     */
    public string $SkuId;
}

/// <summary>
/// String values that can be added to a query request to filter the results to these specific
/// product types.
/// </summary>
class EntitlementFilterTypes
{
    /// <summary>
    /// Games products.
    /// </summary>
    const Game                = "*:Game";

    /// <summary>
    /// Apps that are not games.
    /// </summary>
    const Application         = "*:Application";

    /// <summary>
    /// Game content such as DLC and most single-time purchase items
    /// </summary>
    const Durable             = "*:Durable";

    /// <summary>
    /// Store-managed consumable products (recommended consumable type for most scenarios).
    /// </summary>
    const Consumable          = "*:Consumable";

    /// <summary>
    /// Developer-managed consumables that must be fulfilled before being able to be purchased by the user again.
    /// </summary>
    const UnmanagedConsumable = "*:UnmanagedConsumable";

    /// <summary>
    /// Store-managed subscriptions. Ex: Xbox Game Pass, Publisher specific subscription.  This product type is not
    /// the Add-on Subscription type that can be configured in the Add-ons page in Partner Center.
    /// </summary>
    const Subscription        = "*:Pass";
}

/// <summary>
/// Filter options for Collections query requests.  Results will be based on specific Status values.
/// </summary>
class ValidityTpes
{
    /// <summary>
    /// Everything will be returned.
    /// </summary>
    const All           = "All";

    /// <summary>
    /// Items that have a state of Active
    /// </summary>
    const Valid         = "Valid";

    /// <summary>
    /// Items that are Expired, Revoked, or in other non-valid states.
    /// </summary>
    const Invalid       = "Invalid";

    /// <summary>
    /// Items that are active but not yet reached their end date
    /// </summary>
    const NotYetEnded   = "NotYetEnded";

    /// <summary>
    /// Items that have not yet been activated such as pre-orders
    /// </summary>
    const NotYetStarted = "NotYetStarted";
}
