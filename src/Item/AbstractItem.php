<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item;

abstract class AbstractItem
{
    /** @var string */
    protected $asin;
    /** @var string */
    protected $detailPageUrl;
    /** @var string */
    protected $salesRank;
    /** @var Component\Image */
    protected $swatchImage;
    /** @var Component\Image */
    protected $tinyImage;
    /** @var Component\Image */
    protected $smallImage;
    /** @var Component\Image */
    protected $mediumImage;
    /** @var Component\Image */
    protected $largeImage;
    /** @var Component\Image */
    protected $hiResImage;

    /** @var string */
    protected $binding;
    /** @var string */
    protected $brand;
    /** @var string */
    protected $feature;
    /** @var string */
    protected $label;
    /** @var Component\Price */
    protected $listPrice;
    /** @var string */
    protected $manufacturer;
    /** @var Component\Dimensions */
    protected $packageDimensions;
    /** @var int */
    protected $packageQuantity;
    /** @var string */
    protected $productGroup;
    /** @var string */
    protected $productTypeName;
    /** @var string */
    protected $publisher;
    /** @var string */
    protected $studio;
    /** @var string */
    protected $title;

    /** @var Component\OfferSummary */
    protected $offerSummary;
    /** @var Component\EditorialReview */
    protected $editorialReview;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->asin = $data['asin'];
        $this->detailPageUrl = $data['detailpageurl'];
        $this->salesRank = $data['salesrank'];

        // Image sets
        $this->swatchImage = $this->getImageSetComponent($data, 'swatchimage');
        $this->tinyImage = $this->getImageSetComponent($data, 'tinyimage');
        $this->smallImage = $this->getImageSetComponent($data, 'smallimage');
        $this->mediumImage = $this->getImageSetComponent($data, 'mediumimage');
        $this->largeImage = $this->getImageSetComponent($data, 'largeimage');
        $this->hiResImage = $this->getImageSetComponent($data, 'hiresimage');

        // Item attributes
        $this->binding = $this->getItemAttributeComponent($data, 'binding');
        $this->brand = $this->getItemAttributeComponent($data, 'brand');
        $this->feature = $this->getItemAttributeComponent($data, 'feature');
        $this->label = $this->getItemAttributeComponent($data, 'label');
        $this->listPrice = $this->getItemAttributeComponent($data, 'listprice');
        $this->manufacturer = $this->getItemAttributeComponent($data, 'manufacturer');
        $this->packageDimensions = $this->getItemAttributeComponent($data, 'packagedimensions');
        $this->packageQuantity = $this->getItemAttributeComponent($data, 'packagequantity');
        $this->productGroup = $this->getItemAttributeComponent($data, 'productgroup');
        $this->productTypeName = $this->getItemAttributeComponent($data, 'producttypename');
        $this->publisher = $this->getItemAttributeComponent($data, 'publisher');
        $this->studio = $this->getItemAttributeComponent($data, 'studio');
        $this->title = $this->getItemAttributeComponent($data, 'title');

        // Other
        $this->offerSummary = new Component\OfferSummary($data['offersummary']);
        $this->editorialReview = new Component\EditorialReview($data['editorialreviews']['editorialreview']);
    }

    /**
     * @return string
     */
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * @param string $asin
     *
     * @return AbstractItem
     */
    public function setAsin($asin)
    {
        $this->asin = $asin;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetailPageUrl()
    {
        return $this->detailPageUrl;
    }

    /**
     * @param string $detailPageUrl
     *
     * @return AbstractItem
     */
    public function setDetailPageUrl($detailPageUrl)
    {
        $this->detailPageUrl = $detailPageUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalesRank()
    {
        return $this->salesRank;
    }

    /**
     * @param string $salesRank
     *
     * @return AbstractItem
     */
    public function setSalesRank($salesRank)
    {
        $this->salesRank = $salesRank;

        return $this;
    }

    /**
     * @return Component\Image
     */
    public function getSwatchImage()
    {
        return $this->swatchImage;
    }

    /**
     * @param Component\Image $swatchImage
     *
     * @return AbstractItem
     */
    public function setSwatchImage($swatchImage)
    {
        $this->swatchImage = $swatchImage;

        return $this;
    }

    /**
     * @return Component\Image
     */
    public function getTinyImage()
    {
        return $this->tinyImage;
    }

    /**
     * @param Component\Image $tinyImage
     *
     * @return AbstractItem
     */
    public function setTinyImage($tinyImage)
    {
        $this->tinyImage = $tinyImage;

        return $this;
    }

    /**
     * @return Component\Image
     */
    public function getSmallImage()
    {
        return $this->smallImage;
    }

    /**
     * @param Component\Image $smallImage
     *
     * @return AbstractItem
     */
    public function setSmallImage($smallImage)
    {
        $this->smallImage = $smallImage;

        return $this;
    }

    /**
     * @return Component\Image
     */
    public function getMediumImage()
    {
        return $this->mediumImage;
    }

    /**
     * @param Component\Image $mediumImage
     *
     * @return AbstractItem
     */
    public function setMediumImage($mediumImage)
    {
        $this->mediumImage = $mediumImage;

        return $this;
    }

    /**
     * @return Component\Image
     */
    public function getLargeImage()
    {
        return $this->largeImage;
    }

    /**
     * @param Component\Image $largeImage
     *
     * @return AbstractItem
     */
    public function setLargeImage($largeImage)
    {
        $this->largeImage = $largeImage;

        return $this;
    }

    /**
     * @return Component\Image
     */
    public function getHiResImage()
    {
        return $this->hiResImage;
    }

    /**
     * @param Component\Image $hiResImage
     *
     * @return AbstractItem
     */
    public function setHiResImage($hiResImage)
    {
        $this->hiResImage = $hiResImage;

        return $this;
    }

    /**
     * @return string
     */
    public function getBinding()
    {
        return $this->binding;
    }

    /**
     * @param string $binding
     *
     * @return AbstractItem
     */
    public function setBinding($binding)
    {
        $this->binding = $binding;

        return $this;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     *
     * @return AbstractItem
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return string
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * @param string $feature
     *
     * @return AbstractItem
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return AbstractItem
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Component\Price
     */
    public function getListPrice()
    {
        return $this->listPrice;
    }

    /**
     * @param Component\Price $listPrice
     *
     * @return AbstractItem
     */
    public function setListPrice($listPrice)
    {
        $this->listPrice = $listPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param string $manufacturer
     *
     * @return AbstractItem
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * @return Component\Dimensions
     */
    public function getPackageDimensions()
    {
        return $this->packageDimensions;
    }

    /**
     * @param Component\Dimensions $packageDimensions
     *
     * @return AbstractItem
     */
    public function setPackageDimensions($packageDimensions)
    {
        $this->packageDimensions = $packageDimensions;

        return $this;
    }

    /**
     * @return int
     */
    public function getPackageQuantity()
    {
        return $this->packageQuantity;
    }

    /**
     * @param int $packageQuantity
     *
     * @return AbstractItem
     */
    public function setPackageQuantity($packageQuantity)
    {
        $this->packageQuantity = $packageQuantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductGroup()
    {
        return $this->productGroup;
    }

    /**
     * @param string $productGroup
     *
     * @return AbstractItem
     */
    public function setProductGroup($productGroup)
    {
        $this->productGroup = $productGroup;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductTypeName()
    {
        return $this->productTypeName;
    }

    /**
     * @param string $productTypeName
     *
     * @return AbstractItem
     */
    public function setProductTypeName($productTypeName)
    {
        $this->productTypeName = $productTypeName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param string $publisher
     *
     * @return AbstractItem
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return string
     */
    public function getStudio()
    {
        return $this->studio;
    }

    /**
     * @param string $studio
     *
     * @return AbstractItem
     */
    public function setStudio($studio)
    {
        $this->studio = $studio;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return AbstractItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Component\OfferSummary
     */
    public function getOfferSummary()
    {
        return $this->offerSummary;
    }

    /**
     * @param Component\OfferSummary $offerSummary
     *
     * @return AbstractItem
     */
    public function setOfferSummary($offerSummary)
    {
        $this->offerSummary = $offerSummary;

        return $this;
    }

    /**
     * @return Component\EditorialReview
     */
    public function getEditorialReview()
    {
        return $this->editorialReview;
    }

    /**
     * @param Component\EditorialReview $editorialReview
     *
     * @return AbstractItem
     */
    public function setEditorialReview($editorialReview)
    {
        $this->editorialReview = $editorialReview;

        return $this;
    }

    /**
     * @param array  $data
     * @param string $setName
     *
     * @return Component\Image|null
     */
    protected function getImageSetComponent(array $data, $setName)
    {
        if (!isset($data['imagesets']['imageset'][$setName])) {
            return null;
        }

        return new Component\Image($data['imagesets']['imageset'][$setName]);
    }

    /**
     * @param array  $data
     * @param string $attributeName
     *
     * @return Component\Dimensions|null
     */
    protected function getItemAttributeComponent(array $data, $attributeName)
    {
        if (!isset($data['itemattributes'][$attributeName])) {
            return null;
        }

        if ($attributeName === 'listprice') {
            return new Component\Price($data['itemattributes'][$attributeName]);
        }

        if ($attributeName === 'packagedimensions') {
            return new Component\Dimensions($data['itemattributes'][$attributeName]);
        }

        return $data['itemattributes'][$attributeName];
    }
}
