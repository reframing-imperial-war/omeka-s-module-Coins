<?php

namespace CoinsTest\Controller;

use Omeka\Test\AbstractHttpControllerTestCase;

abstract class CoinsControllerTestCase extends AbstractHttpControllerTestCase
{
    protected $items;
    protected $site;

    public function setUp()
    {
        parent::setUp();

        $this->loginAsAdmin();

        $response = $this->api()->create('sites', [
            'o:title' => 'Test site',
            'o:slug' => 'test',
            'o:theme' => 'default',
        ]);
        $this->site = $response->getContent();

        for ($i = 0; $i < 10; $i++) {
            $response = $this->api()->create('items', [
                'dcterms:title' => [
                    [
                        'type' => 'literal',
                        'property_id' => 1,
                        '@value' => sprintf('Test item %d', $i),
                    ],
                ],
            ]);
            $this->items[] = $response->getContent();
        }
    }

    public function tearDown()
    {
        foreach ($this->items as $item) {
            $this->api()->delete('items', $item->id());
        }
        $this->api()->delete('sites', $this->site->id());
    }

    protected function loginAsAdmin()
    {
        $application = $this->getApplication();
        $serviceLocator = $application->getServiceManager();
        $auth = $serviceLocator->get('Omeka\AuthenticationService');
        $adapter = $auth->getAdapter();
        $adapter->setIdentity('admin@example.com');
        $adapter->setCredential('root');
        $auth->authenticate();
    }

    protected function getServiceLocator()
    {
        return $this->getApplication()->getServiceManager();
    }

    protected function api()
    {
        return $this->getServiceLocator()->get('Omeka\ApiManager');
    }
}
