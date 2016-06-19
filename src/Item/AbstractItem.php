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
