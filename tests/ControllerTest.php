<?php

namespace Tests;

use Symfony\Component\Routing\Exception\RouteNotFoundException;

abstract class ControllerTest extends TestCase
{
//    use RefreshDatabase;
    protected string $model;
    protected string $routeName;
    protected string $varName;
    protected array $methods;


    /**
     * Test if con receive the index
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response_on_index(): void
    {
        $this->test_the_application_returns_a_successful_response('index');
    }

    /**
     * Test if con receive the correct response
     *
     * @return void
     */
    protected function test_the_application_returns_a_successful_response(string $method = 'index', array $params = []): void
    {
        try {
            $response = $this->get(route($this->routeName . '.' . $method, $params));
            if (in_array($method, array_keys($this->methods)))
                $response->assertStatus($this->methods[$method]);
            else
                $response->assertStatus(404);
        } catch (RouteNotFoundException) {
            $response = $this->get('/notFound');
            $response->assertStatus(404);
        }

    }

    /**
     * Test if con receive the create
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response_on_create(): void
    {
//        $this->seed();
        $this->test_the_application_returns_a_successful_response('create');
    }

    /**
     * Test if con receive the show
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response_on_show(): void
    {
        ${$this->varName} = call_user_func(array($this->model, 'all'))->random();
        $this->test_the_application_returns_a_successful_response('show', compact($this->varName));
    }

    /**
     * Test if con receive the update
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response_on_edit(): void
    {
        ${$this->varName} = call_user_func(array($this->model, 'all'))->random();#($this->model)::all()->random();
        $this->test_the_application_returns_a_successful_response('edit', compact($this->varName));
    }

}
