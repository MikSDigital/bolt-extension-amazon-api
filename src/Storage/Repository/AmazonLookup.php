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
     * {@inheritdoc}
     */
    public function save($entity, $silent = null)
    {
        $existing = $this->findOneBy(['asin' => $entity->getAsin()]);

        if ($existing) {
            $response = $this->update($entity);
        } else {
            $response = $this->insert($entity);
        }

        return $response;
    }

    /**
     * Check database for a pre-stored ASIN
     *
     * @param string $asin An Amazon ASIN
     *
     * @return Entity\AmazonLookup
     */
    public function getLookupByASIN($asin)
    {
        $query = $this->getLookupByASINQuery($asin);

        return $this->findOneWith($query);
    }

    public function getLookupByASINQuery($asin)
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
    public function getLookups()
    {
        return $this->findAll();
    }
}
