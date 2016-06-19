<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item;

use Bolt\Extension\Bolt\AmazonApi\Item\Component\Dimensions;
use Bolt\Extension\Bolt\AmazonApi\Item\Component\Language;

class Book extends AbstractItem
{
    protected $author;
    protected $catalogNumberList;
    protected $ean;
    protected $eanList;
    protected $isbn;
    protected $itemDimensions;
    protected $languages;
    protected $mpn;
    protected $numberOfItems;
    protected $numberOfPages;
    protected $partNumber;
    protected $publicationDate;
    protected $releaseDate;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->author = $data['itemattributes']['author'];
        $this->catalogNumberList = $data['itemattributes']['catalognumberlist']['catalognumberlistelement'];
        $this->ean = $data['itemattributes']['ean'];
        $this->eanList = $data['itemattributes']['eanlist']['eanlistelement'];
        $this->isbn = $data['itemattributes']['isbn'];
        $this->itemDimensions = new Dimensions($data['itemattributes']['itemdimensions']);
        $this->languages = new Language($data['itemattributes']['languages']['language']);
        $this->mpn = $data['itemattributes']['mpn'];
        $this->numberOfItems = $data['itemattributes']['numberofitems'];
        $this->numberOfPages = $data['itemattributes']['numberofpages'];
        $this->partNumber = $data['itemattributes']['partnumber'];
        $this->publicationDate = $data['itemattributes']['publicationdate'];
        $this->releaseDate = $data['itemattributes']['releasedate'];
    }
}
