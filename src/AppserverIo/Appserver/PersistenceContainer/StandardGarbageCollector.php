<?php

/**
 * \AppserverIo\Appserver\PersistenceContainer\StandardGarbageCollector
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
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/appserver
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Appserver\PersistenceContainer;

use AppserverIo\Logger\LoggerUtils;
use AppserverIo\Psr\Application\ApplicationInterface;

/**
 * The garbage collector for the stateful session beans.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/appserver
 * @link      http://www.appserver.io
 */
class StandardGarbageCollector extends \Thread
{

    /**
     * The time we wait after each loop.
     *
     * @var integer
     */
    const TIME_TO_LIVE = 1;

    /**
     * Initializes the queue worker with the application and the storage it should work on.
     *
     * @param \AppserverIo\Psr\Application\ApplicationInterface $application The application instance with the queue manager/locator
     */
    public function __construct(ApplicationInterface $application)
    {

        // bind the gc to the application
        $this->application = $application;

        // start the worker
        $this->start(PTHREADS_INHERIT_NONE|PTHREADS_INHERIT_CONSTANTS);
    }

    /**
     * Returns the application instance.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * We process the messages here.
     *
     * @return void
     */
    public function run()
    {

        // register the default autoloader
        require SERVER_AUTOLOADER;

        // register shutdown handler
        register_shutdown_function(array(&$this, "shutdown"));

        // synchronize the application instance and register the class loaders
        $application = $this->getApplication();
        $application->registerClassLoaders();

        // try to load the profile logger
        if ($profileLogger = $application->getInitialContext()->getLogger(LoggerUtils::PROFILE)) {
            $profileLogger->appendThreadContext('persistence-container-garbage-collector');
        }

        while (true) {
            // wait one second
            $this->synchronized(function () {
                $this->wait(1000000 * StandardGarbageCollector::TIME_TO_LIVE);
            });

            // we need the bean manager that handles all the beans
            /** @var \AppserverIo\Psr\EnterpriseBeans\BeanContextInterface $beanManager */
            $beanManager = $application->search('BeanContextInterface');

            // load the map with the stateful session beans
            $statefulSessionBeans = $beanManager->getStatefulSessionBeans();

            // iterate over the applications sessions with stateful session beans
            foreach ($statefulSessionBeans as $sessionId => $sessions) {
                // query if we've a map with stateful session beans
                if ($sessions instanceof StatefulSessionBeanMap) {
                    // initialize the timestamp with the actual time
                    $actualTime = time();

                    // check the lifetime of the stateful session beans
                    foreach ($sessions->getLifetime() as $className => $lifetime) {
                        if ($lifetime < $actualTime) {
                            // if the stateful session bean has timed out, remove it
                            $beanManager->removeStatefulSessionBean($sessionId, $className);
                        }
                    }
                }
            }

            if ($profileLogger) {
                // profile the stateful session bean map size
                $profileLogger->debug(
                    sprintf('Processed standard garbage collector, handling %d SFSBs', sizeof($statefulSessionBeans))
                );
            }
        }
    }

    /**
     * Shutdown function to log unexpected errors.
     *
     * @return void
     * @see http://php.net/register_shutdown_function
     */
    public function shutdown()
    {

        // check if there was a fatal error caused shutdown
        if ($lastError = error_get_last()) {
            // initialize error type and message
            $type = 0;
            $message = '';
            // extract the last error values
            extract($lastError);
            // query whether we've a fatal/user error
            if ($type === E_ERROR || $type === E_USER_ERROR) {
                $this->getApplication()->getInitialContext()->getSystemLogger()->critical($message);
            }
        }
    }
}
