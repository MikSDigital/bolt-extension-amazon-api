Amazon API for Bolt
====================

An extension to query Amazon's REST/SOAP API.

You will need SOAP support installed/enabled in your PHP setup.

### Using

```
$entity = $app['amazon.api']['lookup']->getItemByAsin('0345538374');
if ($entity) {
    $item = $entity->getItem();
    echo 
}
```
