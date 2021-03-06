<?php

/**
 * \AppserverIo\Appserver\Core\GenericDeployment
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

namespace AppserverIo\Appserver\Core;

/**
 * Generic deployment implementation for web applications.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/appserver
 * @link      http://www.appserver.io
 */
class GenericDeployment extends AbstractDeployment
{

    /**
     * Initializes the available applications and adds them to the container.
     *
     * @return void
     * @see \AppserverIo\Psr\Deployment\DeploymentInterface::deploy()
     */
    public function deploy()
    {
        $this->deployDatasources();
        $this->deployApplications();
    }

    /**
     * Deploys the available datasources.
     *
     * @return void
     */
    protected function deployDatasources()
    {

        // check if deploy dir exists
        if (is_dir($directory = $this->getDeploymentService()->getWebappsDir())) {
            // load the datasource files
            $datasourceFiles = $this->getDeploymentService()->globDir($directory . DIRECTORY_SEPARATOR . '*-ds.xml');
            // iterate through all provisioning files (provision.xml), validate them and attach them to the configuration
            $configurationService = $this->getConfigurationService();
            foreach ($datasourceFiles as $datasourceFile) {
                // validate the file, but skip it if validation fails
                if ($configurationService->validateFile($datasourceFile) === false) {
                    $errorMessages = $configurationService->getErrorMessages();
                    $systemLogger = $this->getInitialContext()->getSystemLogger();
                    $systemLogger->error(reset($errorMessages));
                    $systemLogger->critical(sprintf('Will skip reading configuration in %s, datasources might be missing.', $datasourceFile));
                    continue;
                }

                // load the database configuration
                $datasourceNodes = $this->getDatasourceService()->initFromFile($datasourceFile);

                // store the datasource in the system configuration
                foreach ($datasourceNodes as $datasourceNode) {
                    $this->getDatasourceService()->persist($datasourceNode);
                }
            }
        }
    }

    /**
     * Deploys the available applications.
     *
     * @return void
     */
    protected function deployApplications()
    {

        // load the container and initial context instance
        $container = $this->getContainer();

        // load the context instances for this container
        $contextInstances = $this->getDeploymentService()->loadContextInstancesByContainer($container);

        // gather all the deployed web applications
        foreach ($contextInstances as $context) {
            // try to load the application factory
            if ($applicationFactory = $context->getFactory()) {
                // use the factory if available
                $applicationFactory::visit($container, $context);
            } else {
                // if not, try to instantiate the application directly
                $applicationType = $context->getType();
                $container->addApplication(new $applicationType($context));
            }
        }
    }
}
