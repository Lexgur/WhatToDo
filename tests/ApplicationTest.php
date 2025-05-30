<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

class ApplicationTest extends WebTestCase
{
    public function testBadRequestException(): void
    {
        [$output, $statusCode] = $this->request('GET', '/test/bad-request');
        $json = json_decode($output, true);

        $this->assertEquals(400, $statusCode);
        $this->assertEquals('Bad Request', $json['error']);
        $this->assertEquals('Please check your information and try again.', $json['message']);
    }

    public function testUnauthorizedException(): void
    {
        [$output, $statusCode] = $this->request('GET', '/test/unauthorized');
        $json = json_decode($output, true);

        $this->assertEquals(401, $statusCode);
        $this->assertEquals('Unauthorized', $json['error']);
        $this->assertEquals('Authentication required to access this resource.', $json['message']);
    }

    public function testForbiddenException(): void
    {
        [$output, $statusCode] = $this->request('GET', '/test/forbidden');
        $json = json_decode($output, true);

        $this->assertEquals(403, $statusCode);
        $this->assertEquals('Forbidden', $json['error']);
        $this->assertEquals('You do not have permission to access this resource.', $json['message']);
    }

    public function testNotFoundException(): void
    {
        [$output, $statusCode] = $this->request('GET', '/test/not-found');
        $json = json_decode($output, true);

        $this->assertEquals(404, $statusCode);
        $this->assertEquals('Not Found', $json['error']);
        $this->assertEquals('The requested resource could not be found.', $json['message']);
    }

    public function testEmptyPathReturnsSportController(): void
    {
        [$output, $statusCode] = $this->request('GET', '');
        $json = json_decode($output, true);

        $this->assertEquals(200, $statusCode);
    }

    public function testJsonResponseReturnsProperly(): void
    {
        [$output, $statusCode] = $this->request('GET', '/test/json');
        $json = json_decode($output, true);

        $this->assertEquals(200, $statusCode);
        $this->assertEquals('success', $json['status']);
        $this->assertEquals('It works!', $json['data']['message']);
    }
}