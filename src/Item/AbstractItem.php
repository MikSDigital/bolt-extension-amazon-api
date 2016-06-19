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
        $this->swatchImage = new Component\Image($data['imagesets']['imageset']['swatchimage']);
        $this->tinyImage = new Component\Image($data['imagesets']['imageset']['tinyimage']);
        $this->smallImage = new Component\Image($data['imagesets']['imageset']['smallimage']);
        $this->mediumImage = new Component\Image($data['imagesets']['imageset']['mediumimage']);
        $this->largeImage = new Component\Image($data['imagesets']['imageset']['largeimage']);
        $this->hiResImage = new Component\Image($data['imagesets']['imageset']['hiresimage']);

        // Item attributes
        $this->binding = $data['itemattributes']['binding'];
        $this->brand = $data['itemattributes']['brand'];
        $this->feature = $data['itemattributes']['feature'];
        $this->label = $data['itemattributes']['label'];
        $this->listPrice = new Component\Price($data['itemattributes']['listprice']);
        $this->manufacturer = $data['itemattributes']['manufacturer'];
        $this->packageDimensions = new Component\Dimensions($data['itemattributes']['packagedimensions']);
        $this->packageQuantity = $data['itemattributes']['packagequantity'];
        $this->productGroup = $data['itemattributes']['productgroup'];
        $this->productTypeName = $data['itemattributes']['producttypename'];
        $this->publisher = $data['itemattributes']['publisher'];
        $this->studio = $data['itemattributes']['studio'];
        $this->title = $data['itemattributes']['title'];

        // Other
        $this->offerSummary = new Component\OfferSummary($data['offersummary']);
        $this->editorialReview = new Component\EditorialReview($data['editorialreviews']['editorialreview']);
    }
}
