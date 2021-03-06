<?php

/**
 * \AppserverIo\Appserver\Core\Api\Node\ServerNode
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @author    Johann Zelger <jz@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/appserver
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Appserver\Core\Api\Node;

/**
 * DTO to transfer server information.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @author    Johann Zelger <jz@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/appserver
 * @link      http://www.appserver.io
 */
class ServerNode extends AbstractNode implements ServerNodeInterface
{

    /**
     * The trait for the server environment variables.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\EnvironmentVariablesNodeTrait
     */
    use EnvironmentVariablesNodeTrait;

    /**
     * The trait for the server params.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\ParamsNodeTrait
     */
    use ParamsNodeTrait;

    /**
     * The trait for the server rewrite maps.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\RewriteMapsNodeTrait
     */
    use RewriteMapsNodeTrait;

    /**
     * The trait for the server rewrites.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\RewritesNodeTrait
     */
    use RewritesNodeTrait;

    /**
     * The trait for the server access.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\AccessesNodeTrait
     */
    use AccessesNodeTrait;

    /**
     * The trait for the server locations.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\LocationsNodeTrait
     */
    use LocationsNodeTrait;

    /**
     * The trait for the server authentications.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\AuthenticationsNodeTrait
     */
    use AuthenticationsNodeTrait;

    /**
     * The trait for the server analytics.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\AnalyticsNodeTrait
     */
    use AnalyticsNodeTrait;

    /**
     * The trait for the server file handlers.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\FileHandlersNodeTrait
     */
    use FileHandlersNodeTrait;

    /**
     * The trait for the server connection handlers.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\ConnectionHandlersNodeTrait
     */
    use ConnectionHandlersNodeTrait;

    /**
     * The trait for the server modules.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\ModulesNodeTrait
     */
    use ModulesNodeTrait;

    /**
     * The trait for the virtual hosts.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\VirtualHostsNodeTrait
     */
    use VirtualHostsNodeTrait;
    
    /**
     * The trait for the certificates.
     *
     * @var \AppserverIo\Appserver\Core\Api\Node\CertificatesNodeTrait
     */
    use CertificatesNodeTrait;

    /**
     * The servers type.
     *
     * @var string
     * @AS\Mapping(nodeType="string")
     */
    protected $type;

    /**
     * The servers name.
     *
     * @var string
     * @AS\Mapping(nodeType="string")
     */
    protected $name;

    /**
     * The worker to use.
     *
     * @var string
     * @AS\Mapping(nodeType="string")
     */
    protected $worker;

    /**
     * The socket to use.
     *
     * @var string
     * @AS\Mapping(nodeType="string")
     */
    protected $socket;

    /**
     * The loggers name to use.
     *
     * @var string
     * @AS\Mapping(nodeType="string")
     */
    protected $loggerName;

    /**
     * The server context to use.
     *
     * @var string
     * @AS\Mapping(nodeType="string")
     */
    protected $serverContext;

    /**
     * The request context to use.
     *
     * @var string
     * @AS\Mapping(nodeType="string")
     */
    protected $requestContext;
    
    /**
     * The stream context to use.
     *
     * @var string
     * @AS\Mapping(nodeType="string")
     */
    protected $streamContext;

    /**
     * Returns the servers type.
     *
     * @return string The servers type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the server name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the worker to use for server.
     *
     * @return string The worker type to use for server
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Returns the socket to use.
     *
     * @return string The socket type
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * Returns the loggers name to use.
     *
     * @return string The loggers name
     */
    public function getLoggerName()
    {
        return $this->loggerName;
    }

    /**
     * Returns the server context to use.
     *
     * @return string The server context type
     */
    public function getServerContext()
    {
        return $this->serverContext;
    }

    /**
     * Returns the request context to use.
     *
     * @return string The request context type
     */
    public function getRequestContext()
    {
        return $this->requestContext;
    }
    
    /**
     * Returns the stream context to use.
     *
     * @return string The stream context type
     */
    public function getStreamContext()
    {
        return $this->streamContext;
    }
}
