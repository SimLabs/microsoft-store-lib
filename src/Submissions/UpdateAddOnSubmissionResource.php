<?php

namespace MicrosoftStoreLib\Submissions;

use MicrosoftStoreLib\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class IconInfo
{
    public string $fileName;
    public string $fileStatus;
}

class MarketSpecificPricingsMap
{
    public ?string $RU;
    public ?string $US;
}

class SaleInfo
{
    public string $name;
    public string $basePriceId;
    public \DateTime $startDate;
    public \DateTime $endDate;
    public MarketSpecificPricingsMap $marketSpecificPricings;
}

class PricingInfo {
    public MarketSpecificPricingsMap $marketSpecificPricings;
    /**
     * @var SaleInfo[]
     */
    public array $sales = [];
    public string $priceId;
    public bool $isAdvancedPricingModel = true;

    public function addSale(SaleInfo $item)
    {
        $this->sales[] = $item;
    }

    public function removeSale(SaleInfo $item)
    {
        foreach ($this->sales as $i => $sale)
        {
            if($item->name === $sale->name)
            {
                unset($this->sales[$i]);
                break;
            }
        }
    }
}

class ListingsMap
{
    public ?ListingInfo $en;
    public ?ListingInfo $ru;
}

class UpdateAddOnSubmissionResource
{
    public function __construct()
    {
        $this->pricing = new PricingInfo();
    }

    public string $id;
    public string $contentType;
    /**
     * @var string[]
     */
    public array $keywords = [];
    public string $lifetime;
    public ListingsMap $listings;
    public PricingInfo $pricing;
    public \DateTime $targetPublishDate;
    public string $targetPublishMode;
    public ?string $tag = null;
    public string $visibility;

    public function __toString()
    {
        return Serializer::Get()->serialize($this, 'json', [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::PRESERVE_EMPTY_OBJECTS => true,
        ]);
    }
}
