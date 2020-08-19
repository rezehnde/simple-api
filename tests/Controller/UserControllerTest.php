<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateWithoutBody()
    {
        $client = static::createClient();

        $client->request('POST', '/user/create');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testReadUserThatNotExists()
    {
        $client = static::createClient();

        $client->request('POST', '/user/0');

        $this->assertEquals(405, $client->getResponse()->getStatusCode());
    }
}