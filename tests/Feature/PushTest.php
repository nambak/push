<?php

namespace Tests\Feature;

use Tests\TestCase;

class PushTest extends TestCase
{
    /** @test */
    public function is_it_successfully_send_push_message()
    {
        $response = $this->post('/api/send_push', [
            'title'   => '상품 바로가기 테스트',
            'message' => '상품ID : 1103',
            'data'    => json_encode(['name' => 'productDetail', 'id' => 1103]),
        ]);

        $response->assertStatus(200);

        dump($response->getContent());
    }

    /** @test */
    public function test_user_push_send_api()
    {
        $response = $this->post('/api/send_test_push', [
            'title'   => '테스트 푸시 전송',
            'message' => '테스트 푸시 입니다.',
        ]);

        $response->dump();

        $response->assertSuccessful();
    }

    /** @test */
    public function test_user_push_send_api_with_app_link()
    {
        $response = $this->post('/api/send_test_push', [
            'title'   => '테스트 푸시 전송',
            'message' => '테스트 푸시 입니다. 앱링크도 전송합니다.',
            'data'    => ["name" =>"productList","id" => 6],
        ]);

        $response->dump();

        $response->assertSuccessful();
    }

}
