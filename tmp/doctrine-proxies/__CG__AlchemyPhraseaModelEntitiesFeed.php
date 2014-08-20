<?php

namespace Alchemy\Phrasea\Model\Proxies\__CG__\Alchemy\Phrasea\Model\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Feed extends \Alchemy\Phrasea\Model\Entities\Feed implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'id', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'public', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'iconUrl', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'baseId', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'title', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'subtitle', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'createdOn', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'updatedOn', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'publishers', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'entries', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'tokens');
        }

        return array('__isInitialized__', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'id', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'public', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'iconUrl', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'baseId', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'title', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'subtitle', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'createdOn', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'updatedOn', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'publishers', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'entries', '' . "\0" . 'Alchemy\\Phrasea\\Model\\Entities\\Feed' . "\0" . 'tokens');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Feed $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsPublic($public)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsPublic', array($public));

        return parent::setIsPublic($public);
    }

    /**
     * {@inheritDoc}
     */
    public function isPublic()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isPublic', array());

        return parent::isPublic();
    }

    /**
     * {@inheritDoc}
     */
    public function setIconUrl($iconUrl)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIconUrl', array($iconUrl));

        return parent::setIconUrl($iconUrl);
    }

    /**
     * {@inheritDoc}
     */
    public function getIconUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIconUrl', array());

        return parent::getIconUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function setBaseId($baseId)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBaseId', array($baseId));

        return parent::setBaseId($baseId);
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBaseId', array());

        return parent::getBaseId();
    }

    /**
     * {@inheritDoc}
     */
    public function setTitle($title)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTitle', array($title));

        return parent::setTitle($title);
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTitle', array());

        return parent::getTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function addPublisher(\Alchemy\Phrasea\Model\Entities\FeedPublisher $publishers)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addPublisher', array($publishers));

        return parent::addPublisher($publishers);
    }

    /**
     * {@inheritDoc}
     */
    public function removePublisher(\Alchemy\Phrasea\Model\Entities\FeedPublisher $publishers)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePublisher', array($publishers));

        return parent::removePublisher($publishers);
    }

    /**
     * {@inheritDoc}
     */
    public function getPublishers()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPublishers', array());

        return parent::getPublishers();
    }

    /**
     * {@inheritDoc}
     */
    public function addEntry(\Alchemy\Phrasea\Model\Entities\FeedEntry $entries)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addEntry', array($entries));

        return parent::addEntry($entries);
    }

    /**
     * {@inheritDoc}
     */
    public function removeEntry(\Alchemy\Phrasea\Model\Entities\FeedEntry $entries)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeEntry', array($entries));

        return parent::removeEntry($entries);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntries($offset_start = 0, $how_many = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEntries', array($offset_start, $how_many));

        return parent::getEntries($offset_start, $how_many);
    }

    /**
     * {@inheritDoc}
     */
    public function getOwner()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOwner', array());

        return parent::getOwner();
    }

    /**
     * {@inheritDoc}
     */
    public function isOwner(\Alchemy\Phrasea\Model\Entities\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isOwner', array($user));

        return parent::isOwner($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection(\Alchemy\Phrasea\Application $app)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCollection', array($app));

        return parent::getCollection($app);
    }

    /**
     * {@inheritDoc}
     */
    public function setCollection(\collection $collection = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCollection', array($collection));

        return parent::setCollection($collection);
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedOn($createdOn)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreatedOn', array($createdOn));

        return parent::setCreatedOn($createdOn);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedOn()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedOn', array());

        return parent::getCreatedOn();
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdatedOn($updatedOn)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUpdatedOn', array($updatedOn));

        return parent::setUpdatedOn($updatedOn);
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedOn()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdatedOn', array());

        return parent::getUpdatedOn();
    }

    /**
     * {@inheritDoc}
     */
    public function isPublisher(\Alchemy\Phrasea\Model\Entities\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isPublisher', array($user));

        return parent::isPublisher($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getPublisher(\Alchemy\Phrasea\Model\Entities\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPublisher', array($user));

        return parent::getPublisher($user);
    }

    /**
     * {@inheritDoc}
     */
    public function setSubtitle($subtitle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubtitle', array($subtitle));

        return parent::setSubtitle($subtitle);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubtitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubtitle', array());

        return parent::getSubtitle();
    }

    /**
     * {@inheritDoc}
     */
    public function isAggregated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isAggregated', array());

        return parent::isAggregated();
    }

    /**
     * {@inheritDoc}
     */
    public function getCountTotalEntries()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCountTotalEntries', array());

        return parent::getCountTotalEntries();
    }

    /**
     * {@inheritDoc}
     */
    public function hasAccess(\Alchemy\Phrasea\Model\Entities\User $user, \Alchemy\Phrasea\Application $app)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasAccess', array($user, $app));

        return parent::hasAccess($user, $app);
    }

    /**
     * {@inheritDoc}
     */
    public function addToken(\Alchemy\Phrasea\Model\Entities\FeedToken $tokens)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addToken', array($tokens));

        return parent::addToken($tokens);
    }

    /**
     * {@inheritDoc}
     */
    public function removeToken(\Alchemy\Phrasea\Model\Entities\FeedToken $tokens)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeToken', array($tokens));

        return parent::removeToken($tokens);
    }

    /**
     * {@inheritDoc}
     */
    public function getTokens()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTokens', array());

        return parent::getTokens();
    }

    /**
     * {@inheritDoc}
     */
    public function addEntrie(\Alchemy\Phrasea\Model\Entities\FeedEntry $entries)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addEntrie', array($entries));

        return parent::addEntrie($entries);
    }

    /**
     * {@inheritDoc}
     */
    public function removeEntrie(\Alchemy\Phrasea\Model\Entities\FeedEntry $entries)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeEntrie', array($entries));

        return parent::removeEntrie($entries);
    }

    /**
     * {@inheritDoc}
     */
    public function hasPage($pageNumber, $nbEntriesByPage)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasPage', array($pageNumber, $nbEntriesByPage));

        return parent::hasPage($pageNumber, $nbEntriesByPage);
    }

    /**
     * {@inheritDoc}
     */
    public function isAccessible(\Alchemy\Phrasea\Model\Entities\User $user, \Alchemy\Phrasea\Application $app)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isAccessible', array($user, $app));

        return parent::isAccessible($user, $app);
    }

}
