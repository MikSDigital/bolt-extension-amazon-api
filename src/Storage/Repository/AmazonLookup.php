<?php

namespace Bolt\Extension\Bolt\AmazonApi\Storage\Repository;

use Bolt\Extension\Bolt\AmazonApi\Storage\Entity;
use Bolt\Storage\Repository;

/**
 * Amazon lookup repository.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class AmazonLookup extends Repository
{
    /**
     * Check database for a pre-stored ASIN
     *
     * @param string $asin An Amazon ASIN
     *
     * @return Entity\AmazonLookup
     */
    public function doLookupASIN($asin)
    {
        $query = $this->doLookupASINQuery($asin);
        
        return $this->findOneWith($query);
    }
    
    public function doLookupASINQuery($asin)
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->where('asin = :asin')
            ->setParameter('asin', $asin)
        ;
    }

    /**
     * Return all ASIN records
     *
     * @return Entity\AmazonLookup[]
     */
    public function doLookupASINs()
    {
        return $this->findAll();
    }
}
