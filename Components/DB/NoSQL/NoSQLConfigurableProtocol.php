<?php

namespace Smartbox\Integration\FrameworkBundle\Components\DB\NoSQL;

use Smartbox\Integration\FrameworkBundle\Configurability\DescriptableInterface;
use Smartbox\Integration\FrameworkBundle\Core\Protocols\Protocol;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NoSQLConfigurableProtocol
 */
class NoSQLConfigurableProtocol extends Protocol implements DescriptableInterface
{
    const OPTION_NOSQL_DRIVER       = 'nosql_driver';
    const OPTION_COLLECTION_PREFIX  = 'prefix';
    const OPTION_COLLECTION_NAME    = 'collection';
    const OPTION_METHOD = 'method';

    /**
     * Get static default options.
     *
     * @return array Array with option name, description, and options (optional)
     */
    public function getOptionsDescriptions()
    {
        return array_merge(parent::getOptionsDescriptions(), [
            self::OPTION_NOSQL_DRIVER => ['The driver service to use to connect to the MongoDb instance', []],
            self::OPTION_COLLECTION_PREFIX => ['A string prefix used for collection names', []],
            self::OPTION_COLLECTION_NAME => ['The name of the collection in which the messages will be stored', []],
            self::OPTION_METHOD => ['Method to be executed in the consumer/producer', []],
        ]);
    }

    /**
     * With this method this class can configure an OptionsResolver that will be used to validate the options.
     *
     * @param OptionsResolver $resolver
     *
     * @return mixed
     */
    public function configureOptionsResolver(OptionsResolver $resolver)
    {
        parent::configureOptionsResolver($resolver);
        $resolver->setRequired([
            self::OPTION_NOSQL_DRIVER,
            self::OPTION_COLLECTION_PREFIX,
            self::OPTION_COLLECTION_NAME,
            self::OPTION_METHOD
        ]);

        $resolver->setAllowedTypes(self::OPTION_COLLECTION_NAME, ['string']);
        $resolver->setAllowedTypes(self::OPTION_COLLECTION_PREFIX, ['string']);
        $resolver->setAllowedTypes(self::OPTION_NOSQL_DRIVER, ['string']);
        $resolver->setAllowedTypes(self::OPTION_METHOD, ['string']);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Specialized protocol to interact with noSQL databases (MongoDB only for now)';
    }
}
