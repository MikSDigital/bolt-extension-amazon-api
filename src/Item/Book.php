<?php

namespace Bolt\Extension\Bolt\AmazonApi\Item;

use Bolt\Extension\Bolt\AmazonApi\Item\Component\BookVersion;
use Bolt\Extension\Bolt\AmazonApi\Item\Component\Dimensions;
use Bolt\Extension\Bolt\AmazonApi\Item\Component\Language;

class Book extends AbstractItem
{
    /** @var string */
    protected $author;
    /** @var string */
    protected $catalogNumberList;
    /** @var string */
    protected $ean;
    /** @var string */
    protected $eanList;
    /** @var string */
    protected $isbn;
    /** @var Dimensions */
    protected $itemDimensions;
    /** @var Language */
    protected $languages;
    /** @var string */
    protected $mpn;
    /** @var int */
    protected $numberOfItems;
    /** @var int */
    protected $numberOfPages;
    /** @var string */
    protected $partNumber;
    /** @var string */
    protected $publicationDate;
    /** @var string */
    protected $releaseDate;
    /** @var BookVersion */
    protected $alternateVersions;

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
        $this->alternateVersions = new BookVersion($data['alternateversions']['alternateversion']);
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getCatalogNumberList()
    {
        return $this->catalogNumberList;
    }

    /**
     * @param string $catalogNumberList
     *
     * @return Book
     */
    public function setCatalogNumberList($catalogNumberList)
    {
        $this->catalogNumberList = $catalogNumberList;

        return $this;
    }

    /**
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * @param string $ean
     *
     * @return Book
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * @return string
     */
    public function getEanList()
    {
        return $this->eanList;
    }

    /**
     * @param string $eanList
     *
     * @return Book
     */
    public function setEanList($eanList)
    {
        $this->eanList = $eanList;

        return $this;
    }

    /**
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     *
     * @return Book
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return Dimensions
     */
    public function getItemDimensions()
    {
        return $this->itemDimensions;
    }

    /**
     * @param Dimensions $itemDimensions
     *
     * @return Book
     */
    public function setItemDimensions($itemDimensions)
    {
        $this->itemDimensions = $itemDimensions;

        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param Language $languages
     *
     * @return Book
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * @return string
     */
    public function getMpn()
    {
        return $this->mpn;
    }

    /**
     * @param string $mpn
     *
     * @return Book
     */
    public function setMpn($mpn)
    {
        $this->mpn = $mpn;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfItems()
    {
        return $this->numberOfItems;
    }

    /**
     * @param int $numberOfItems
     *
     * @return Book
     */
    public function setNumberOfItems($numberOfItems)
    {
        $this->numberOfItems = $numberOfItems;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }

    /**
     * @param int $numberOfPages
     *
     * @return Book
     */
    public function setNumberOfPages($numberOfPages)
    {
        $this->numberOfPages = $numberOfPages;

        return $this;
    }

    /**
     * @return string
     */
    public function getPartNumber()
    {
        return $this->partNumber;
    }

    /**
     * @param string $partNumber
     *
     * @return Book
     */
    public function setPartNumber($partNumber)
    {
        $this->partNumber = $partNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param string $publicationDate
     *
     * @return Book
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @param string $releaseDate
     *
     * @return Book
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return BookVersion
     */
    public function getAlternateVersions()
    {
        return $this->alternateVersions;
    }

    /**
     * @param BookVersion $alternateVersions
     *
     * @return Book
     */
    public function setAlternateVersions($alternateVersions)
    {
        $this->alternateVersions = $alternateVersions;

        return $this;
    }
}
