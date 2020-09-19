<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('button[type=submit]')->form();
        $form->setValues([
            'login[email]' => 'test@email.com',
            'login[password]' => '11111111',
        ]);
        $client->submit($form);
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testRegistration()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/registration');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('button[type=submit]')->form();
        $form->setValues([
            'registration[firstName]' => 'Test2',
            'registration[lastName]' => 'Test2',
            'registration[email]' => 'test2@email.com',
            'registration[plainPassword][first]' => '11111111',
            'registration[plainPassword][second]' => '11111111',
        ]);
        $client->submit($form);
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
